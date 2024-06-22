<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'rent_amount',
        'renewal_date',
        'end_date',
        'deleted_by'
    ];
}
