<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\ModificationRequest;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::with('inProgressReviewRequests')->paginate(10);

        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_number' => 'required',
            'floor_number' => 'required',
            'rent' => 'required|numeric',
        ]);

        Location::create($request->all());

        return redirect()->route('locations')->with('success', 'Location created successfully.');
    }

    public function edit($id)
    {
        $location = Location::findOrFail($id);

        return view('locations.edit', compact('location'));
    }

    public function show($id)
    {
        $location = Location::findOrFail($id);

        return view('locations.show', compact('location'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'shop_number' => 'required',
            'floor_number' => 'required',
            'rent' => 'required|numeric',
        ]);

        $location = Location::findOrFail($id);

        if ($location) {
            if (auth()->user()->hasRole('president')) {
                $location->shop_number = $request->shop_number;
                $location->floor_number = $request->floor_number;
                $location->complex = $request->complex;
                $location->rent = $request->rent;
                $location->save();

                return redirect()->route('locations')->with('success', 'Location record updated successfully.');
            } else {
                $this->submitChangeRequest([
                    "table_name" => 'locations',
                    "record_id" => $location->id,
                    "changes" => $request->all(),
                    "action" => ModificationRequest::REQUEST_TYPE_UPDATE,
                    "requested_by" => Auth::id(),
                ]);
            }
        }

        return redirect()->route('locations')->with('success', 'Location record updated request submitted successfully.');
    }

    public function destroy($id)
    {
        // Find the user by ID
        $location = Location::findOrFail($id);

        if ($location) {
            if (auth()->user()->hasRole('president')) {
                // Set the deleted_by field with the authenticated location's ID
                $location->deleted_by = Auth::id();
                $location->save(); // Save the $location to update the deleted_by field

                // Soft delete the $location
                $location->delete();
                return redirect()->route('locations')->with('success', 'Location deleted successfully!');
            } else {
                $this->submitChangeRequest([
                    "table_name" => 'locations',
                    "record_id" => $location->id,
                    "action" => ModificationRequest::REQUEST_TYPE_DELETE,
                    "requested_by" => Auth::id(),
                ]);
            }
        }

        return redirect()->route('locations')->with('success', 'Location deleted request submitted successfully!');
    }
}
