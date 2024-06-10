@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Subscriptions /</span> Add Subscription</h4>
<form method="POST" action="{{ route('subscription.store') }}" id="formSubscription">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Subscription Details</h5>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="lawyer" class="form-label">Lawyer <span class="text-danger">*</span></label>
                            <select id="lawyer" name="user_id" class="select2 form-select @error('user_id') is-invalid @enderror">
                                <option value="">Select Lawyer</option>
                                @foreach($activeLawyers as $lawyerId => $lawyerName)
                                <option value="{{$lawyerId}}">{{$lawyerName}}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="subscription_type" class="form-label">Subscription Type <span class="text-danger">*</span></label>
                            <select id="subscription_type" name="subscription_type" class="select2 form-select @error('subscription_type') is-invalid @enderror">
                                <option value="">Select Type</option>
                                @foreach(\App\Models\Subscription::$subscriptionTypes as $key => $subscriptionType)
                                <option value="{{$key}}" {{ old('subscription_type') == $key ? 'selected' : ''}}>{{$subscriptionType}}</option>
                                @endforeach
                            </select>
                            @error('subscription_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="start_date">Subscription Start From <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control @error('start_date') is-invalid @enderror" type="date" name="start_date" value="{{ old('start_date') }}">
                            </div>
                            @error('start_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="end_date">Subscription End Date <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control @error('end_date') is-invalid @enderror" type="date" name="end_date" value="{{ old('end_date') }}">
                            </div>
                            @error('end_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('users')}}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection