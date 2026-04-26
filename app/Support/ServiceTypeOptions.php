<?php

namespace App\Support;

use App\Models\ServiceRate;

class ServiceTypeOptions
{
    private const FALLBACK = [
        'Wash • Dry • Fold (Minimum 8 kg)',
        'Wash Only (Minimum 8 kg)',
        'Dry Only (Minimum 8 kg)',
        'Heavy Items (Min. 5 kg)',
        'Comforter (1 pc per load)',
    ];

    /**
     * @return array<int, string>
     */
    public static function all(): array
    {
        $serviceTypes = ServiceRate::query()
            ->orderBy('service_type')
            ->pluck('service_type')
            ->filter()
            ->values()
            ->all();

        return $serviceTypes !== [] ? $serviceTypes : self::FALLBACK;
    }
}
