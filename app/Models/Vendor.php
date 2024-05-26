<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "first_name",
        "last_name",
        "father_first_name",
        "father_last_name",
        "gender",
        "dob",
        "mobile",
        "residence_address",
        "business_name",
        "employees",
        "location_id",
        "status",
        "deleted_by"
    ];

    // Define static array for gender
    public static $genders = [
        1 => 'Male',
        2 => 'Female',
        3 => 'Other'
    ];

    public static $statuses = [
        true => 'Active',
        false => 'In-active'
    ];


    public function location()
    {
        return $this->hasOne(Location::class, "location_id");
    }

    public function getFullNameAttribute()
    {
        $last_name = $this->last_name ? ' ' . $this->last_name : '';
        return "{$this->first_name}{$last_name}";
    }

    public function getFullFatherNameAttribute()
    {
        $father_last_name = $this->father_last_name ? ' ' . $this->father_last_name : '';
        return "{$this->father_first_name}{$father_last_name}";
    }
}
