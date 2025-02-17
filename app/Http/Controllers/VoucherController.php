<?php

namespace App\Http\Controllers;

use App\Models\ModificationRequest;
use App\Models\Voucher;
use App\Services\LawyerService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use setasign\Fpdi\Fpdi;

class VoucherController extends Controller
{
    protected $lawyerService;

    public function __construct(LawyerService $lawyerService)
    {
        $this->lawyerService = $lawyerService;
    }

    public function index(Request $request)
    {
        $vouchersQuery = Voucher::query();

        if ($request->filled('title')) {
            $vouchersQuery->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('price')) {
            $vouchersQuery->where('price', $request->price);
        }

        if ($request->filled('startDate') && $request->input('endDate')) {
            $startDate = Carbon::parse($request->input('startDate'))->startOfDay();
            $endDate = Carbon::parse($request->input('endDate'))->endOfDay();
            $vouchersQuery->whereBetween('date', [$startDate, $endDate]);
        }

        $totalPrice = 0;
        if (count($_GET) > 0 && $request->filled('showTotal')) {
            $totalPrice = (clone $vouchersQuery)->sum('price');
        }

        $vouchers = $vouchersQuery->orderBy('created_at', 'desc')->paginate(10);

        $activeLawyers = $this->lawyerService->getActiveLawyers(false);

        return view('vouchers.index', compact('vouchers', 'activeLawyers', 'totalPrice'));
    }

    public function create()
    {
        $activeLawyers = $this->lawyerService->getActiveLawyers();

        return view('vouchers.create', compact('activeLawyers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'price' => 'required'
        ]);

        Voucher::create($request->all());

        return redirect()->route('vouchers')->with('success', 'Voucher record added successfully.');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);

        $activeLawyers = $this->lawyerService->getActiveLawyers();

        return view('vouchers.edit', compact('voucher', 'activeLawyers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'price' => 'required'
        ]);

        $voucher = Voucher::findOrFail($id);

        if ($voucher) {
            if (auth()->user()->hasRole('president')) {

                $voucher->title = $request->title;
                $voucher->price = $request->price;
                $voucher->issued_by = $request->issued_by;
                $voucher->issued_to = $request->issued_to;
                $voucher->description = $request->description;
                $voucher->save();

                return redirect()->route('vouchers')->with('success', 'Voucher updated successfully.');
            } else {
                $changes = $request->except(['_token', '_method']);
                $this->submitChangeRequest([
                    "table_name" => 'vouchers',
                    "record_id" => $voucher->id,
                    "changes" => $changes,
                    "action" => ModificationRequest::REQUEST_TYPE_UPDATE,
                    "requested_by" => Auth::id(),
                ]);

                return redirect()->route('vouchers')->with('success', 'Voucher updated request submitted successfully.');
            }
        }

        return redirect()->route('vouchers')->with('error', 'Something went wrong.');
    }


    public function destroy($id)
    {
        // Find the payment by ID
        $voucher = Voucher::findOrFail($id);

        if ($voucher) {
            if (auth()->user()->hasRole('president')) {
                $voucher->deleted_by = Auth::id();
                $voucher->save();

                // Soft delete the voucher
                $voucher->delete();

                return redirect()->route('vouchers')->with('success', 'Voucher record deleted successfully!');
            } else {
                $this->submitChangeRequest([
                    "table_name" => 'vouchers',
                    "record_id" => $voucher->id,
                    "action" => ModificationRequest::REQUEST_TYPE_DELETE,
                    "requested_by" => Auth::id(),
                ]);
                return redirect()->route('vouchers')->with('success', 'Voucher record delete request submitted successfully!');
            }
        }

        return redirect()->route('vouchers')->with('error', 'Something went wrong.');
    }

    public function generateVoucherReceipt($id)
    {
        // Fetch rent details by ID
        $voucher = Voucher::findOrFail($id);

        $serialNumber = 'E-' . $voucher->id;

        $activeLawyers = $this->lawyerService->getActiveLawyers(false);

        // Create new PDF instance
        $pdf = new Fpdi();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Set the title
        $pdf->SetXY(10, 10);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(190, 10, 'Voucher Receipt', 0, 1, 'C');

        // Line Break
        $pdf->Ln(10);

        // Add rent details
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'Serial Number:', 0, 0);
        $pdf->Cell(100, 10, $serialNumber, 0, 1);

        $pdf->Cell(50, 10, 'Title:', 0, 0);
        $pdf->Cell(100, 10, $voucher->title, 0, 1);

        $pdf->Cell(50, 10, 'Amount:', 0, 0);
        $pdf->Cell(100, 10, 'Rs. ' . number_format($voucher->price, 2), 0, 1);

        $pdf->Cell(50, 10, 'Voucher Date:', 0, 0);
        $pdf->Cell(100, 10, $voucher->date ? date('d-m-Y', strtotime($voucher->date)) : '--', 0, 1);

        $pdf->Cell(50, 10, 'Issued To:', 0, 0);
        $pdf->Cell(100, 10, (!empty($activeLawyers) && $voucher->issued_to && $activeLawyers[$voucher->issued_to]) ? $activeLawyers[$voucher->issued_to] : '--', 0, 1);

        $pdf->Cell(50, 10, 'Issued By:', 0, 0);
        $pdf->Cell(100, 10, (!empty($activeLawyers) && $voucher->issued_by && $activeLawyers[$voucher->issued_by]) ? $activeLawyers[$voucher->issued_by] : '--', 0, 1);

        $pdf->Cell(50, 10, 'Description:', 0, 0);
        $pdf->Cell(100, 10, $voucher->description ?? '--', 0, 1);

        // Output PDF to the browser
        return response($pdf->Output('S'))->header('Content-Type', 'application/pdf');
    }

    public function upload(Request $request, $id)
    {
        // Validate the file input
        $request->validate([
            'image' => 'required|file|mimes:pdf,jpeg,jpg,png|max:2048',
        ]);

        // Retrieve the voucher
        $voucher = Voucher::findOrFail($id);

        if ($voucher) {

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $base64Picture = base64_encode(file_get_contents($file->getPathname()));
            }

            $voucher->image = $base64Picture;
            $voucher->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Scanned copy uploaded successfully!');
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }
}
