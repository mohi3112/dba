<?php

namespace App\Http\Controllers;

use App\Models\ModificationRequest;
use App\Models\Voucher;
use App\Services\LawyerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if ($request->filled('date')) {
            $vouchersQuery->where('date', $request->date);
        }

        $vouchers = $vouchersQuery->orderBy('created_at', 'desc')->paginate(10);

        $activeLawyers = $this->lawyerService->getActiveLawyers(false);

        return view('vouchers.index', compact('vouchers', 'activeLawyers'));
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
}
