<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;

    protected $fillable = ['employee_id', 'date', 'check_in', 'check_out', 'deleted_by'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
