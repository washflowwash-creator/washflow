<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'unit',
        'low_stock_threshold',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'low_stock_threshold' => 'decimal:2',
        ];
    }

    public function isLowStock(): bool
    {
        return (float) $this->quantity <= (float) $this->low_stock_threshold;
    }
}
