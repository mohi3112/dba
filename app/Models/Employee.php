<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'gender', 'email', 'phone', 'position', 'salary', 'deleted_by'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
