<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Vakalatnama extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "user_id",
        "unique_id",
        "deleted_by"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateUniqueId()
    {
        $latestUniqueId = DB::table('vakalatnamas')->latest('created_at')->value('unique_id');

        if ($latestUniqueId) {
            // Extract the serial number part
            $serialNumber = (int) substr($latestUniqueId, 0, 3);

            // Increment the serial number
            $newSerialNumber = str_pad($serialNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            // If there's no record, start with 001
            $newSerialNumber = '001';
        }

        // Generate a random number
        $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Get the current month and year
        $currentDate = now()->format('m') . now()->format('y');

        // Combine them to form the new unique_id
        return $newSerialNumber . '-' . $randomNumber . '-' . $currentDate;
    }
}
