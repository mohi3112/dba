<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        "user_id",
        "business_name",
        "employees",
        "location_id",
    ];

    public function location()
    {
        return $this->hasOne(Location::class, "location_id");
    }
}
