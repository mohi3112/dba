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
        "image",
        "issued_by",
        "issued_to",
        "description",
        "deleted_by"
    ];

    public static function generateVoucherSerialNumber($created_at, $record_id) {
        // Concatenate created_at timestamp and record_id
        $string = $created_at . $record_id;
    
        // Generate a hash and convert it to a number
        $hash = crc32($string); 
    
        // Keep it within 4-6 digits
        return str_pad($hash % 1000000, 6, '0', STR_PAD_LEFT);
    }
}
