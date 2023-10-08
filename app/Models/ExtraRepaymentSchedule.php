<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraRepaymentSchedule extends Model
{
    protected $fillable = [
        'loan_id',
        'month_number',
        'starting_balance',
        'monthly_payment',
        'principal_component',
        'interest_component',
        'extra_repayment_made',
        'ending_balance',
        'remaining_loan_term',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}