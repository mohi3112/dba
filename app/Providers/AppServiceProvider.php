<?php

namespace App\Providers;

use App\Models\Family;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
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

            $view->with('events', $events);
        });
    }
}
