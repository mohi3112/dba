<?php

namespace App\Services;

use App\Models\Location;

class LocationService
{
    public function getActiveLocations($onlyActive = true)
    {
        $locations = Location::query();

        if ($onlyActive) {
            $all_locations = $locations->get();
        } else {
            $all_locations = $locations->withTrashed()->get();
        }

        return $all_locations->mapWithKeys(function ($location) {
            return [$location->id => $location->full_location_name];
        })->toArray();
    }
}
