<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::query()
            ->with('order.booking')
            ->latest()
            ->paginate(15);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::query()
            ->whereDoesntHave('payment')
            ->with('booking')
            ->orderByDesc('created_at')
            ->get();

        return view('payments.create', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required', 'exists:orders,id'],
            'payment_method' => ['required', 'in:cash,gcash/manual'],
            'amount' => ['required', 'numeric', 'min:0'],
            'is_full_payment' => ['nullable', 'boolean'],
            'reference' => ['nullable', 'string', 'max:255'],
        ]);

        $order = Order::query()->findOrFail($validated['order_id']);
        $amount = $request->boolean('is_full_payment')
            ? (float) $order->total_cost
            : min((float) $validated['amount'], (float) $order->total_cost);
        $paymentStatus = $amount >= (float) $order->total_cost ? 'paid' : 'unpaid';

        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'user_id' => $order->user_id,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $paymentStatus,
                'amount' => $amount,
                'paid_at' => $paymentStatus === 'paid' ? now() : null,
                'reference' => $validated['reference'] ?? null,
            ]
        );

        return redirect()->route('payments.index')->with('success', 'Payment saved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load('order.booking.user');

        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        return view('payments.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:cash,gcash/manual'],
            'amount' => ['required', 'numeric', 'min:0'],
            'is_full_payment' => ['nullable', 'boolean'],
            'reference' => ['nullable', 'string', 'max:255'],
        ]);

        $orderTotal = (float) $payment->order->total_cost;
        $amount = $request->boolean('is_full_payment')
            ? $orderTotal
            : min((float) $validated['amount'], $orderTotal);
        $paymentStatus = $amount >= $orderTotal ? 'paid' : 'unpaid';

        $payment->update([
            'payment_method' => $validated['payment_method'],
            'payment_status' => $paymentStatus,
            'amount' => $amount,
            'paid_at' => $paymentStatus === 'paid' ? ($payment->paid_at ?? now()) : null,
            'reference' => $validated['reference'] ?? null,
        ]);

        return redirect()->route('payments.show', $payment)->with('success', 'Payment updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Payment deleted.');
    }
}
