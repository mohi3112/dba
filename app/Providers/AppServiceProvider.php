<?php

namespace App\Providers;

use App\Models\Family;
use App\Models\User;
use App\Models\ProfileView;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.app', function ($view) {

            $today = Carbon::today();

            $events = User::with(['families' => function ($query) use ($today) {
                $query->where(function ($subQuery) use ($today) {
                    $subQuery->whereMonth('date', $today->month)
                        ->whereDay('date', $today->day)
                        ->whereIn('type', [
                            Family::SON,
                            Family::DAUGHTER
                        ]);
                })->orWhere(function ($subQuery) use ($today) {
                    $subQuery->whereMonth('date', $today->month)
                        ->whereDay('date', $today->day)
                        ->where('type', Family::SPOUSE);
                });
            }])
                ->where(function ($query) use ($today) {
                    $query->whereMonth('dob', $today->month)
                        ->whereDay('dob', $today->day)
                        ->whereIn('designation', User::$lawyersDesignations)
                        ->orWhereHas('families', function ($subQuery) use ($today) {
                            $subQuery->whereMonth('date', $today->month)
                                ->whereDay('date', $today->day)
                                ->whereIn('type', [
                                    Family::SON,
                                    Family::DAUGHTER,
                                    Family::SPOUSE
                                ]);
                        });
                })->get();

            $profileViews = collect();
            $unseenCount = 0;
            if (Auth::check()) {
                $userId = Auth::id();
                $fiveDaysAgo = Carbon::now()->subDays(5)->startOfDay();

                $unseenCount = ProfileView::where('viewed_user_id', $userId)
                    ->where('notification_viewed', false)
                    ->count();

                $profileViews = ProfileView::where('viewed_user_id', $userId)
                    ->where('viewed_at', '>=', $fiveDaysAgo)
                    ->orderByDesc('viewed_at')
                    ->get();
            }
            $view->with(['events' => $events, 'profileViews' => $profileViews, 'unseenCount' => $unseenCount]);
        });
    }
}
