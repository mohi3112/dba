<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id', 'payment_amount', 'payment_date', 'payment_proof', 'deleted_by'];

    // Define relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
