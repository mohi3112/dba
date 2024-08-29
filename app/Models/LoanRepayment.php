<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanRepayment extends Model
{
    use SoftDeletes;
    // Mass assignable attributes
    protected $fillable = [
        'loan_id',
        'payment_date',
        'amount_paid',
        'balance_due'
    ];

    // Relationship with the Loan model
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
