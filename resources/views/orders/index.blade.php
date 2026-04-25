<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-sky-900">Orders</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('orders.history') }}" class="rbj-btn-outline">History</a>
                <a href="{{ route('orders.create') }}" class="rbj-btn-primary">Create Order</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-800">{{ session('success') }}</div>
            @endif

            <div class="rbj-card mb-4 p-4">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-sky-900">Pending Booking Requests (Customer)</h3>
                    <span class="rbj-badge rbj-badge-pending">{{ $pendingBookings->count() }} pending</span>
                </div>

                @if ($pendingBookings->isEmpty())
                    <p class="text-sm text-slate-500">No pending booking requests right now.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="bg-sky-50 text-slate-600">
                                <tr>
                                    <th class="px-3 py-2">Booking #</th>
                                    <th class="px-3 py-2">Customer</th>
                                    <th class="px-3 py-2">Service</th>
                                    <th class="px-3 py-2">Schedule</th>
                                    <th class="px-3 py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingBookings as $booking)
                                    <tr class="border-t border-sky-100">
                                        <td class="px-3 py-2">{{ $booking->id }}</td>
                                        <td class="px-3 py-2">{{ $booking->user->name ?? '-' }}</td>
                                        <td class="px-3 py-2">{{ $booking->service_type }}</td>
                                        <td class="px-3 py-2">{{ $booking->scheduled_at?->format('M d, Y h:i A') }}</td>
                                        <td class="px-3 py-2">
                                            <a href="{{ route('orders.create', ['booking_id' => $booking->id]) }}" class="rbj-btn-primary !px-3 !py-1.5">Create Order</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="rbj-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-sky-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">Customer</th>
                                <th class="px-4 py-3">Service</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                @php
                                    $badge = match ($order->status) {
                                        'pending' => 'rbj-badge rbj-badge-pending',
                                        'washing', 'drying', 'picked-up' => 'rbj-badge rbj-badge-washing',
                                        'ready' => 'rbj-badge rbj-badge-ready',
                                        'completed' => 'rbj-badge rbj-badge-completed',
                                        default => 'rbj-badge bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <tr class="border-t border-sky-100">
                                    <td class="px-4 py-3">{{ $order->id }}</td>
                                    <td class="px-4 py-3">{{ $order->booking->user->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">{{ $order->booking->service_type ?? '-' }}</td>
                                    <td class="px-4 py-3"><span class="{{ $badge }}">{{ $order->status }}</span></td>
                                    <td class="px-4 py-3">PHP {{ number_format($order->total_cost, 2) }}</td>
                                    <td class="px-4 py-3"><a href="{{ route('orders.show', $order) }}" class="rbj-btn-outline !px-3 !py-1.5">Manage</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">{{ $orders->links() }}</div>
        </div>
    </div>
</x-app-layout>
