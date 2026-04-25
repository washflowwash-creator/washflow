<?php

namespace App\Http\Controllers;

use App\Models\ServiceRate;
use Illuminate\Http\Request;

class ServiceRateController extends Controller
{
    private const DEFAULT_SERVICE_TYPES = [
        'Wash • Dry • Fold (Minimum 8 kg)',
        'Wash Only (Minimum 8 kg)',
        'Dry Only (Minimum 8 kg)',
        'Heavy Items (Min. 5 kg)',
        'Comforter (1 pc per load)',
    ];

    public function index()
    {
        foreach (self::DEFAULT_SERVICE_TYPES as $serviceType) {
            ServiceRate::query()->firstOrCreate(
                ['service_type' => $serviceType],
                ['price_per_kg' => 0]
            );
        }

        $rates = ServiceRate::query()
            ->whereIn('service_type', self::DEFAULT_SERVICE_TYPES)
            ->get()
            ->sortBy(fn (ServiceRate $rate): int => array_search($rate->service_type, self::DEFAULT_SERVICE_TYPES, true))
            ->values();

        return view('service-rates.index', compact('rates'));
    }

    public function update(Request $request, ServiceRate $serviceRate)
    {
        $validated = $request->validate([
            'price_per_kg' => ['required', 'numeric', 'min:0'],
        ]);

        $serviceRate->update($validated);

        return redirect()->route('service-rates.index')->with('success', 'Service rate updated.');
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'rates' => ['required', 'array'],
            'rates.*' => ['required', 'numeric', 'min:0'],
        ]);

        foreach ($validated['rates'] as $rateId => $price) {
            ServiceRate::query()->where('id', $rateId)->update(['price_per_kg' => $price]);
        }

        return redirect()->route('service-rates.index')->with('success', 'All service rates updated successfully.');
    }
}
