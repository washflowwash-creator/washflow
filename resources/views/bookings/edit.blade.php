<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-sky-900">Edit Booking</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('bookings.update', $booking) }}" class="rbj-card space-y-4 p-6">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-sm font-medium text-slate-700">Service Type</label>
                    <select name="service_type" required class="rbj-input">
                        @foreach ($serviceTypes as $serviceType)
                            <option value="{{ $serviceType }}" @selected(old('service_type', $booking->service_type) === $serviceType)>{{ $serviceType }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Schedule</label>
                    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', $booking->scheduled_at?->format('Y-m-d\\TH:i')) }}" min="{{ now()->startOfMinute()->format('Y-m-d\\TH:i') }}" required class="rbj-input">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Notes</label>
                    <textarea name="notes" rows="4" class="rbj-input">{{ old('notes', $booking->notes) }}</textarea>
                </div>
                <div class="flex gap-2">
                    <button class="rbj-btn-primary" type="submit">Update Booking</button>
                    <a href="{{ route('bookings.index') }}" class="rbj-btn-outline inline-flex">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
