@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Locations /</span> Edit Location</h4>
<form method="POST" action="{{ route('locations.update', $location->id) }}" id="formLocation">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Location Details</h5>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="shop_number">Shop number <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="shop_number" value="{{$location->shop_number}}" name="shop_number" class="form-control @error('shop_number') is-invalid @enderror" placeholder="Shop number">
                                @error('shop_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="floor_number">Floor <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="floor_number @error('floor_number') is-invalid @enderror" value="{{$location->floor_number}}" name="floor_number" class="form-control" placeholder="Floor">
                                @error('floor_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="complex">Complex <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="complex" value="{{$location->complex}}" name="complex" class="form-control" placeholder="Complex">
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="rent" class="form-label">Rent <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control @error('rent') is-invalid @enderror" min="0" value="{{$location->rent}}" name="rent" placeholder="Rent">
                                <span class="input-group-text">.00</span>
                                @error('rent')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('locations')}}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection