<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="rbj-page-title">RBJ Laundry Dashboard</h2>
                <p class="mt-1 text-sm text-slate-600">Operational view for your role</p>
            </div>
            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold capitalize text-sky-700">{{ auth()->user()->role }}</span>
        </div>
    </x-slot>

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

        $progress = fn ($status) => match ($status) {
            'pending' => 10,
            'picked-up' => 25,
            'washing' => 50,
            'drying' => 70,
            'ready' => 90,
            'completed' => 100,
            default => 0,
        };
    @endphp

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-800">{{ session('success') }}</div>
            @endif

            @if (auth()->user()->isCustomer())
                <section class="grid gap-5 lg:grid-cols-3">
                    <article class="rbj-panel p-5 lg:col-span-2">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-sky-900">Active Orders</h3>
                            <a href="{{ route('customer.portal') }}" class="rbj-btn-outline">My Orders</a>
                        </div>

                        <div class="space-y-4">
                            @forelse ($recentOrders as $order)
                                <div class="rounded-2xl border border-sky-100 p-4">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <p class="font-medium text-slate-700">Order #{{ $order->id }} - {{ $order->booking->service_type ?? 'Laundry' }}</p>
                                        <span class="{{ $statusClass($order->status) }}">{{ $order->status }}</span>
                                    </div>
                                    <div class="mt-3 h-2 w-full rounded-full bg-sky-100">
                                        <div class="h-2 rounded-full bg-gradient-to-r from-sky-400 to-blue-600" style="width: {{ $progress($order->status) }}%"></div>
                                    </div>
                                    <p class="mt-2 text-xs text-slate-500">PHP {{ number_format($order->total_cost, 2) }} - Payment: {{ $order->payment?->payment_status ?? 'unpaid' }}</p>
                                </div>
                            @empty
                                <p class="rounded-2xl border border-dashed border-sky-200 p-4 text-sm text-slate-500">No active orders yet.</p>
                            @endforelse
                        </div>
                    </article>

                    <article class="rbj-panel p-5">
                        <h3 class="text-lg font-semibold text-sky-900">Book Service</h3>
                        <form method="POST" action="{{ route('bookings.store') }}" class="mt-4 space-y-3">
                            @csrf
                            <div>
                                <label class="text-sm font-medium text-slate-700">Service Type</label>
                                <select name="service_type" class="rbj-input" required>
                                    <option value="Wash and Fold">Wash and Fold</option>
                                    <option value="Wash and Dry">Wash and Dry</option>
                                    <option value="Premium Care">Premium Care</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-700">Pickup or Drop-off</label>
                                <select name="notes" class="rbj-input">
                                    <option value="Pickup">Pickup</option>
                                    <option value="Drop-off">Drop-off</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-700">Date and Time</label>
                                <input type="datetime-local" name="scheduled_at" class="rbj-input" required>
                            </div>
                            <button class="rbj-btn-primary w-full" type="submit">Submit Booking</button>
                        </form>
                    </article>
                </section>
            @else
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('orders.create') }}" class="rbj-btn-primary">New Order</a>
                    <a href="{{ route('orders.history') }}" class="rbj-btn-outline">History</a>
                    <a href="{{ route('service-rates.index') }}" class="rbj-btn-outline">Service Rates</a>
                </div>

                <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <article class="rbj-stat">
                        <p class="text-sm text-slate-500">Today's Orders</p>
                        <p class="mt-2 text-3xl font-bold text-sky-900">{{ $orderCount }}</p>
                    </article>
                    <article class="rbj-stat">
                        <p class="text-sm text-slate-500">Today's Revenue</p>
                        <p class="mt-2 text-3xl font-bold text-sky-800">PHP {{ number_format($revenue, 2) }}</p>
                    </article>
                    <article class="rbj-stat">
                        <p class="text-sm text-slate-500">Today's Pending</p>
                        <p class="mt-2 text-3xl font-bold text-amber-600">{{ $pendingCount }}</p>
                    </article>
                </section>

                <section class="grid gap-5 lg:grid-cols-3">
                    <article class="rbj-panel p-5 lg:col-span-2">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-sky-900">Orders Queue</h3>
                            <a href="{{ route('orders.index') }}" class="rbj-btn-outline">Open Orders</a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="text-slate-500">
                                    <tr>
                                        <th class="px-2 py-2">Order</th>
                                        <th class="px-2 py-2">Customer</th>
                                        <th class="px-2 py-2">Status</th>
                                        <th class="px-2 py-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentOrders as $order)
                                        <tr class="border-t border-sky-100">
                                            <td class="px-2 py-3">#{{ $order->id }}</td>
                                            <td class="px-2 py-3">{{ $order->booking->user->name ?? '-' }}</td>
                                            <td class="px-2 py-3"><span class="{{ $statusClass($order->status) }}">{{ $order->status }}</span></td>
                                            <td class="px-2 py-3"><a href="{{ route('orders.show', $order) }}" class="rbj-btn-outline !px-3 !py-1.5">Update</a></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-2 py-4 text-slate-500">No orders yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </article>

                    <article class="rbj-panel p-5">
                        <h3 class="text-lg font-semibold text-sky-900">Inventory Alerts</h3>
                        <ul class="mt-4 space-y-3 text-sm">
                            @forelse ($lowStockItems as $item)
                                <li class="rounded-2xl border border-amber-200 bg-amber-50 p-3">
                                    <p class="font-semibold text-amber-800">{{ $item->name }}</p>
                                    <p class="text-amber-700">{{ $item->quantity }} {{ $item->unit }} left</p>
                                </li>
                            @empty
                                <li class="rounded-2xl border border-emerald-200 bg-emerald-50 p-3 text-emerald-700">All supplies are above threshold.</li>
                            @endforelse
                        </ul>
                    </article>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
