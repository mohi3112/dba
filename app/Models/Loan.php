<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;
    // Mass assignable attributes
    protected $fillable = [
        'employee_id',
        'loan_amount',
        'tenure_months',
        'interest_rate',
        'emi_amount',
        'start_date',
        'end_date',
        'status'
    ];

    // Statuses
    const PENDING_LOAN = "pending";
    const APPROVED_LOAN = "approved";
    const REJECTED_LOAN = "rejected";
    const ACTIVE_LOAN = "active";
    const CLOSED_LOAN = "closed";

    // Loan Statuses
    public static $loanStatuses = [
        self::PENDING_LOAN => 'Pending',
        self::APPROVED_LOAN => 'Approved',
        self::REJECTED_LOAN => 'Rejected',
        self::ACTIVE_LOAN => 'Active',
        self::CLOSED_LOAN => 'Closed',
    ];

    // Relationship with the Employee model
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Relationship with the LoanRepayment model
    public function repayments()
    {
        return $this->hasMany(LoanRepayment::class);
    }
}
