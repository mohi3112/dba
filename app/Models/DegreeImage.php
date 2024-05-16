<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DegreeImage extends Model
{
    protected $fillable = ['user_id', 'image'];

    // Define relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
