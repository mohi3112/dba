<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\ModificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $loanQuery = Loan::query();
        if ($request->filled('employeeId')) {
            $loanQuery->where('employee_id', $request->employeeId);
        }

        if ($request->filled('loanStatus')) {
            $loanQuery->where('status', $request->loanStatus);
        }

        if ($request->filled('startDate')) {
            $loanQuery->where('start_date', $request->startDate);
        }

        if ($request->filled('endDate')) {
            $loanQuery->where('end_date', $request->endDate);
        }

        $loans = $loanQuery->orderBy('id', 'desc')->paginate(20);

        $allEmployees = $this->getActiveEmployeesList(false);

        // Fetch all loans with associated employee and repayments
        // $loans = Loan::with('employee', 'repayments')->paginate(10);

        // Return a view or JSON response with the loans data
        return view('loans.index', compact('loans', 'allEmployees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allEmployees = $this->getActiveEmployeesList();
        // Return a view with a form for creating a loan
        return view('loans.create', compact('allEmployees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'employee_id' => 'required',
            'loan_amount' => 'required|numeric',
            'tenure_months' => 'required|integer',
            'status' => 'required'
        ]);

        // Create a new loan
        Loan::create($request->all());

        // Redirect or return a response
        return redirect()->route('loans')->with('success', 'Loan record created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function loanDetails($id)
    {
        $loan = Loan::with('employee', 'repayments')->findOrFail($id);

        return view('loans.loanDetails', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loan = Loan::findOrFail($id);

        $allEmployees = $this->getActiveEmployeesList();

        return view('loans.edit', compact('loan', 'allEmployees'));
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
            'employee_id' => 'required',
            'loan_amount' => 'required|numeric',
            'tenure_months' => 'required|integer',
            'status' => 'required'
        ]);

        $loan = Loan::findOrFail($id);

        if ($loan) {

            $loan->employee_id = $request->employee_id;
            $loan->loan_amount = $request->loan_amount;
            $loan->tenure_months = $request->tenure_months;
            $loan->interest_rate = $request->interest_rate;
            $loan->emi_amount = $request->emi_amount;
            $loan->start_date = $request->start_date;
            $loan->end_date = $request->end_date;
            $loan->status = $request->status;
            $loan->save();

            return redirect()->route('loans')->with('success', 'Loan updated successfully.');
        }

        return redirect()->route('loanrs')->with('error', 'Something went wrong.');
    }

    public function updateEmi(Request $request, $id)
    {
        $loanRepayment = LoanRepayment::findOrFail($id);

        if ($loanRepayment) {
            $balDue = $loanRepayment->balance_due + $loanRepayment->amount_paid - $request->amount_paid;
            $loanRepayment->loan_id = $request->loan_id;
            $loanRepayment->payment_date = $request->payment_date;
            $loanRepayment->amount_paid = $request->amount_paid;
            $loanRepayment->balance_due = $balDue;
            $loanRepayment->save();

            return redirect()->back()->with('success', 'EMI updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan) {
            $loan->deleted_by = Auth::id();
            $loan->save();

            // Soft delete the loan
            $loan->delete();

            return redirect()->route('loans')->with('success', 'Loan record deleted successfully!');
        }

        return redirect()->route('loans')->with('error', 'Something went wrong.');
    }

    public function destroyEmi($id)
    {
        $loanRepayment = LoanRepayment::findOrFail($id);

        if ($loanRepayment) {
            $loanRepayment->deleted_by = Auth::id();
            $loanRepayment->save();

            // Soft delete the loan
            $loanRepayment->delete();

            return redirect()->back()->with('success', 'Loan EMI record deleted successfully!');
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function payEmi($id, Request $request)
    {
        $loan = Loan::findOrFail($id);
        if (in_array($loan->status, [Loan::APPROVED_LOAN, Loan::ACTIVE_LOAN])) {

            $loanBalance = $loan->loan_amount - $request->amount_paid;
            $request->merge(['balance_due' => $loanBalance]);

            LoanRepayment::create($request->all());

            return redirect()->route('loans')->with('success', 'Emi paid successfully!');
        }

        return redirect()->route('loans')->with('error', 'Something went wrong.');
    }
}
