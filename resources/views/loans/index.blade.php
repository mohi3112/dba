@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Loan</span></h4>
<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="{{route('loans.add')}}"><i class="bx bx-user me-1"></i> Add Loan</a>
    </li>
</ul>
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card mb-4">
    <h5 class="card-header">Filters</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('loans') }}">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-md-3">
                    <label for="employeeId" class="form-label">Employee</label>
                    <select id="userDropdown" name="employeeId" class="form-control form-select user-select">
                        <option></option>
                        @foreach($allEmployees as $ky => $employee)
                        <option value="{{$ky}}" @if(@$_GET['employeeId']==$ky) selected @endif>{{$employee}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="loanStatus" class="form-label">Status</label>
                    <select id="loanStatusDropdown" name="loanStatus" class="form-control form-select">
                        <option></option>
                        @foreach(\App\Models\Loan::$loanStatuses as $k => $status)
                        <option value="{{$k}}" @if(@$_GET['loanStatus']==$k) selected @endif>{{$status}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="startDate" class="form-label">Start Date</label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="startDate" value="{{@$_GET['startDate']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="endDate" class="form-label">End Date</label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="endDate" value="{{@$_GET['endDate']}}">
                    </div>
                </div>

                <div class="col-md-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <button class="btn btn-primary">Filter</button>
                </div>

                <div class="col-md-1 ml-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <a href="{{ route('loans') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Striped Rows -->

<div class="card">
    <div class="table-responsive text-nowrap">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Employee Name</th>
                    <th>Loan Amount</th>
                    <th>Start Date <br> End Date</th>
                    <th>Status</th>
                    <th>Pay EMI</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($loans as $loan)
                <tr>
                    <td> {{ $i }} </td>
                    <td> {{ $allEmployees ? $allEmployees[$loan->employee_id] : '--' }} </td>
                    <td>₹{{ $loan->loan_amount }} </td>
                    <td>{{ ($loan->start_date) ? \Carbon\Carbon::parse($loan->start_date)->format('d-M-Y') : NULL }} <br> {{ ($loan->end_date) ? \Carbon\Carbon::parse($loan->end_date)->format('d-M-Y') : NULL }} </td>
                    <td> {{ ucfirst($loan->status) }} </td>
                    <td>
                        @if(in_array($loan->status, [\App\Models\Loan::APPROVED_LOAN, \App\Models\Loan::ACTIVE_LOAN]))
                        <button type="button" class="btn rounded-pill btn-outline-primary" data-bs-toggle="modal" data-bs-target="#emiModal{{$loan->id}}">Pay Now</button>

                        <div class="modal fade" id="emiModal{{$loan->id}}" tabindex="-1" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog" role="document">
                                <form method="POST" action="{{ route('loans.payEmi', $loan->id) }}">
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
                                                    <input type="date" id="payment_date" class="form-control" name="payment_date" value="{{date('Y-m-d')}}" required>
                                                </div>
                                                <div class="col mb-0">
                                                    <label for="amount_paid" class="form-label">Amount <span class="text-danger">*</span></label>
                                                    <input type="number" min="0" id="amount_paid" class="form-control" name="amount_paid" value="{{$loan->emi_amount}}" required>
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
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <!-- edit -->
                            <a class="color-unset" href="{{ route('loans.edit', $loan->id) }}"><i class="fas fa-edit"></i></a>
                            <!-- view -->
                            <a class="pl-3 color-unset" href="{{ route('loan.loanDetails', $loan->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <!-- delete -->
                            <form action="{{ route('loans.destroy', $loan->id) }}" method="POST">
                                @csrf
                                <a class="pl-3 delete-loan color-unset" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </form>
                        </div>
                    </td>
                </tr>
                @php($i++)
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3 mr-3">
            {{ $loans->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->

@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // click event listener to all delete buttons with the class 'delete-loan'
        document.querySelectorAll('.delete-loan').forEach(function(button) {
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
                const form = this.closest('form');
                const paymentDate = form.querySelector('#payment_date');
                const amountPaid = form.querySelector('#amount_paid');

                // Check if both fields have values
                if (!paymentDate.value || !amountPaid.value) {
                    alert('Please fill all the details.');
                    return; // Stop the form submission
                }

                form.submit();

            });
        });
    });
    $(document).ready(function() {
        $('.user-select').select2({
            placeholder: 'Select employee',
            allowClear: true
        });
    });
</script>
@endsection