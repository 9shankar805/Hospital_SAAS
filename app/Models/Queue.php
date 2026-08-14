<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'token_number', 'patient_id', 'doctor_id',
        'queue_date', 'status', 'priority', 'notes',
        'called_at', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'queue_date'   => 'date',
            'called_at'    => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
