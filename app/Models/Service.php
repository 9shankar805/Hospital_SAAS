<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'fee', 'status'];

    protected function casts(): array
    {
        return [
            'fee' => 'float',
        ];
    }
}
