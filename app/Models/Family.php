<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{

    protected $fillable = ['user_id', 'type', 'name', 'date'];

    const FATHER = 'father';
    const MOTHER = 'mother';
    const SPOUSE = 'spouse';
    const SON = 'son';
    const DAUGHTER = 'daughter';
    const OTHER = 'other';

    public static $familyRelations = [
        self::FATHER => 'Father',
        self::MOTHER => 'Mother',
        self::SPOUSE => 'Spouse',
        self::SON => 'Son',
        self::DAUGHTER => 'Daughter',
        self::OTHER => 'Other',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
