<?php

namespace App\Services;

use App\Models\User;

class LawyerService
{
    public function getActiveLawyers()
    {
        $all_lawyers = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->statusActive()->get();

        return $all_lawyers->mapWithKeys(function ($user) {
            return [$user->id => $user->full_name];
        })->toArray();
    }
}
