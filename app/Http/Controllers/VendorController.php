<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LocationService;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function index(Request $request)
    {
        $vendorsQuery = User::with('vendorInfo')->where('designation', User::DESIGNATION_VENDOR);

        if ($request->filled('name')) {
            $vendorsQuery->where('first_name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('l_name')) {
            $vendorsQuery->where('last_name', 'like', '%' . $request->l_name . '%');
        }

        if (count($_GET) > 0 && !$request->filled('is_active')) {
            $vendorsQuery->where('status', User::STATUS_IN_ACTIVE);
        } else {
            $vendorsQuery->statusActive();
        }

        if ($request->filled('gender')) {
            $vendorsQuery->where('gender', $request->gender);
        }

        if ($request->filled('is_deceased')) {
            $vendorsQuery->where('is_deceased', true);
        }

        if ($request->filled('is_physically_disabled')) {
            $vendorsQuery->where('is_physically_disabled', true);
        }

        $vendors = $vendorsQuery->orderBy('created_at', 'desc')->paginate(10);
        $activeLocations = $this->locationService->getActiveLocations(false);

        return view('vendors.index', compact('vendors', 'activeLocations'));
    }
}
