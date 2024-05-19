@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Subscriptions /</span> Edit Subscription</h4>
@if ($errors->any())
@foreach ($errors->all() as $error)
<div class="alert alert-danger alert-dismissible" role="alert">
    {{ $error }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endforeach
@endif
<form method="POST" action="{{ route('subscriptions.update', $subscription->id) }}" id="formSubscription">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Edit Subscription Details</h5>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="lawyer" class="form-label">Lawyer <span class="text-danger">*</span></label>
                            <select id="lawyer" name="user_id" class="select2 form-select">
                                <option value="">Select Lawyer</option>
                                @foreach($activeLawyers as $lawyerId => $lawyerName)
                                <option value="{{$lawyerId}}" {{ $subscription->user_id == $lawyerId ? 'selected' : '' }}>{{$lawyerName}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="subscription_type" class="form-label">Subscription Type <span class="text-danger">*</span></label>
                            <select id="subscription_type" name="subscription_type" class="select2 form-select">
                                <option value="">Select Type</option>
                                @foreach(\App\Models\Subscription::$subscriptionTypes as $key => $subscriptionType)
                                <option value="{{$key}}" {{ $subscription->subscription_type == $key ? 'selected' : '' }}>{{$subscriptionType}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="start_date">Subscription Duration <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="date" name="start_date" value="{{$subscription->start_date}}" id="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="subscription_duration">Subscription Duration <span class="text-danger">*</span></label>
                            <select id="subscription_duration" name="subscription_duration" class="select2 form-select">
                                @foreach(\App\Models\Subscription::$subscriptionDurations as $k => $subscriptionDuration)
                                <option value="{{$k}}" {{ $subscription->subscription_duration == $k ? 'selected' : '' }}>{{$subscriptionDuration}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('subscriptions')}}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                    <!-- </form> -->
                </div>
                <!-- /Account -->
            </div>
        </div>

    </div>
</form>
@endsection