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

        // Determine pricing when service has a minimum pack (e.g. "Minimum 8 kg").
        [$unitPrice, $totalCost] = $this->calculatePricingForService(
            $booking->service_type,
            (float) ($validated['unit_price'] ?? $defaultRate),
            $weightKg
        );

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
            'total_cost' => $totalCost,
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
            $unitPriceInput = (float) ($validated['unit_price'] ?? $order->unit_price ?? 0);

            [$unitPrice, $totalCost] = $this->calculatePricingForService(
                $order->booking->service_type,
                $unitPriceInput,
                $weightKg
            );

            $order->update([
                'status' => $validated['status'],
                'weight_kg' => $weightKg,
                'unit_price' => $unitPrice,
                'total_cost' => $totalCost,
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

    /**
     * Extract minimum-pack information from a service type string.
     * Returns ['min' => ?float, 'per_pack' => bool]
     */
    private function extractServiceMinInfo(string $serviceType): array
    {
        $lower = strtolower($serviceType);

        $min = null;
        if (preg_match('/(\d+)\s*(?:kg)\b/i', $serviceType, $m)) {
            $min = (float) $m[1];
        }

        $perPack = false;
        if (str_contains($lower, 'per load') || str_contains($lower, '1 pc') || str_contains($lower, 'comforter') || str_contains($lower, 'per pack') || str_contains($lower, 'minimum')) {
            // treat named minimums and comforter as pack-style pricing when appropriate
            $perPack = str_contains($lower, 'per load') || str_contains($lower, '1 pc') || str_contains($lower, 'comforter');
            // if text mentions "minimum" but no explicit per-load phrasing, still use min
            if ($min === null && preg_match('/minimum\s*(\d+)\s*kg/i', $serviceType, $mm)) {
                $min = (float) $mm[1];
            }
        }

        return ['min' => $min, 'per_pack' => $perPack];
    }

    /**
     * Calculate unit price (per-kg) and total cost taking into account
     * minimum-pack pricing where admin may have stored a pack price.
     * Returns [unitPrice, totalCost].
     */
    private function calculatePricingForService(string $serviceType, float $adminPrice, float $weightKg): array
    {
        $info = $this->extractServiceMinInfo($serviceType);

        // If there's a minimum weight defined, treat admin price as price for the minimum
        if ($info['min'] !== null) {
            // For single-item per-load pricing (comforter / 1 pc per load), charge flat admin price
            if ($info['per_pack'] && (int) $info['min'] === 1) {
                $total = max($weightKg, 1) > 0 ? $adminPrice : $adminPrice;
                return [$adminPrice, $total];
            }

            // Otherwise admin price represents the price for the minimum weight (e.g. 99 for 8kg).
            $unitPerKg = $adminPrice / max(1, $info['min']);
            $chargeKg = max($weightKg, $info['min']);
            $total = $chargeKg * $unitPerKg;

            return [$unitPerKg, $total];
        }

        // default: admin price is per-kg
        return [$adminPrice, $weightKg * $adminPrice];
    }
}
