<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-sky-900">Create Booking</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('bookings.store') }}" class="rbj-card space-y-4 p-6">
                @csrf
                @if(isset($shop) && $shop->status !== 'OPEN')
                    <div class="mb-3 rounded-2xl border border-rose-100 bg-rose-50 p-3 text-rose-700">The shop is currently <strong>{{ $shop->status }}</strong> and may not be accepting bookings.</div>
                @elseif(isset($shop) && $shop->current_active_loads >= $shop->capacity)
                    <div class="mb-3 rounded-2xl border border-rose-100 bg-rose-50 p-3 text-rose-700">Capacity full: the shop cannot accept new orders right now.</div>
                @elseif(isset($shop) && ($shop->current_active_loads / max(1, $shop->capacity) * 100) >= $shop->nearly_full_threshold)
                    <div class="mb-3 rounded-2xl border border-amber-100 bg-amber-50 p-3 text-amber-700">Nearly full: please expect delays.</div>
                @endif
                <div>
                    <label class="text-sm font-medium text-slate-700">Service Type</label>
                    <select name="service_type" required class="rbj-input">
                        <option value="">Select service</option>
                        @foreach ($serviceTypes as $serviceType)
                            <option value="{{ $serviceType }}" @selected(old('service_type') === $serviceType)>{{ $serviceType }}</option>
                        @endforeach
                    </select>
                    @error('service_type') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Schedule</label>
                    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" min="{{ now()->startOfMinute()->format('Y-m-d\\TH:i') }}" required class="rbj-input">
                    @error('scheduled_at') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Notes</label>
                    <textarea name="notes" rows="4" class="rbj-input">{{ old('notes') }}</textarea>
                </div>
                <div class="flex gap-2">
                    <button class="rbj-btn-primary" type="submit">Create Booking</button>
                    <a href="{{ route('bookings.index') }}" class="rbj-btn-outline inline-flex">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
