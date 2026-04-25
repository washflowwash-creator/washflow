<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-sky-900">Service Rates (Per Pack)</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-800">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('service-rates.bulk-update') }}" class="space-y-4">
                @csrf
                <div class="rbj-card overflow-hidden">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-sky-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3">Service</th>
                                <th class="px-4 py-3">Price Per Pack</th>
                                <th class="px-4 py-3">Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rates as $rate)
                                <tr class="border-t border-sky-100">
                                    <td class="px-4 py-3 font-medium text-slate-700">{{ $rate->service_type }}</td>
                                    <td class="px-4 py-3">
                                        <input type="number" step="0.01" min="0" name="rates[{{ $rate->id }}]" value="{{ $rate->price_per_kg }}" class="rbj-input !mt-0 w-36">
                                    </td>
                                    <td class="px-4 py-3 text-slate-500">Applies to new orders by default</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="rbj-btn-primary">Save All Changes</button>
                    <a href="{{ route('dashboard') }}" class="rbj-btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
