<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\ModificationRequest;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentController extends Controller
{
    public function index()
    {
        $rentsQuery = Rent::query();

        $rents = $rentsQuery->orderBy('created_at', 'desc')->paginate(10);

        $activeVendors = $this->getActiveVendorsList();

        return view('rents.index', compact('rents', 'activeVendors'));
    }

    public function create()
    {
        $activeVendors = $this->getActiveVendorsList();
        return view('rents.create', compact('activeVendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'rent_amount' => 'required',
            'renewal_date' => 'required'
        ]);

        Rent::create($request->all());

        return redirect()->route('rents')->with('success', 'Rent record added successfully.');
    }

    public function edit($id)
    {
        $rent = Rent::findOrFail($id);

        $activeVendors = $this->getActiveVendorsList();

        return view('rents.edit', compact('rent', 'activeVendors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'rent_amount' => 'required',
            'renewal_date' => 'required'
        ]);

        $rent = Rent::findOrFail($id);

        if ($rent) {
            if (auth()->user()->hasRole('president')) {

                $rent->user_id = $request->user_id;
                $rent->rent_amount = $request->rent_amount;
                $rent->renewal_date = $request->renewal_date;
                $rent->end_date = $request->end_date;
                $rent->save();

                return redirect()->route('rents')->with('success', 'Rent updated successfully.');
            } else {
                $changes = $request->except(['_token', '_method']);
                $this->submitChangeRequest([
                    "table_name" => 'rents',
                    "record_id" => $rent->id,
                    "changes" => $changes,
                    "action" => ModificationRequest::REQUEST_TYPE_UPDATE,
                    "requested_by" => Auth::id(),
                ]);

                return redirect()->route('rents')->with('success', 'Rent updated request submitted successfully.');
            }
        }

        return redirect()->route('rents')->with('error', 'Something went wrong.');
    }


    public function destroy($id)
    {
        // Find the payment by ID
        $rent = Rent::findOrFail($id);

        if ($rent) {
            if (auth()->user()->hasRole('president')) {
                $rent->deleted_by = Auth::id();
                $rent->save();

                // Soft delete the rent
                $rent->delete();

                return redirect()->route('rents')->with('success', 'Rent record deleted successfully!');
            } else {
                $this->submitChangeRequest([
                    "table_name" => 'rents',
                    "record_id" => $rent->id,
                    "action" => ModificationRequest::REQUEST_TYPE_DELETE,
                    "requested_by" => Auth::id(),
                ]);
                return redirect()->route('rents')->with('success', 'Rent record delete request submitted successfully!');
            }
        }

        return redirect()->route('rents')->with('error', 'Something went wrong.');
    }

    public function getRent($locationId)
    {
        $location = Location::find($locationId);

        if (!$location) {
            return response()->json(['error' => 'Location not found'], 404);
        }

        $rent = $location->rent;

        return response()->json(['rent' => $rent]);
    }
}
