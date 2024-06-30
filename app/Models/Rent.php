<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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

    public static function getLatestExpiredRentsForAllUsers($request = null)
    {
        $next15Days = Carbon::now()->addDays(15);

        $subQuery = Rent::select('user_id', DB::raw('MAX(end_date) as latest_end_date'))
            ->groupBy('user_id');

        $rentsQuery = Rent::joinSub($subQuery, 'latest_rents', function ($join) use ($next15Days) {
            $join->on('rents.user_id', '=', 'latest_rents.user_id')
                ->on('rents.end_date', '=', 'latest_rents.latest_end_date')
                ->where('latest_rents.latest_end_date', '<', $next15Days);
        });

        if ($request->filled('userId')) {
            $rentsQuery->where('rents.user_id', $request->userId);
        }

        if ($request->filled('rentAmount')) {
            $rentsQuery->where('rents.rent_amount', $request->rentAmount);
        }

        if ($request->filled('renewalDate')) {
            $rentsQuery->where('rents.renewal_date', $request->renewalDate);
        }

        if ($request->filled('endDate')) {
            $rentsQuery->where('rents.end_date', $request->endDate);
        }

        return $rentsQuery->addSelect('rents.*')
            ->orderBy('rents.end_date')
            ->paginate(10);
    }
}
