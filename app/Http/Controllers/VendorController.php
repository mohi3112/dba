<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LocationService;

class VendorController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function index()
    {
        $vendors = User::with('vendorInfo')->where('designation', User::DESIGNATION_VENDOR)->paginate(10);
        $activeLocations = $this->locationService->getActiveLocations(false);

        return view('vendors.index', compact('vendors', 'activeLocations'));
    }
}
