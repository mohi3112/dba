<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['user_id', 'payment_amount', 'payment_date', 'expiry_date', 'payment_proof'];

    // Define relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
