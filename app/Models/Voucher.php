<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "title",
        "price",
        "date",
        "issued_by",
        "issued_to",
        "description",
        "deleted_by"
    ];
}
