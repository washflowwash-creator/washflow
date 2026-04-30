<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_type',
        'price_per_kg',
    ];

    protected $casts = [
        'price_per_kg' => 'decimal:2',
    ];
}
