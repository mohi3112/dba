@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Loan</span></h4>

@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card mb-4">
    <h5 class="card-header">Loan Details</h5>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <h6 class="mt-2 text-muted">Basic Information</h6>
                <div class="card col-12">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <label for=""> Employee Name: </label> <span> {{ $loan->employee->name }} </span>
                        </li>
                        <li class="list-group-item">
                            <label for=""> Loan Amount: </label> <span> ₹{{ $loan->loan_amount }} </span>
                        </li>
                        <li class="list-group-item">
                            <label for=""> Tenure: </label> <span> {{ $loan->tenure_months }} Months </span>
                        </li>
                        <li class="list-group-item">
                            <label for=""> EMI Amount: </label> <span> ₹{{ $loan->emi_amount }} </span>
                        </li>
                        <li class="list-group-item">
                            <label for=""> Loan Start Date: </label> <span> {{ ($loan->start_date) ? \Carbon\Carbon::parse($loan->start_date)->format('d-M-Y') : NULL }} </span>
                        </li>
                        <li class="list-group-item">
                            <label for=""> Loan End Date: </label> <span> {{ ($loan->end_date) ? \Carbon\Carbon::parse($loan->end_date)->format('d-M-Y') : NULL }} </span>
                        </li>
                        <li class="list-group-item">
                            <label for=""> Statue: </label> <span> {{ \App\Models\Loan::$loanStatuses[$loan->status] }} </span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Striped Rows -->

<div class="card">
    <h5 class="card-header">EMI's Details</h5>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Payment Date</th>
                        <th>Paid EMI Amount</th>
                        <th>Loan Balance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php($i = 1)
                    @foreach($loan->repayments as $emi)
                    <tr>
                        <td> {{ $i }} </td>
                        <td>{{ ($emi->payment_date) ? \Carbon\Carbon::parse($emi->payment_date)->format('d-M-Y') : NULL }}</td>
                        <td>₹{{ $emi->amount_paid }} </td>
                        <td>₹{{ $emi->balance_due }} </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <!-- edit -->
                                <a class="color-unset" data-bs-toggle="modal" data-bs-target="#emiModal"><i class="fas fa-edit"></i></a>

                                <div class="modal fade" id="emiModal" tabindex="-1" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog" role="document">
                                        <form method="POST" action="{{ route('loan.updateEmi', $emi->id) }}">
                                            @method('PUT')
                                            @csrf
                                            <input type="hidden" name="loan_id" value="{{$loan->id}}">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel1">Pay EMI</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-6">
                                                        <div class="col mb-0">
                                                            <label for="payment_date" class="form-label">Emi Date <span class="text-danger">*</span></label>
                                                            <input type="date" id="payment_date" class="form-control" name="payment_date" value="{{$emi->payment_date}}" required>
                                                        </div>
                                                        <div class="col mb-0">
                                                            <label for="amount_paid" class="form-label">Amount <span class="text-danger">*</span></label>
                                                            <input type="number" min="0" id="amount_paid" class="form-control" name="amount_paid" value="{{$emi->amount_paid}}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary submit-emi">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- delete -->
                                <form action="{{ route('loan.destroyEmi', $emi->id) }}" method="POST">
                                    @csrf
                                    <a class="pl-3 delete-loan-emi color-unset" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @php($i++)
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--/ Striped Rows -->

@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // click event listener to all delete buttons with the class 'delete-loan-emi'
        document.querySelectorAll('.delete-loan-emi').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Show the confirmation dialog
                if (confirm('Are you sure you want to delete this record?')) {
                    // If the user confirms, submit the nearest form
                    this.closest('form').submit();
                }
            });
        });

        document.querySelectorAll('.submit-emi').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                // Get the form fields
                const emiForm = this.closest('form');
                const paymentDate = emiForm.querySelector('#payment_date');
                const amountPaid = emiForm.querySelector('#amount_paid');

                // Check if both fields have values
                if (!paymentDate.value || !amountPaid.value) {
                    alert('Please fill all the details.');
                    return; // Stop the form submission
                }

                emiForm.submit();
            });
        });
    });
</script>
@endsection