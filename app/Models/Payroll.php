<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'payrollable_id', 'payrollable_type', 'month',
        'basic_salary', 'allowances', 'deductions', 'net_salary',
        'payment_status', 'paid_date',
    ];

    protected function casts(): array
    {
        return [
            'paid_date'    => 'date',
            'basic_salary' => 'float',
            'allowances'   => 'float',
            'deductions'   => 'float',
            'net_salary'   => 'float',
        ];
    }

    public function payrollable()
    {
        return $this->morphTo();
    }
}
