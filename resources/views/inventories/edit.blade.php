<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-sky-900">Edit Inventory Item</h2></x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('inventories.update', $inventory) }}" class="rbj-card space-y-4 p-6">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-sm font-medium text-slate-700">Name</label>
                    <input name="name" value="{{ $inventory->name }}" required class="rbj-input">
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="text-sm font-medium text-slate-700">Quantity</label>
                        <input type="number" step="0.01" min="0" name="quantity" value="{{ $inventory->quantity }}" required class="rbj-input">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Unit</label>
                        <input name="unit" value="{{ $inventory->unit }}" required class="rbj-input">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Low Stock Threshold</label>
                        <input type="number" step="0.01" min="0" name="low_stock_threshold" value="{{ $inventory->low_stock_threshold }}" required class="rbj-input">
                    </div>
                </div>
                <button class="rbj-btn-primary" type="submit">Update Item</button>
                <a href="{{ route('inventories.index') }}" class="rbj-btn-outline inline-flex">Cancel</a>
            </form>
        </div>
    </div>
</x-app-layout>
