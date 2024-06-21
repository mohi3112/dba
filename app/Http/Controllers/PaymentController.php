<?php

namespace App\Http\Controllers;

use App\Models\ModificationRequest;
use App\Models\Payment;
use App\Models\User;
use App\Services\LawyerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $lawyerService;

    public function __construct(LawyerService $lawyerService)
    {
        $this->lawyerService = $lawyerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeLawyers = $this->lawyerService->getActiveLawyers(false);

        $paymentsQuery = Payment::query();

        if (auth()->user()->hasRole('lawyer') || auth()->user()->hasRole('librarian')) {
            $paymentsQuery->where('user_id', auth()->user()->id);
        }

        $payments = $paymentsQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('payments.index', compact('payments', 'activeLawyers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $activeLawyers = $this->lawyerService->getActiveLawyers();
        return view('payments.create', compact('activeLawyers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'payment_amount' => 'required',
            'payment_date' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle the profile image
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $base64Picture = base64_encode(file_get_contents($file->getPathname()));
            $request->merge(['payment_proof' => $base64Picture]);
        }

        Payment::create($request->all());

        return redirect()->route('payments')->with('success', 'Payment record added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment = Payment::findOrFail($id);

        $activeLawyers = $this->lawyerService->getActiveLawyers();

        return view('payments.edit', compact('payment', 'activeLawyers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'payment_amount' => 'required',
            'payment_date' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $payment = Payment::findOrFail($id);

        if ($payment) {
            if (auth()->user()->hasRole('president')) {
                // Handle the profile image
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $base64Picture = base64_encode(file_get_contents($file->getPathname()));
                    $payment->payment_proof = $base64Picture;
                }

                $payment->user_id = $request->user_id;
                $payment->payment_amount = $request->payment_amount;
                $payment->payment_date = $request->payment_date;
                $payment->save();

                return redirect()->route('payments')->with('success', 'Payment updated successfully.');
            } else {
                $changes = $request->except(['_token', '_method']);
                $this->submitChangeRequest([
                    "table_name" => 'payments',
                    "record_id" => $payment->id,
                    "changes" => $changes,
                    "action" => ModificationRequest::REQUEST_TYPE_UPDATE,
                    "requested_by" => Auth::id(),
                ]);

                return redirect()->route('payments')->with('success', 'Payment updated request submitted successfully.');
            }
        }

        return redirect()->route('payments')->with('error', 'Something went wrong.');
    }

    public function destroy($id)
    {
        // Find the payment by ID
        $payment = Payment::findOrFail($id);

        if ($payment) {
            if (auth()->user()->hasRole('president')) {
                $payment->deleted_by = Auth::id();
                $payment->save();

                // Soft delete the payment
                $payment->delete();

                return redirect()->route('payments')->with('success', 'Payment record deleted successfully!');
            } else {
                $this->submitChangeRequest([
                    "table_name" => 'payments',
                    "record_id" => $payment->id,
                    "action" => ModificationRequest::REQUEST_TYPE_DELETE,
                    "requested_by" => Auth::id(),
                ]);
                return redirect()->route('payments')->with('success', 'Payment record delete request submitted successfully!');
            }
        }

        return redirect()->route('payments')->with('error', 'Something went wrong.');
    }

    public function deleteImage($id)
    {
        try {
            $payment = Payment::findOrFail($id);

            $payment->payment_proof = NULL;
            $payment->save();

            $message = 'Image deleted successfully.';
        } catch (\Exception $e) {
            $message = 'Something went wrong. Please try again.';
        }

        return response()->json(['message' => $message]);
    }
}
