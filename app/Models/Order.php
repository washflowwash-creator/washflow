<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending',
        'picked-up',
        'washing',
        'drying',
        'ready',
        'completed',
    ];

    protected $fillable = [
        'booking_id',
        'user_id',
        'status',
        'weight_kg',
        'unit_price',
        'total_cost',
        'inventory_deduction_json',
    ];

    protected function casts(): array
    {
        return [
            'weight_kg' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'total_cost' => 'decimal:2',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }
}
