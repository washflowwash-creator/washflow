<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\ServiceRate;
use App\Models\Transaction;
use App\Services\LoyaltyRewardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::query()
            ->with(['booking.user', 'payment'])
            ->where('status', '!=', 'completed')
            ->latest()
            ->paginate(15);

        $pendingBookings = Booking::query()
            ->with('user')
            ->doesntHave('order')
            ->where('status', 'pending')
            ->orderBy('scheduled_at')
            ->take(10)
            ->get();

        return view('orders.index', compact('orders', 'pendingBookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $bookings = Booking::query()
            ->with('user')
            ->doesntHave('order')
            ->where('status', 'pending')
            ->orderBy('scheduled_at')
            ->get();

        $selectedBookingId = (int) $request->integer('booking_id');

        $inventories = Inventory::query()->orderBy('name')->get();
        $serviceRateMap = ServiceRate::query()
            ->pluck('price_per_kg', 'service_type')
            ->toArray();

        return view('orders.create', compact('bookings', 'selectedBookingId', 'inventories', 'serviceRateMap'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'exists:bookings,id'],
            'weight_kg' => ['nullable', 'numeric', 'min:0'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'inventory_items' => ['nullable', 'array'],
            'inventory_items.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        $booking = Booking::query()->findOrFail($validated['booking_id']);

        abort_if($booking->order()->exists(), 422, 'Booking already has an order.');

        $weightKg = (float) ($validated['weight_kg'] ?? 0);
        $defaultRate = (float) ServiceRate::query()
            ->where('service_type', $booking->service_type)
            ->value('price_per_kg');
        $unitPrice = (float) ($validated['unit_price'] ?? $defaultRate);

        $deductions = collect($validated['inventory_items'] ?? [])
            ->filter(fn ($qty): bool => is_numeric($qty) && (float) $qty > 0)
            ->map(fn ($qty): float => (float) $qty)
            ->all();

        $order = Order::create([
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id,
            'status' => 'pending',
            'weight_kg' => $weightKg,
            'unit_price' => $unitPrice,
            'total_cost' => $weightKg * $unitPrice,
            'inventory_deduction_json' => empty($deductions) ? null : json_encode($deductions),
        ]);

        // assign readable order number and estimated completion
        $order->order_number = 'WF'.str_pad($order->id, 6, '0', STR_PAD_LEFT);
        $order->estimated_completed_at = $order->estimateCompletion();
        $order->save();

        $booking->update(['status' => 'picked-up']);

        return redirect()->route('orders.show', $order)->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['booking.user', 'payment', 'transaction']);

        $inventories = Inventory::query()->orderBy('name')->get();
        $selectedInventory = $this->parseInventoryJson($order->inventory_deduction_json);

        return view('orders.show', compact('order', 'inventories', 'selectedInventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', Order::STATUSES)],
            'weight_kg' => ['nullable', 'numeric', 'min:0'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'inventory_items' => ['nullable', 'array'],
            'inventory_items.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated, $order): void {
            $oldStatus = $order->status;

            $weightKg = (float) ($validated['weight_kg'] ?? $order->weight_kg ?? 0);
            $unitPrice = (float) ($validated['unit_price'] ?? $order->unit_price ?? 0);

            $order->update([
                'status' => $validated['status'],
                'weight_kg' => $weightKg,
                'unit_price' => $unitPrice,
                'total_cost' => $weightKg * $unitPrice,
                'inventory_deduction_json' => empty($validated['inventory_items'] ?? [])
                    ? $order->inventory_deduction_json
                    : json_encode(collect($validated['inventory_items'])
                        ->filter(fn ($qty): bool => is_numeric($qty) && (float) $qty > 0)
                        ->map(fn ($qty): float => (float) $qty)
                        ->all()),
            ]);

            if ($oldStatus !== 'washing' && $validated['status'] === 'washing') {
                $this->deductInventoryForOrder($order);
            }

            if ($validated['status'] === 'completed') {
                $order->booking->update(['status' => 'completed']);
                $order->completed_at = now();
                $order->save();

                Transaction::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'user_id' => $order->user_id,
                        'amount' => $order->total_cost,
                        'service_summary' => $order->booking->service_type,
                        'completed_at' => now(),
                    ]
                );

                // award loyalty stamp on completion
                (new LoyaltyRewardService())->awardStamp($order->user);
            } else {
                $order->booking->update(['status' => $validated['status']]);
            }
        });

        return redirect()->route('orders.show', $order)->with('success', 'Order updated.');
    }

    public function history()
    {
        $orders = Order::query()
            ->with(['booking.user', 'payment', 'transaction'])
            ->where('status', 'completed')
            ->latest()
            ->paginate(20);

        return view('orders.history', compact('orders'));
    }

    public function receipt(Order $order)
    {
        $order->load(['booking.user', 'payment', 'transaction']);

        return view('orders.receipt', compact('order'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted.');
    }

    private function deductInventoryForOrder(Order $order): void
    {
        $items = $this->parseInventoryJson($order->inventory_deduction_json);

        foreach ($items as $itemId => $qty) {
            if ($qty <= 0) {
                continue;
            }

            $inventory = Inventory::query()->find((int) $itemId);

            if (! $inventory && is_string($itemId)) {
                $inventory = Inventory::query()->where('name', $itemId)->first();
            }

            if ($inventory) {
                $inventory->update([
                    'quantity' => max(0, (float) $inventory->quantity - (float) $qty),
                ]);
            }
        }
    }

    private function parseInventoryJson(?string $json): array
    {
        if (! $json) {
            return [];
        }

        $decoded = json_decode($json, true);

        if (! is_array($decoded)) {
            return [];
        }

        return collect($decoded)
            ->filter(fn ($qty, $item): bool => (is_numeric($item) || is_string($item)) && is_numeric($qty))
            ->map(fn ($qty): float => (float) $qty)
            ->all();
    }
}
