<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddressProof extends Model
{
    protected $fillable = ['user_id', 'filename'];

    // Define relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
