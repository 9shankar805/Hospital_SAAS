<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'attendable_id', 'attendable_type',
        'date', 'check_in', 'check_out', 'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function attendable()
    {
        return $this->morphTo();
    }
}
