@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Vendors /</span> Edit Vendor</h4>
<form method="POST" action="{{ route('vendors.update', $vendor->id) }}" id="formVendor">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Vendor Details</h5>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="first_name">first name <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="first_name" value="{{$vendor->first_name}}" name="first_name" class="form-control @error('first_name') is-invalid @enderror" placeholder="First name">
                                @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="last_name">last name <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="last_name" value="{{$vendor->last_name}}" name="last_name" class="form-control @error('last_name') is-invalid @enderror" placeholder="Last name">
                                @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="father_first_name">father's first name <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="father_first_name" value="{{$vendor->father_first_name}}" name="father_first_name" class="form-control @error('father_first_name') is-invalid @enderror" placeholder="Father's first name">
                                @error('father_first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="father_last_name">father's last name</label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="father_last_name" value="{{$vendor->father_last_name}}" name="father_last_name" class="form-control" placeholder="Father's last name">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select id="gender" name="gender" class="select2 form-select">
                                @foreach(\App\Models\Vendor::$genders as $key => $gender)
                                <option value="{{$key}}" @if($vendor->gender == $key) selected @endif>{{$gender}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="dob">Date of birth</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="date" name="dob" value="{{$vendor->dob}}" id="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="mobile">Mobile number <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="mobile" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{$vendor->mobile}}" placeholder="Mobile number">
                                @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="residence_address">Residence Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="residence_address" placeholder="Residence address"> {{$vendor->residence_address}} </textarea>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="business_name">Business name <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="business_name" name="business_name" value="{{$vendor->business_name}}" class="form-control @error('business_name') is-invalid @enderror" placeholder="Business name">
                                @error('business_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="employees">Employees <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" name="employees" class="form-control @error('employees') is-invalid @enderror" value="{{$vendor->employees}}" placeholder="Employees">
                                @error('employees')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                            <select name="location_id" class="select2 form-select  @error('location_id') is-invalid @enderror">
                                <option value="">Select Location (Shop number, Floor, Complex)</option>
                                @foreach($activeLocations as $locationId => $location)
                                <option value="{{$locationId}}" @if($vendor->location_id == $locationId) selected @endif>{{$location}}</option>
                                @endforeach
                            </select>
                            @error('location_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="showToastPlacement">&nbsp;</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" {{$vendor->status == 1 ? 'checked value=1' : ''}} name="status">
                                <label class="form-check-label" for="status"> Active </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('vendors')}}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection