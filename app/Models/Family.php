<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{

    protected $fillable = ['user_id', 'type', 'name', 'date'];

    public static $familyRelations = [
        // 'father' => 'Father',
        // 'mother' => 'Mother',
        'spouse' => 'Spouse',
        'son' => 'Son',
        'daughter' => 'Daughter',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
