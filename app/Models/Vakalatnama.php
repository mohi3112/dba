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
        "bulk_issue",
        "number_of_issue_vakalatnamas",
        "last_unique_id",
        "deleted_by"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateUniqueId($latestUniqueId = '', $numberOfvakalatnamaIssue = null)
    {
        if ($latestUniqueId == '') {
            $latestUniqueIdRecord = DB::table('vakalatnamas')->latest('created_at')->first();

            $latestUniqueId = ($latestUniqueIdRecord) ? $latestUniqueIdRecord->unique_id : null;
            if ($latestUniqueIdRecord && $latestUniqueIdRecord->bulk_issue == 1) {
                $latestUniqueId = $latestUniqueIdRecord->last_unique_id;
            }
        }

        if ($latestUniqueId) {
            // Extract the serial number part
            $serialNumber = (int) substr($latestUniqueId, 0, 6);

            $lastNumber = 1;
            // Increment the serial number
            if ($numberOfvakalatnamaIssue != null) {
                $lastNumber = (int) ($numberOfvakalatnamaIssue > 1) ? $numberOfvakalatnamaIssue - 1 : 1;
            }

            $newSerialNumber = str_pad($serialNumber + $lastNumber, 6, '0', STR_PAD_LEFT);
        } else {
            // If there's no record, start with 000001
            $newSerialNumber = '000001';
        }

        // Generate a random number
        $randomNumber = str_pad(rand(0, 9999), 3, '0', STR_PAD_LEFT);

        // Get the current month and year
        $currentDate = now()->format('m') . now()->format('y');

        // Combine them to form the new unique_id
        return $newSerialNumber . '-' . $randomNumber . '-' . $currentDate;
    }
}
