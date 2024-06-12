@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Lawyers /</span> Add User</h4>
<form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data" id="formUserAccount">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Profile Details</h5>
                <!-- Account -->
                <!-- <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img src="../assets/img/avatars/1.png" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar">
                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Upload new photo</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" class="account-file-input" name="picture" hidden="" accept="image/png, image/jpeg">
                            </label>
                            <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Reset</span>
                            </button>
                            <p class="text-muted mb-0">Allowed JPG or PNG only.</p>
                        </div>
                    </div>
                </div> -->
                <hr class="my-0">
                <div class="card-body">
                    <!-- <form id="formUserAccount" method="POST" enctype="multipart/form-data"> -->
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input class="form-control @error('first_name') is-invalid @enderror" type="text" id="first_name" placeholder="First Name" name="first_name" value="{{ old('first_name') }}" autofocus="">
                            @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="middle_name" class="form-label">Middle Name <span>(Optional)</span></label>
                            <input class="form-control" type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Middle Name" id="middle_name">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input class="form-control" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last name" id="lastName">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                            <input class="form-control @error('email') is-invalid @enderror" type="text" id="email" name="email" value="{{ old('email') }}" placeholder="Email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="father_first_name" class="form-label">Father's First Name</label>
                            <input class="form-control" type="text" id="father_first_name" placeholder="Father's first name" name="father_first_name" value="{{ old('father_first_name') }}" autofocus="">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="father_last_name" class="form-label">Father's Last Name</label>
                            <input class="form-control" type="text" id="father_last_name" placeholder="Father's last name" name="father_last_name" value="{{ old('father_last_name') }}" autofocus="">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="dob">Date of Birth <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="date" name="dob" value="{{ old('dob') }}">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select id="gender" name="gender" class="select2 form-select">
                                @foreach(\App\Models\User::$genders as $key => $gender)
                                <option value="{{$key}}" {{ old('gender') == $key ? 'selected' : '' }}>{{$gender}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="aadhaar_no">Aadhaar number <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="aadhaar_no" name="aadhaar_no" maxlength="12" value="{{ old('aadhaar_no') }}" class="form-control numeric-input @error('aadhaar_no') is-invalid @enderror" placeholder="Aadhaar number">
                                @error('aadhaar_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="mobile1">Mobile <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">IN (+91)</span>
                                <input type="text" id="mobile1" name="mobile1" maxlength="10" value="{{ old('mobile1') }}" class="form-control numeric-input @error('mobile1') is-invalid @enderror" placeholder="Mobile number">
                                @error('mobile1')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="mobile2">Alternate Mobile <span>(Optional)</span></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">IN (+91)</span>
                                <input type="text" id="mobile2" name="mobile2" maxlength="10" value="{{ old('mobile2') }}" class="form-control numeric-input" placeholder="Alternate mobile number">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="designation" class="form-label">Designation</label>
                            <select id="designation" name="designation" class="select2 form-select">
                                <option value="">Select Designation</option>
                                @foreach(\App\Models\User::$designationRoles as $key => $designation)
                                <option value="{{$key}}" {{ old('designation') == $key ? 'selected' : '' }}>{{$designation}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6 not-for-vendor">
                            <label for="degrees" class="form-label">Degrees</label>
                            <input type="text" class="form-control" placeholder="Degrees" value="{{ old('degrees') }}" id="degrees" name="degrees">
                        </div>

                        <div class="mb-3 col-md-6 for-vendor d-none">
                            <label for="business_name" class="form-label">Business Name</label>
                            <input type="text" class="form-control" placeholder="Business Name" id="business_name" name="business_name" value="{{old('business_name')}}">
                        </div>

                        <div class="mb-3 col-md-6 for-vendor d-none">
                            <label for="employees" class="form-label">Employees</label>
                            <input type="text" class="form-control" placeholder="Employees" id="employees" name="employees" value="{{old('employees')}}">
                        </div>

                        <div class="mb-3 col-md-6 for-vendor d-none">
                            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                            <select name="location_id" class="select2 form-select  @error('location_id') is-invalid @enderror">
                                <option value="">Select Location (Shop number, Floor, Complex)</option>
                                @foreach($activeLocations as $locationId => $location)
                                <option value="{{$locationId}}" @if(old('location_id')==$locationId) selected @endif>{{$location}}</option>
                                @endforeach
                            </select>
                            @error('location_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6 not-for-vendor">
                            <label for="chamber_number" class="form-label">Chamber Number</label>
                            <input type="text" class="form-control" placeholder="Chamber number" value="{{ old('chamber_number') }}" id="chamber_number" name="chamber_number">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="address">Residence Address</label>
                            <textarea class="form-control" id="address" name="address" placeholder="Residence address"> {{ old('address') }} </textarea>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="other_details">Other details</label>
                            <textarea id="other_details" class="form-control" name="other_details" placeholder="Other details"> {{ old('other_details') }} </textarea>
                        </div>

                        @if(auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('president'))
                        <div class="mb-3 col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" aria-describedby="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" class="form-control @error('password') is-invalid @enderror" name="password">
                        </div>
                        @endif

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="showToastPlacement">&nbsp;</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="status" checked="">
                                        <label class="form-check-label" for="status"> Active </label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="is_deceased">
                                        <label class="form-check-label" for="is_deceased"> Is Deceased?</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="showToastPlacement">&nbsp;</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="is_physically_disabled">
                                        <label class="form-check-label" for="is_physically_disabled"> Is Physically Disabled? </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="divider divider-primary">
                            <div class="divider-text">Upload Documents</div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="image" class="form-label">Picture</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="address_proof" class="form-label">Address proof (Images)</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="address_proof" name="address_proofs[]" multiple accept="image/*">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6 not-for-vendor">
                            <label for="degree_pictures" class="form-label">Upload Degrees (Images)</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="degree_pictures" name="degree_pictures[]" multiple accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('users')}}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                    <!-- </form> -->
                </div>
                <!-- /Account -->
            </div>
        </div>

    </div>
</form>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('.numeric-input').on('input', function() {
            // Get current value
            var currentValue = $(this).val();

            // Remove non-numeric characters
            var numericValue = currentValue.replace(/[^0-9]/g, '');

            // Update input value
            $(this).val(numericValue);
        });

        $('#designation').on('change', function() {
            if ($(this).val() == '{{\App\Models\User::DESIGNATION_VENDOR}}') {
                $('#degrees, #chamber_number, #degree_pictures').prop('disabled', true);
                $('.not-for-vendor').hide();
                $('.for-vendor').show();
                $('.for-vendor').removeClass('d-none');
            } else {
                $('#degrees, #chamber_number, #degree_pictures').prop('disabled', false);
                $('.not-for-vendor').show();
                $('.for-vendor').hide();
                $('.not-for-vendor').removeClass('d-none');
            }
        });
    });
</script>
@endsection