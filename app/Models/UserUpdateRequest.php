<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class UserUpdateRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "user_id",
        "first_name",
        "middle_name",
        "last_name",
        "email",
        "father_first_name",
        "father_last_name",
        "dob",
        "gender",
        "mobile1",
        "mobile2",
        "aadhaar_no",
        "picture",
        "designation",
        "degrees",
        "address",
        "status",
        "chamber_number",
        "is_deceased",
        "is_physically_disabled",
        "other_details",
        "business_name",
        "employees",
        "location_id",
        "change_type",
        "changes_requested_by",
        "approved_by_secretary",
        "approved_by_president",
        "deleted_by",
    ];

    const CHANGE_TYPE_EDIT = 1;
    const CHANGE_TYPE_DELETE = 2;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute()
    {
        $middleName = $this->middle_name ? ' ' . $this->middle_name : '';
        $lastName = $this->last_name ? ' ' . $this->last_name : '';
        return "{$this->first_name}{$middleName} {$lastName}";
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['dob'])->age;
    }

    public static function getAgeOperator($key)
    {
        switch ($key) {
            case 'eq':
                return '=';
            case 'gt':
                return '>';
            case 'lt':
                return '<';
            default:
                return '=';
        }
    }

    public function scopeAge(Builder $query, $age, $operatorKey = 'eq')
    {
        $operator = UserUpdateRequest::getAgeOperator($operatorKey);
        return $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) $operator ?", [$age]);
    }
}
