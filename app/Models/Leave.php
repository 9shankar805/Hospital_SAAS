<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'leavable_id', 'leavable_type', 'leave_type_id',
        'from_date', 'to_date', 'days', 'reason', 'status',
    ];

    protected function casts(): array
    {
        return [
            'from_date' => 'date',
            'to_date'   => 'date',
        ];
    }

    public function leavable()
    {
        return $this->morphTo();
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }
}
