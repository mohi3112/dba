@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Payments /</span> Add Payment</h4>
@if ($errors->any())
@foreach ($errors->all() as $error)
<div class="alert alert-danger alert-dismissible" role="alert">
    {{ $error }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endforeach
@endif
<form method="POST" action="{{ route('payment.store') }}" id="formPayment" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Payment Details</h5>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="lawyer" class="form-label">Lawyer <span class="text-danger">*</span></label>
                            <select id="lawyer" name="user_id" class="select2 form-select">
                                <option value="">Select Lawyer</option>
                                @foreach($activeLawyers as $lawyerId => $lawyerName)
                                <option value="{{$lawyerId}}">{{$lawyerName}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="payment_amount" class="form-label">Amount</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control" name="payment_amount" placeholder="Payment Amount">
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="payment_date">Payment Date <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="date" name="payment_date" value="" id="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="image" class="form-label">Payment Proof</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('payments')}}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection