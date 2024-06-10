@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Payments /</span> Add Payment</h4>

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
                            <select id="lawyer" name="user_id" class="select2 form-select @error('user_id') is-invalid @enderror">
                                <option value="">Select Lawyer</option>
                                @foreach($activeLawyers as $lawyerId => $lawyerName)
                                <option value="{{$lawyerId}}" {{ old('user_id') == $lawyerId ? 'selected' : ''}}>{{$lawyerName}}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="payment_amount" class="form-label">Amount</label>
                            <select id="paymentAmount" name="payment_amount" class="select2 form-select @error('payment_amount') is-invalid @enderror">
                                <option value="">Select Payment Amount</option>
                                @foreach(\App\Models\Payment::$subscriptionPayments as $payment => $paymentValue)
                                <option value="{{$payment}}" {{ old('payment_amount') == $payment ? 'selected' : ''}}>{{$paymentValue}}</option>
                                @endforeach
                            </select>
                            @error('payment_amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="payment_date">Payment Date <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control @error('payment_date') is-invalid @enderror" type="date" name="payment_date" value="{{ old('payment_date') }}" id="">
                            </div>
                            @error('payment_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="image" class="form-label">Payment Proof</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="image" name="image" value="{{ old('image') }}" accept="image/*">
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