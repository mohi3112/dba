@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Vendors /</span> Add Vendor</h4>
<form method="POST" action="{{ route('vendor.store') }}" id="formVendor">
    @csrf
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
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" class="form-control @error('first_name') is-invalid @enderror" placeholder="First name">
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
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" class="form-control @error('last_name') is-invalid @enderror" placeholder="Last name">
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
                                <input type="text" id="father_first_name" name="father_first_name" value="{{ old('father_first_name') }}" class="form-control @error('father_first_name') is-invalid @enderror" placeholder="Father's first name">
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
                                <input type="text" id="father_last_name" name="father_last_name" value="{{ old('father_last_name') }}" class="form-control" placeholder="Father's last name">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select id="gender" name="gender" class="select2 form-select">
                                @foreach(\App\Models\Vendor::$genders as $key => $gender)
                                <option value="{{$key}}" {{ old('gender') == $key ? 'selected' : '' }}>{{$gender}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="dob">Date of birth</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="date" name="dob" value="{{ old('dob') }}">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="mobile">Mobile number <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="mobile" name="mobile" value="{{ old('mobile') }}" class="form-control @error('mobile') is-invalid @enderror" placeholder="Mobile number">
                                @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="residence_address">Residence Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="residence_address" placeholder="Residence address"> {{ old('residence_address') }}</textarea>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="business_name">Business name <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="business_name" name="business_name" value="{{ old('business_name') }}" class="form-control @error('business_name') is-invalid @enderror" placeholder="Business name">
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
                                <input type="text" name="employees" value="{{ old('employees') }}" class="form-control @error('employees') is-invalid @enderror" placeholder="Employees">
                                @error('employees')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                            <select name="location_id" class="select2 form-select @error('location_id') is-invalid @enderror">
                                <option value="">Select Location (Shop number, Floor, Complex)</option>
                                @foreach($activeLocations as $lawyerId => $location)
                                <option value="{{$lawyerId}}" {{ old('location_id') == $lawyerId ? 'selected' : '' }}>{{$location}}</option>
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
                                <input class="form-check-input" type="checkbox" value="1" name="status" checked="">
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