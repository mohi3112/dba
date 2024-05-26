<?php

namespace App\Services;

use App\Models\User;

class LawyerService
{
    public function getActiveLawyers($onlyActive = true)
    {
        $lawyers = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
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
