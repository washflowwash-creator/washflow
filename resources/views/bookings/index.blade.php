<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-sky-900">My Bookings</h2>
            <a href="{{ route('bookings.create') }}" class="rbj-btn-primary">New Booking</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-800">{{ session('success') }}</div>
            @endif

            <div class="rbj-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-sky-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3">Service</th>
                                <th class="px-4 py-3">Schedule</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                @php
                                    $badge = match ($booking->status) {
                                        'pending' => 'rbj-badge rbj-badge-pending',
                                        'washing', 'drying', 'picked-up' => 'rbj-badge rbj-badge-washing',
                                        'ready' => 'rbj-badge rbj-badge-ready',
                                        'completed' => 'rbj-badge rbj-badge-completed',
                                        default => 'rbj-badge bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <tr class="border-t border-sky-100">
                                    <td class="px-4 py-3 font-medium text-slate-700">{{ $booking->service_type }}</td>
                                    <td class="px-4 py-3">{{ $booking->scheduled_at?->format('M d, Y h:i A') }}</td>
                                    <td class="px-4 py-3"><span class="{{ $badge }}">{{ $booking->status }}</span></td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('bookings.show', $booking) }}" class="rbj-btn-outline !px-3 !py-1.5">View</a>
                                            @if ($booking->status === 'pending')
                                                <a href="{{ route('bookings.edit', $booking) }}" class="rbj-btn-outline !px-3 !py-1.5">Edit</a>
                                                <form method="POST" action="{{ route('bookings.destroy', $booking) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-2xl border border-rose-300 bg-rose-50 px-3 py-1.5 text-sm font-semibold text-rose-700">Cancel</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">No bookings yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">{{ $bookings->links() }}</div>
        </div>
    </div>
</x-app-layout>
