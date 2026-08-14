<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'staff_code', 'name', 'email', 'phone', 'role',
        'department_id', 'designation', 'avatar', 'salary',
        'joining_date', 'status',
    ];

    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
            'salary'       => 'float',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
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
}
