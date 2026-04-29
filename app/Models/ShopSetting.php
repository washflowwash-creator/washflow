<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'capacity',
        'processing_capacity',
        'current_active_loads',
        'nearly_full_threshold',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'status' => 'OPEN',
            'capacity' => 30,
            'processing_capacity' => 5,
            'current_active_loads' => 0,
            'nearly_full_threshold' => 80,
        ]);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'OPEN' && $this->current_active_loads < $this->capacity;
    }
}
