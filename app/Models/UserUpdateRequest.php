<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        "change_type",
        "changes_requested_by",
        "approved_by_secretry",
        "approved_by_president",
        "deleted_by",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
