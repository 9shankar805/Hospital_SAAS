<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    protected $fillable = [
        'medicine_id', 'from_location', 'to_location',
        'quantity', 'status', 'transferred_at',
    ];

    protected function casts(): array
    {
        return [
            'transferred_at' => 'datetime',
        ];
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
