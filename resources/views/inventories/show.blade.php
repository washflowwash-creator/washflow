<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-sky-900">Inventory Item</h2></x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rbj-card p-6">
                <p><span class="font-semibold">Name:</span> {{ $inventory->name }}</p>
                <p class="mt-2"><span class="font-semibold">Quantity:</span> {{ $inventory->quantity }} {{ $inventory->unit }}</p>
                <p class="mt-2"><span class="font-semibold">Low Threshold:</span> {{ $inventory->low_stock_threshold }} {{ $inventory->unit }}</p>
            </div>
            <div class="mt-4 flex gap-2">
                <a href="{{ route('inventories.edit', $inventory) }}" class="rbj-btn-outline inline-flex">Edit</a>
                <a href="{{ route('inventories.index') }}" class="rbj-btn-outline inline-flex" onclick="history.back(); return false;">← Back</a>
            </div>
        </div>
    </div>
</x-app-layout>
