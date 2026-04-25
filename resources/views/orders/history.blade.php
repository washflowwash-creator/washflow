<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-sky-900">Completed Orders History</h2>
            <a href="{{ route('orders.index') }}" class="rbj-btn-outline">Back to Active Orders</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rbj-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-sky-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3">Order #</th>
                                <th class="px-4 py-3">Customer</th>
                                <th class="px-4 py-3">Service</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Completed At</th>
                                <th class="px-4 py-3">Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr class="border-t border-sky-100">
                                    <td class="px-4 py-3">{{ $order->id }}</td>
                                    <td class="px-4 py-3">{{ $order->booking->user->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $order->booking->service_type ?? '-' }}</td>
                                    <td class="px-4 py-3">PHP {{ number_format($order->total_cost, 2) }}</td>
                                    <td class="px-4 py-3">{{ $order->transaction?->completed_at?->format('M d, Y h:i A') ?? '-' }}</td>
                                    <td class="px-4 py-3"><a href="{{ route('orders.receipt', $order) }}" class="rbj-btn-primary !px-3 !py-1.5">View</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-500">No completed orders yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">{{ $orders->links() }}</div>
        </div>
    </div>
</x-app-layout>
