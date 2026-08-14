<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceClaim extends Model
{
    protected $fillable = [
        'patient_id', 'appointment_id',
        'insurer_name', 'claim_number', 'amount', 'status',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'float',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
