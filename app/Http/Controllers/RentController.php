<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\ModificationRequest;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Cast\Object_;
use setasign\Fpdi\Fpdi;

class RentController extends Controller
{
    public function index(Request $request)
    {
        $rentsQuery = Rent::query();

        if ($request->filled('userId')) {
            $rentsQuery->where('user_id', $request->userId);
        }

        if ($request->filled('rentAmount')) {
            $rentsQuery->where('rent_amount', $request->rentAmount);
        }

        if ($request->filled('renewalDate')) {
            $rentsQuery->where('renewal_date', $request->renewalDate);
        }

        if ($request->filled('endDate')) {
            $rentsQuery->where('end_date', $request->endDate);
        }

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

    public function pendingRents(Request $request)
    {
        $expiredRents = Rent::getLatestExpiredRentsForAllUsers($request);

        $activeVendors = $this->getActiveVendorsList();

        return view('rents.pending-rents', compact('expiredRents', 'activeVendors'));
    }

    public function generateReceipt($id)
    {
        // Fetch rent details by ID
        $rent = Rent::findOrFail($id);

        $activeVendors = $this->getActiveVendorsList();

        $location = Location::find($activeVendors[$rent->user_id]['location_id']);

        $request = new Request();
        $request->merge(['userId' => $rent->user_id]);

        $expiredRents = Rent::getLatestExpiredRentsForAllUsers($request)->first();

        $pendingMonths = 0;
        if ($expiredRents->count()) {
            $currentDate = \Carbon\Carbon::now();
            $lastPaidDate = \Carbon\Carbon::parse($expiredRents->end_date);
            $pendingMonths = $lastPaidDate->diffInMonths($currentDate);
        }

        // Create new PDF instance
        $pdf = new Fpdi();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Set the title
        $pdf->SetXY(10, 10);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(190, 10, 'Rent Receipt', 0, 1, 'C');

        // Line Break
        $pdf->Ln(10);

        // Add rent details
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'Vendor:', 0, 0);
        $pdf->Cell(100, 10, (!empty($activeVendors) && $activeVendors[$rent->user_id]) ? $activeVendors[$rent->user_id]['full_name'] : '--', 0, 1);

        $pdf->Cell(50, 10, 'Location:', 0, 0);
        $pdf->Cell(100, 10, ($location) ? $location->fullLocationName : '--', 0, 1);

        $pdf->Cell(50, 10, 'Rent Amount:', 0, 0);
        $pdf->Cell(100, 10, 'Rs. ' . number_format($rent->rent_amount, 2), 0, 1);

        $pdf->Cell(50, 10, 'Renewal Date:', 0, 0);
        $pdf->Cell(100, 10, date('d-m-Y', strtotime($rent->renewal_date)), 0, 1);

        $pdf->Cell(50, 10, 'End Date:', 0, 0);
        $pdf->Cell(100, 10, date('d-m-Y', strtotime($rent->end_date)), 0, 1);

        $pdf->Cell(50, 10, 'Pending Rent Amount:', 0, 0);
        $pdf->Cell(100, 10, 'Rs. ' . number_format($rent->rent_amount * $pendingMonths, 2) . ' (' . $pendingMonths . ' Month(s))', 0, 1);

        // Line Break
        $pdf->Ln(5);

        // Footer message
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(190, 10, 'Thank you for your payment!', 0, 1, 'C');

        // Output PDF to the browser
        return response($pdf->Output('S'))->header('Content-Type', 'application/pdf');
    }
}
