@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Loans /</span> Edit Loans</h4>
@if ($errors->any())
@foreach ($errors->all() as $error)
<div class="alert alert-danger alert-dismissible" role="alert">
    {{ $error }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endforeach
@endif
<form method="POST" action="{{ route('loans.update', $loan->id) }}" id="formLoan" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="issued_by" value="{{ Auth::user()->id }}">
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Loan Details</h5>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="employee_id" class="form-label">Employee</label>
                            <select id="userDropdown" name="employee_id" class="form-control form-select user-select">
                                <option></option>
                                @foreach($allEmployees as $ky => $employee)
                                <option value="{{$ky}}" @if($loan['employee_id']==$ky) selected @endif>{{$employee}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="loan_amount" class="form-label">Loan Amount <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control @error('loan_amount') is-invalid @enderror" type="number" name="loan_amount" value="{{ $loan['loan_amount'] }}">
                            </div>
                            @error('loan_amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="tenure_months" class="form-label">Tenure Months <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control @error('tenure_months') is-invalid @enderror" type="text" name="tenure_months" value="{{ $loan['tenure_months'] }}">
                            </div>
                            @error('tenure_months')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="interest_rate" class="form-label">interest rate</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control @error('interest_rate') is-invalid @enderror" type="text" name="interest_rate" value="{{ $loan['interest_rate'] }}">
                            </div>
                            @error('interest_rate')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="emi_amount" class="form-label">emi amount</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control @error('emi_amount') is-invalid @enderror" type="text" name="emi_amount" value="{{ $loan['emi_amount'] }}">
                            </div>
                            @error('emi_amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="start_date">start date</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control @error('start_date') is-invalid @enderror" type="date" name="start_date" value="{{ $loan['start_date'] }}">
                            </div>
                            @error('start_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="end_date">end date</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control @error('end_date') is-invalid @enderror" type="date" name="end_date" value="{{ $loan['end_date'] }}">
                            </div>
                            @error('end_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select id="userDropdown" name="status" class="form-control form-select">
                                @foreach(\App\Models\Loan::$loanStatuses as $ky => $status)
                                <option value="{{$ky}}" @if($loan['status']==$ky) selected @endif>{{$status}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('loans')}}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('.user-select').select2({
            placeholder: 'Select user',
            allowClear: true
        });
    });
</script>
@endSection