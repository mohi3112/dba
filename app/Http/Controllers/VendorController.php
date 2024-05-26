<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
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
        $vendors = Vendor::paginate(10);
        $activeLocations = $this->locationService->getActiveLocations(false);

        return view('vendors.index', compact('vendors', 'activeLocations'));
    }

    public function create()
    {
        $activeLocations = $this->locationService->getActiveLocations();
        return view('vendors.create', compact('activeLocations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'father_first_name' => 'required',
            'mobile' => 'required|numeric',
            'business_name' => 'required',
            'employees' => 'required',
            'location_id' => 'required',
        ]);

        if (!$request->has('status')) {
            $request->merge(['status' => false]);
        }

        Vendor::create($request->all());

        return redirect()->route('vendors')->with('success', 'Vendor created successfully.');
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        $activeLocations = $this->locationService->getActiveLocations();

        return view('vendors.edit', compact('vendor', 'activeLocations'));
    }

    public function show($id)
    {
        $Vendor = Vendor::findOrFail($id);

        return view('vendors.show', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'father_first_name' => 'required',
            'mobile' => 'required|numeric',
            'business_name' => 'required',
            'employees' => 'required',
            'location_id' => 'required',
        ]);

        if (!$request->has('status') || $request->status == false) {
            $request->merge(['status' => false]);
        } else {
            $request->merge(['status' => true]);
        }

        $vendor = Vendor::findOrFail($id);

        $vendor->first_name = $request->first_name;
        $vendor->last_name = $request->last_name;
        $vendor->father_first_name = $request->father_first_name;
        $vendor->father_last_name = $request->father_last_name;
        $vendor->gender = $request->gender;
        $vendor->dob = $request->dob;
        $vendor->mobile = $request->mobile;
        $vendor->residence_address = $request->residence_address;
        $vendor->business_name = $request->business_name;
        $vendor->employees = $request->employees;
        $vendor->location_id = $request->location_id;
        $vendor->status = $request->status;
        $vendor->save();

        return redirect()->route('vendors')->with('success', 'Vendor record updated successfully.');
    }

    public function destroy($id)
    {
        // Find the user by ID
        $vendor = Vendor::findOrFail($id);

        // Set the deleted_by field with the authenticated vendor's ID
        $vendor->deleted_by = Auth::id();
        $vendor->save(); // Save the $vendor to update the deleted_by field

        // Soft delete the $vendor
        $vendor->delete();

        return redirect()->route('vendors')->with('success', 'Vendor deleted successfully!');
    }
}
