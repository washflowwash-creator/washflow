<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-sky-900">Order #{{ $order->id }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-800">{{ session('success') }}</div>
            @endif

            <div class="rbj-card p-6">
                <p><span class="font-semibold">Customer:</span> {{ $order->booking->user->name ?? '-' }}</p>
                <p class="mt-2"><span class="font-semibold">Service:</span> {{ $order->booking->service_type ?? '-' }}</p>
                <p class="mt-2"><span class="font-semibold">Current Status:</span> <span class="capitalize">{{ $order->status }}</span></p>
                <p class="mt-2"><span class="font-semibold">Total:</span> PHP {{ number_format($order->total_cost, 2) }}</p>

                <div class="mt-4">
                    <a href="{{ route('orders.receipt', $order) }}" class="rbj-btn-outline">Print Receipt</a>
                </div>

                <form method="POST" action="{{ route('orders.update', $order) }}" class="mt-6 grid gap-4 sm:grid-cols-2">
                    @csrf
                    @method('PUT')
                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700">Update Status</label>
                        <select name="status" class="rbj-input">
                            @foreach (\App\Models\Order::STATUSES as $status)
                                <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Weight (kg)</label>
                        <input type="number" step="0.01" min="0" name="weight_kg" value="{{ $order->weight_kg }}" class="rbj-input">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Unit Price</label>
                        <input type="number" step="0.01" min="0" name="unit_price" value="{{ $order->unit_price }}" class="rbj-input">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium text-slate-700">Inventory Items to Deduct</label>
                        <div class="mt-2 space-y-2 rounded-2xl border border-sky-100 bg-sky-50 p-3">
                            @foreach ($inventories as $item)
                                <div class="grid grid-cols-12 items-center gap-2">
                                    <label class="col-span-7 text-sm text-slate-700">{{ $item->name }} ({{ $item->unit }})</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="inventory_items[{{ $item->id }}]"
                                        value="{{ old('inventory_items.'.$item->id, $selectedInventory[$item->id] ?? 0) }}"
                                        class="rbj-input col-span-5 !mt-0"
                                    >
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <button class="rbj-btn-primary" type="submit">Update Order</button>
                        <a href="{{ route('orders.index') }}" class="rbj-btn-outline inline-flex ml-2">Back to Orders</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
