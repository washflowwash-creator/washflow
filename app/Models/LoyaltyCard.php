<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stamps',
        'first_stamp_at',
        'expires_at',
        'reward_generated',
        'reward_redeemed_at',
        'reward_code',
    ];

    protected $casts = [
        'first_stamp_at' => 'datetime',
        'expires_at' => 'datetime',
        'reward_redeemed_at' => 'datetime',
        'reward_generated' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
