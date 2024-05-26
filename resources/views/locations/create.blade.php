@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Locations /</span> Add Location</h4>
@if ($errors->any())
@foreach ($errors->all() as $error)
<div class="alert alert-danger alert-dismissible" role="alert">
    {{ $error }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endforeach
@endif
<form method="POST" action="{{ route('location.store') }}" id="formLocation">
    @csrf
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
                                <input type="text" id="shop_number" name="shop_number" class="form-control" placeholder="Shop number">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="floor_number">Floor <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="floor_number" name="floor_number" class="form-control" placeholder="Floor">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="complex">Complex <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="complex" name="complex" class="form-control" placeholder="Complex">
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="rent" class="form-label">Rent <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control" min="1" name="rent" placeholder="Rent">
                                <span class="input-group-text">.00</span>
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