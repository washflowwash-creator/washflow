<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-sky-900">Shop Settings</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="rbj-card p-6">
                <h3 class="text-lg font-semibold mb-4">Manage Shop Operations</h3>

                <form method="POST" action="{{ route('admin.shop-settings.update') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="text-sm font-medium text-slate-700">Shop Status</label>
                        <select name="status" class="rbj-input">
                            <option value="OPEN" @selected(old('status', $shop->status) === 'OPEN')>OPEN - Accepting Orders</option>
                            <option value="TEMPORARILY_UNAVAILABLE" @selected(old('status', $shop->status) === 'TEMPORARILY_UNAVAILABLE')>TEMPORARILY UNAVAILABLE</option>
                            <option value="CLOSED" @selected(old('status', $shop->status) === 'CLOSED')>CLOSED</option>
                        </select>
                        @error('status') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700">Total Capacity (concurrent loads)</label>
                        <input type="number" name="capacity" value="{{ old('capacity', $shop->capacity) }}" min="1" class="rbj-input">
                        @error('capacity') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700">Processing Capacity (loads per day)</label>
                        <input type="number" name="processing_capacity" value="{{ old('processing_capacity', $shop->processing_capacity) }}" min="1" class="rbj-input">
                        @error('processing_capacity') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700">Current Active Loads</label>
                        <input type="number" name="current_active_loads" value="{{ old('current_active_loads', $shop->current_active_loads) }}" min="0" class="rbj-input">
                        @error('current_active_loads') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-xs text-slate-500">Update this as orders progress through the wash cycle.</p>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="rbj-btn-primary">Save Settings</button>
                        <a href="{{ route('dashboard') }}" class="rbj-btn-outline inline-flex">Cancel</a>
                    </div>
                </form>

                <div class="mt-6 pt-6 border-t border-slate-200">
                    <h4 class="font-semibold mb-3">Current Queue Status</h4>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-sky-100 p-3 text-center">
                            <p class="text-sm text-slate-600">Active Loads</p>
                            <p class="text-2xl font-bold text-sky-900">{{ $shop->current_active_loads }}</p>
                        </div>
                        <div class="rounded-2xl border border-sky-100 p-3 text-center">
                            <p class="text-sm text-slate-600">Available Slots</p>
                            <p class="text-2xl font-bold text-sky-900">{{ max(0, $shop->capacity - $shop->current_active_loads) }}</p>
                        </div>
                        <div class="rounded-2xl border border-sky-100 p-3 text-center">
                            <p class="text-sm text-slate-600">Capacity %</p>
                            <p class="text-2xl font-bold" :style="{ color: ($shop->current_active_loads / max(1, $shop->capacity) * 100) >= 80 ? '#dc2626' : '#0369a1' }">
                                {{ floor(($shop->current_active_loads / max(1, $shop->capacity) * 100)) }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
