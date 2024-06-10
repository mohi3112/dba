<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'subscription_type', 'start_date', 'end_date', 'deleted_by'];

    public static $subscriptionTypes = [
        1 => 'Full Membership',
        2 => 'Associate Membership',
        3 => 'Affiliate Membership',
        4 => 'Senior Membership',
        5 => 'Corporate Membership',
        6 => 'Student Membership',
        7 => 'Retired Membership',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
