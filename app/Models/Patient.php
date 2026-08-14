<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_code', 'name', 'email', 'phone',
        'age', 'gender', 'blood_group', 'address', 'avatar',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function vitals()
    {
        return $this->hasMany(Vital::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function labReports()
    {
        return $this->hasMany(LabReport::class);
    }

    public function insuranceClaims()
    {
        return $this->hasMany(InsuranceClaim::class);
    }

    public function clinicalNotes()
    {
        return $this->hasMany(ClinicalNote::class);
    }

    public function reviews()
    {
        return $this->hasMany(DoctorReview::class);
    }

    public function notifications()
    {
        return $this->morphMany(NotificationLog::class, 'notifiable');
    }

    public function latestVital()
    {
        return $this->hasOne(Vital::class)->latestOfMany('logged_at');
    }
}
