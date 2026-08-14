<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'price_monthly', 'price_yearly',
        'doctor_limit', 'patient_limit', 'features_json', 'status',
    ];

    protected function casts(): array
    {
        return [
            'features_json' => 'array',
            'price_monthly' => 'float',
            'price_yearly'  => 'float',
        ];
    }

    public function clinics()
    {
        return $this->hasMany(Clinic::class);
    }
}
