<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "shop_number",
        "floor_number",
        "complex",
        "rent",
        "deleted_by"
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function inProgressReviewRequests()
    {
        return $this->hasOne(ModificationRequest::class, 'record_id')->where('table_name', 'locations')->where('approved_by_secretary', true)->whereNull('approved_by_president');
    }

    public function getFullLocationNameAttribute()
    {
        $complex = $this->complex ? ', ' . $this->complex : '';
        $floor_number = $this->floor_number ? ', ' . $this->floor_number : '';
        return "{$this->shop_number}{$floor_number}{$complex}";
    }
}
