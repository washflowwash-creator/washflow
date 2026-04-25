<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-sky-900">Create Order</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('orders.store') }}" class="rbj-card space-y-4 p-6">
                @csrf
                <div>
                    <label class="text-sm font-medium text-slate-700">Booking</label>
                    <select id="booking_id" name="booking_id" required class="rbj-input">
                        <option value="">Select booking</option>
                        @foreach ($bookings as $booking)
                            <option
                                value="{{ $booking->id }}"
                                data-service-type="{{ $booking->service_type }}"
                                data-unit-price="{{ (float) ($serviceRateMap[$booking->service_type] ?? 0) }}"
                                @selected((int) old('booking_id', $selectedBookingId ?? 0) === (int) $booking->id)
                            >
                                #{{ $booking->id }} - {{ $booking->user->name ?? 'Customer' }} - {{ $booking->service_type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-slate-700">Weight (kg)</label>
                        <input type="number" step="0.01" min="0" name="weight_kg" value="{{ old('weight_kg', 0) }}" class="rbj-input">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700">Unit Price</label>
                        <input id="unit_price" type="number" step="0.01" min="0" name="unit_price" value="{{ old('unit_price', 0) }}" required class="rbj-input">
                    </div>
                </div>
                <div>
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
                                    value="{{ old('inventory_items.'.$item->id, 0) }}"
                                    class="rbj-input col-span-5 !mt-0"
                                >
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="rbj-btn-primary" type="submit">Save Order</button>
                    <a href="{{ route('orders.index') }}" class="rbj-btn-outline inline-flex">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bookingSelect = document.getElementById('booking_id');
            const unitPriceInput = document.getElementById('unit_price');

            function syncRateFromBooking() {
                const selected = bookingSelect.options[bookingSelect.selectedIndex];
                if (!selected) {
                    return;
                }

                const unitPrice = selected.getAttribute('data-unit-price');
                if (unitPrice !== null && unitPrice !== '' && Number(unitPrice) > 0) {
                    unitPriceInput.value = Number(unitPrice).toFixed(2);
                }
            }

            bookingSelect.addEventListener('change', syncRateFromBooking);
            syncRateFromBooking();
        });
    </script>
</x-app-layout>
