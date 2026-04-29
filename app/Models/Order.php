<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;
use App\Models\ShopSetting;

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

    /**
     * Estimate completion datetime based on service, weight, queue and shop capacity.
     */
    public function estimateCompletion(): Carbon
    {
        $shop = ShopSetting::current();

        $service = $this->booking?->service_type ?? null;
        $weight = (float) ($this->weight_kg ?? 0);

        // base hours per kg for common services
        $baseHoursPerKgMap = [
            'Wash • Dry • Fold (Minimum 8 kg)' => 3.0, // 8kg -> 24h
            'Wash Only (Minimum 8 kg)' => 2.0,
            'Dry Only (Minimum 8 kg)' => 1.0,
            'Heavy Items (Min. 5 kg)' => 6.0,
            'Comforter (1 pc per load)' => 48.0 / max($weight, 1), // comforter handled as fixed load
        ];

        $hoursPerKg = $baseHoursPerKgMap[$service] ?? 3.0;

        // processing time for this order
        $processingHours = $hoursPerKg * max($weight, 1);

        // approximate wait time based on current active loads and processing_capacity
        $daysQueue = $shop->processing_capacity > 0 ? ($shop->current_active_loads / $shop->processing_capacity) : 0;
        $waitHours = max(0, $daysQueue * 24);

        // determine start time (use scheduled_at if future)
        $start = Carbon::now();
        if ($this->scheduled_at && Carbon::parse($this->scheduled_at)->greaterThan($start)) {
            $start = Carbon::parse($this->scheduled_at);
        }

        $estimated = $start->copy()->addHours($waitHours + $processingHours);

        return $estimated;
    }
}
