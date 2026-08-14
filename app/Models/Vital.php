<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vital extends Model
{
    protected $fillable = [
        'patient_id', 'heart_rate', 'blood_pressure', 'oxygen_level',
        'weight', 'height', 'temperature', 'bmi', 'pulse', 'status', 'logged_at',
    ];

    protected function casts(): array
    {
        return [
            'logged_at'    => 'datetime',
            'heart_rate'   => 'float',
            'oxygen_level' => 'float',
            'weight'       => 'float',
            'height'       => 'float',
            'temperature'  => 'float',
            'bmi'          => 'float',
            'pulse'        => 'float',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
