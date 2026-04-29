<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-sky-900">Payment #{{ $payment->id }}</h2></x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-800">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-2xl border border-rose-300 bg-rose-50 px-4 py-3 text-rose-800">{{ session('error') }}</div>
            @endif

            <div class="rbj-card p-6">
                <p><span class="font-semibold">Order:</span> #{{ $payment->order_id }}</p>
                <p class="mt-2"><span class="font-semibold">Customer:</span> {{ $payment->order->booking->user->name ?? '-' }}</p>
                <p class="mt-2"><span class="font-semibold">Method:</span> {{ $payment->payment_method }}</p>
                <p class="mt-2"><span class="font-semibold">Status:</span> <span class="{{ $payment->payment_status === 'paid' ? 'rbj-badge rbj-badge-completed' : 'rbj-badge rbj-badge-pending' }}">{{ $payment->payment_status }}</span></p>
                <p class="mt-2"><span class="font-semibold">Order Total:</span> PHP {{ number_format($payment->order->total_cost, 2) }}</p>
                @if($payment->discount_amount > 0)
                    <p class="mt-2"><span class="font-semibold text-emerald-700">Discount (50% OFF):</span> -PHP {{ number_format($payment->discount_amount, 2) }}</p>
                    <p class="mt-2"><span class="font-semibold">Final Amount:</span> PHP {{ number_format($payment->amount, 2) }}</p>
                @else
                    <p class="mt-2"><span class="font-semibold">Amount:</span> PHP {{ number_format($payment->amount, 2) }}</p>
                @endif
                <p class="mt-2"><span class="font-semibold">Balance:</span> PHP {{ number_format(max(0, (float) $payment->order->total_cost - (float) $payment->amount), 2) }}</p>
                <p class="mt-2"><span class="font-semibold">Reference:</span> {{ $payment->reference ?: 'None' }}</p>
                <p class="mt-2"><span class="font-semibold">Paid At:</span> {{ $payment->paid_at?->format('M d, Y h:i A') ?? 'Not yet paid' }}</p>
            </div>

            @php
                $user = $payment->order->user;
                $loyaltyService = new \App\Services\LoyaltyRewardService();
                $hasReward = $loyaltyService->hasAvailableReward($user) && !$payment->reward_redeemed;
            @endphp

            @if($hasReward)
                <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                    <p class="text-sm font-semibold text-emerald-900">Loyalty Reward Available</p>
                    <p class="text-sm text-emerald-700 mt-1">This customer has earned a 50% OFF reward. Apply it to this payment?</p>
                    <form method="POST" action="{{ route('payments.apply-reward', $payment) }}" class="mt-3">
                        @csrf
                        <button type="submit" class="rbj-btn-primary">Apply 50% OFF Reward</button>
                    </form>
                </div>
            @elseif($payment->reward_redeemed)
                <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-700">Reward Redeemed</p>
                    <p class="text-sm text-slate-600 mt-1">50% OFF discount was applied to this payment.</p>
                </div>
            @endif

            <div class="mt-4 flex items-center gap-2">
                <a href="{{ route('payments.edit', $payment) }}" class="rbj-btn-outline inline-flex">Edit Payment</a>
                <a href="{{ route('orders.receipt', $payment->order) }}" class="rbj-btn-primary inline-flex">Receipt</a>
                <a href="{{ route('payments.index') }}" class="rbj-btn-outline inline-flex" onclick="history.back(); return false;">← Back</a>
            </div>
        </div>
    </div>
</x-app-layout>
