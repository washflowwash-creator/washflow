<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-sky-900">Inventory</h2>
            <a href="{{ route('inventories.create') }}" class="rbj-btn-primary">Add Item</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-800">{{ session('success') }}</div>
            @endif
            <div class="mb-4 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                <h3 class="font-semibold text-amber-800">Low Stock Alerts</h3>
                <ul class="mt-2 text-sm text-amber-900">
                    @forelse ($lowStockItems as $alert)
                        <li>{{ $alert->name }}: {{ $alert->quantity }} {{ $alert->unit }}</li>
                    @empty
                        <li>No low stock alerts.</li>
                    @endforelse
                </ul>
            </div>
            <div class="rbj-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-sky-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Qty</th>
                                <th class="px-4 py-3">Threshold</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr class="border-t border-sky-100">
                                    <td class="px-4 py-3">{{ $item->name }}</td>
                                    <td class="px-4 py-3">{{ $item->quantity }} {{ $item->unit }}</td>
                                    <td class="px-4 py-3">{{ $item->low_stock_threshold }} {{ $item->unit }}</td>
                                    <td class="px-4 py-3"><a href="{{ route('inventories.show', $item) }}" class="rbj-btn-outline !px-3 !py-1.5">View</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">{{ $items->links() }}</div>
        </div>
    </div>
</x-app-layout>
