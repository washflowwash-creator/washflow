<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-sky-900">Booking Details</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rbj-card p-6">
                @php
                    $badge = match ($booking->status) {
                        'pending' => 'rbj-badge rbj-badge-pending',
                        'washing', 'drying', 'picked-up' => 'rbj-badge rbj-badge-washing',
                        'ready' => 'rbj-badge rbj-badge-ready',
                        'completed' => 'rbj-badge rbj-badge-completed',
                        default => 'rbj-badge bg-slate-100 text-slate-700',
                    };
                @endphp
                <p><span class="font-semibold">Service:</span> {{ $booking->service_type }}</p>
                <p class="mt-2"><span class="font-semibold">Schedule:</span> {{ $booking->scheduled_at?->format('M d, Y h:i A') }}</p>
                <p class="mt-2"><span class="font-semibold">Status:</span> <span class="{{ $badge }}">{{ $booking->status }}</span></p>
                <p class="mt-2"><span class="font-semibold">Notes:</span> {{ $booking->notes ?: 'None' }}</p>

                @if ($booking->order)
                    <div class="mt-4 rounded-2xl bg-sky-50 p-4">
                        <p class="font-semibold text-sky-900">Order Linked</p>
                        <p class="mt-1 text-sm">Status: <span class="capitalize">{{ $booking->order->status }}</span></p>
                        <p class="mt-1 text-sm">Total: PHP {{ number_format($booking->order->total_cost, 2) }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
