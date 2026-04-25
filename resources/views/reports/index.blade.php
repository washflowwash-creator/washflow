<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-sky-900">Reports & Analytics</h2>
            <a href="{{ route('reports.export.csv', request()->query()) }}" class="rbj-btn-outline">Export CSV</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Filter Section -->
            <div class="rbj-card mb-6 p-6">
                <h3 class="mb-4 text-lg font-semibold text-sky-900">Filters</h3>
                <form method="GET" action="{{ route('reports.index') }}" class="space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">From Date</label>
                            <input type="date" name="from_date" value="{{ $fromDate }}" class="rbj-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">To Date</label>
                            <input type="date" name="to_date" value="{{ $toDate }}" class="rbj-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Service Type</label>
                            <select name="service_type" class="rbj-input">
                                <option value="">All Services</option>
                                @foreach ($serviceTypes as $type)
                                    <option value="{{ $type }}" @selected($serviceType === $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                            <select name="status" class="rbj-input">
                                <option value="">All Statuses</option>
                                <option value="paid" @selected($status === 'paid')>Paid</option>
                                <option value="unpaid" @selected($status === 'unpaid')>Unpaid</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="submit" class="rbj-btn-primary">Apply Filters</button>
                        <a href="{{ route('reports.index') }}" class="rbj-btn-outline">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Summary Stats -->
            <div class="mb-6 grid gap-4 sm:grid-cols-3">
                <div class="rbj-stat">
                    <p class="text-sm text-slate-500">Total Orders</p>
                    <p class="mt-2 text-3xl font-bold text-sky-900">{{ $summary['total_orders'] }}</p>
                </div>
                <div class="rbj-stat">
                    <p class="text-sm text-slate-500">Total Revenue</p>
                    <p class="mt-2 text-3xl font-bold text-sky-800">PHP {{ number_format($summary['total_revenue'], 2) }}</p>
                </div>
                <div class="rbj-stat">
                    <p class="text-sm text-slate-500">Avg Order Value</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-700">PHP {{ number_format($summary['avg_order_value'], 2) }}</p>
                </div>
            </div>

            <!-- Daily Summary Table -->
            <div class="rbj-card mb-6 overflow-hidden">
                <div class="border-b border-sky-100 px-6 py-4">
                    <h3 class="font-semibold text-sky-900">Daily Summary</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-sky-50 text-slate-600">
                            <tr>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Orders</th>
                                <th class="px-6 py-3">Revenue</th>
                                <th class="px-6 py-3">Avg Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($daily as $row)
                                <tr class="border-t border-sky-100 hover:bg-sky-50">
                                    <td class="px-6 py-3 font-medium">{{ \Carbon\Carbon::parse($row->report_date)->format('M d, Y') }}</td>
                                    <td class="px-6 py-3">{{ $row->orders }}</td>
                                    <td class="px-6 py-3 font-semibold text-sky-700">PHP {{ number_format($row->revenue, 2) }}</td>
                                    <td class="px-6 py-3 text-slate-600">PHP {{ number_format($row->orders > 0 ? $row->revenue / $row->orders : 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">No data found for the selected filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detailed Transactions -->
            <div class="rbj-card overflow-hidden">
                <div class="border-b border-sky-100 px-6 py-4">
                    <h3 class="font-semibold text-sky-900">Transaction Details</h3>
                    <p class="text-sm text-slate-500">Showing {{ $detailedTransactions->count() }} of {{ $detailedTransactions->total() }} transactions</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-sky-50 text-slate-600">
                            <tr>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Order ID</th>
                                <th class="px-6 py-3">Customer</th>
                                <th class="px-6 py-3">Service</th>
                                <th class="px-6 py-3">Amount</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($detailedTransactions as $transaction)
                                <tr class="border-t border-sky-100 hover:bg-sky-50">
                                    <td class="px-6 py-3 text-slate-600">{{ $transaction->completed_at->format('M d, Y H:i') }}</td>
                                    <td class="px-6 py-3 font-semibold text-sky-700">#{{ $transaction->order_id ?? 'N/A' }}</td>
                                    <td class="px-6 py-3">{{ $transaction->order?->booking?->user?->name ?? 'Unknown' }}</td>
                                    <td class="px-6 py-3">{{ $transaction->order?->booking?->service_type ?? 'N/A' }}</td>
                                    <td class="px-6 py-3 font-semibold text-sky-700">PHP {{ number_format($transaction->amount, 2) }}</td>
                                    <td class="px-6 py-3">
                                        @php
                                            $paymentStatus = $transaction->order?->payment?->payment_status ?? 'unpaid';
                                            $statusClass = match($paymentStatus) {
                                                'paid' => 'rbj-badge rbj-badge-completed',
                                                'unpaid' => 'rbj-badge rbj-badge-pending',
                                                default => 'rbj-badge bg-slate-100 text-slate-700',
                                            };
                                        @endphp
                                        <span class="{{ $statusClass }}">{{ ucfirst($paymentStatus) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">No transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($detailedTransactions->hasPages())
                    <div class="border-t border-sky-100 px-6 py-4">
                        {{ $detailedTransactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
