<?php

namespace App\Services;

use App\Models\User;

class LawyerService
{
    public function getActiveLawyers($onlyActive = true)
    {
        if (auth()->user()->hasRole('lawyer')) {
            $roles = [User::DESIGNATION_LAWYER];
        } elseif (auth()->user()->hasRole('librarian')) {
            $roles = [User::DESIGNATION_LIBRARIAN];
        } else {
            $roles = User::$lawyersDesignations;
        }
        $lawyers = User::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn(
                'id',
                $roles
            );
        });

        if ($onlyActive) {
            $all_lawyers = $lawyers->get();
        } else {
            $all_lawyers = $lawyers->withTrashed()->get();
        }

        return $all_lawyers->mapWithKeys(function ($user) {
            return [$user->id => $user->full_name];
        })->toArray();
    }
}
