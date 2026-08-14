<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $table = 'income';

    protected $fillable = [
        'source', 'amount', 'income_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'income_date' => 'date',
            'amount'      => 'float',
        ];
    }
}
