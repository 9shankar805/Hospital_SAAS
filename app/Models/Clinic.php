<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    protected $fillable = [
        'name', 'subdomain', 'admin_email',
        'phone', 'plan_id', 'status', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
        ];
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
