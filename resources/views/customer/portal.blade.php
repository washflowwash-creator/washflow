<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-sky-900">Customer Portal</h2></x-slot>

    @php
        $statusClass = function ($status) {
            return match ($status) {
                'pending' => 'rbj-badge rbj-badge-pending',
                'washing', 'drying', 'picked-up' => 'rbj-badge rbj-badge-washing',
                'ready' => 'rbj-badge rbj-badge-ready',
                'completed' => 'rbj-badge rbj-badge-completed',
                default => 'rbj-badge bg-slate-100 text-slate-700',
            };
        };
    @endphp

    <div class="py-8">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-4 flex items-center justify-between gap-3">
                <h3 class="text-lg font-semibold text-sky-900">Quick Actions</h3>
                <a href="{{ route('bookings.create') }}" class="rbj-btn-primary">Book Service</a>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rbj-card p-5">
                    <h4 class="text-base font-semibold text-sky-900">Recent Bookings</h4>
                    <ul class="mt-3 space-y-2 text-sm">
                        @forelse ($bookings as $booking)
                            <li class="rounded-2xl border border-sky-100 p-3">
                                <p class="font-medium">{{ $booking->service_type }}</p>
                                <p class="text-slate-600">{{ $booking->scheduled_at?->format('M d, Y h:i A') }}</p>
                                <p class="mt-1">Status: <span class="{{ $statusClass($booking->status) }}">{{ $booking->status }}</span></p>
                            </li>
                        @empty
                            <li class="text-slate-500">No bookings found.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="rbj-card p-5">
                    <h4 class="text-base font-semibold text-sky-900">Order Tracking</h4>
                    <ul class="mt-3 space-y-2 text-sm">
                        @forelse ($orders as $order)
                            <li class="rounded-2xl border border-sky-100 p-3">
                                <p class="font-medium">Order #{{ $order->id }}</p>
                                <p class="mt-1">Status: <span class="{{ $statusClass($order->status) }}">{{ $order->status }}</span></p>
                                <p class="text-slate-600">Amount: PHP {{ number_format($order->total_cost, 2) }}</p>
                                <p class="text-slate-600">Payment: {{ $order->payment?->payment_status ?? 'unpaid' }}</p>
                            </li>
                        @empty
                            <li class="text-slate-500">No orders found.</li>
                        @endforelse
                    </ul>
                    <p class="mt-3 text-xs text-slate-500">Tracking refreshes on page reload, keeping the setup simple and free.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
