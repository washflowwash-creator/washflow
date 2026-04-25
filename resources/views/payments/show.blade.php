<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-sky-900">Payment #{{ $payment->id }}</h2></x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rbj-card p-6">
                <p><span class="font-semibold">Order:</span> #{{ $payment->order_id }}</p>
                <p class="mt-2"><span class="font-semibold">Customer:</span> {{ $payment->order->booking->user->name ?? '-' }}</p>
                <p class="mt-2"><span class="font-semibold">Method:</span> {{ $payment->payment_method }}</p>
                <p class="mt-2"><span class="font-semibold">Status:</span> <span class="{{ $payment->payment_status === 'paid' ? 'rbj-badge rbj-badge-completed' : 'rbj-badge rbj-badge-pending' }}">{{ $payment->payment_status }}</span></p>
                <p class="mt-2"><span class="font-semibold">Amount:</span> PHP {{ number_format($payment->amount, 2) }}</p>
                <p class="mt-2"><span class="font-semibold">Order Total:</span> PHP {{ number_format($payment->order->total_cost, 2) }}</p>
                <p class="mt-2"><span class="font-semibold">Balance:</span> PHP {{ number_format(max(0, (float) $payment->order->total_cost - (float) $payment->amount), 2) }}</p>
                <p class="mt-2"><span class="font-semibold">Reference:</span> {{ $payment->reference ?: 'None' }}</p>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <a href="{{ route('payments.edit', $payment) }}" class="rbj-btn-outline inline-flex">Edit Payment</a>
                <a href="{{ route('orders.receipt', $payment->order) }}" class="rbj-btn-primary inline-flex">Receipt</a>
                <a href="{{ route('payments.index') }}" class="rbj-btn-outline inline-flex" onclick="history.back(); return false;">← Back</a>
            </div>
        </div>
    </div>
</x-app-layout>
