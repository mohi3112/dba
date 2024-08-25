<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'gender', 'dob', 'email', 'phone', 'position', 'salary', 'esi_number', 'esi_start_date', 'esi_end_date', 'esi_contribution', 'bank_account_number', 'bank_ifsc_code', 'account_holder_name', 'branch_name', 'policies', 'deleted_by'];

    public static $employeesGender = [
        1 => 'Male',
        2 => 'Female',
        3 => 'Other'
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
