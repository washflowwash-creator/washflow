<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-sky-900">Create Booking</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('bookings.store') }}" class="rbj-card space-y-4 p-6">
                @csrf
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
                    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" required class="rbj-input">
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
