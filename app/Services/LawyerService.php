<?php

namespace App\Services;

use App\Models\User;

class LawyerService
{
    public function getActiveLawyers($onlyActive = true)
    {
        $lawyers = User::whereHas('roles', function ($query) {
            $query->whereIn(
                'id',
                [
                    User::DESIGNATION_PRESIDENT,
                    User::DESIGNATION_VICE_PRESIDENT,
                    User::DESIGNATION_FINANCE_SECRETARY,
                    User::DESIGNATION_SECRETARY,
                    User::DESIGNATION_MANAGER,
                    User::DESIGNATION_LIBRARIAN,
                    User::DESIGNATION_LAWYER
                ]
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
