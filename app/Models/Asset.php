<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'name', 'category', 'serial_number',
        'purchase_date', 'purchase_price', 'location', 'status',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date'  => 'date',
            'purchase_price' => 'float',
        ];
    }
}
