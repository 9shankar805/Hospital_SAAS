<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number', 'supplier_id', 'medicine_id',
        'quantity', 'unit_price', 'total_amount',
        'status', 'order_date', 'expected_delivery',
    ];

    protected function casts(): array
    {
        return [
            'order_date'        => 'date',
            'expected_delivery' => 'date',
            'unit_price'        => 'float',
            'total_amount'      => 'float',
        ];
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
