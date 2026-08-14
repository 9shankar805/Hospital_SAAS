<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'department_id',
        'specialization', 'consulting_fee', 'bio', 'avatar', 'status',
    ];

    protected function casts(): array
    {
        return [
            'consulting_fee' => 'float',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function reviews()
    {
        return $this->hasMany(DoctorReview::class);
    }

    public function leaves()
    {
        return $this->morphMany(Leave::class, 'leavable');
    }

    public function attendance()
    {
        return $this->morphMany(Attendance::class, 'attendable');
    }

    public function payrolls()
    {
        return $this->morphMany(Payroll::class, 'payrollable');
    }

    public function labReports()
    {
        return $this->hasMany(LabReport::class);
    }

    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
}
