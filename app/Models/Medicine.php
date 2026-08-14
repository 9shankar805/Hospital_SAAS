<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name', 'generic_name', 'category', 'unit',
        'price', 'stock', 'reorder_level',
        'expiry_date', 'batch_no', 'status',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'date',
            'price'       => 'float',
        ];
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function stockTransfers()
    {
        return $this->hasMany(StockTransfer::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->reorder_level;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }
}
