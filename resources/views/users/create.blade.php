@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Users /</span> Add User</h4>
@if ($errors->any())
<div>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form method="POST" action="{{ route('user.store') }}" id="formUserAccount">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Profile Details</h5>
                <!-- Account -->
                <div class="card-body">
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
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <form id="formAccountSettings" method="POST" onsubmit="return false">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input class="form-control" type="text" id="first_name" name="first_name" value="" autofocus="">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="middle_name" class="form-label">Middle Name <span>(Optional)</span></label>
                                <input class="form-control" type="text" name="middle_name" id="middle_name" value="">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input class="form-control" type="text" name="lastName" id="lastName" value="">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input class="form-control" type="text" id="email" name="email" value="" placeholder="john.doe@example.com">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="father_first_name" class="form-label">Father's First Name</label>
                                <input class="form-control" type="text" id="father_first_name" name="father_first_name" value="" autofocus="">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="father_last_name" class="form-label">Father's Last Name</label>
                                <input class="form-control" type="text" id="father_last_name" name="father_last_name" value="" autofocus="">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <select id="gender" name="gender" class="select2 form-select">
                                    @foreach(\App\Models\User::$genders as $key => $gender)
                                    <option value="{{$key}}">{{$gender}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="mobile1">Mobile</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">IN (+91)</span>
                                    <input type="text" id="mobile1" name="mobile1" maxlength="10" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="mobile2">Alternate Mobile <span>(Optional)</span></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">IN (+91)</span>
                                    <input type="text" id="mobile2" name="mobile2" maxlength="10" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="designation" class="form-label">Designation</label>
                                <select id="designation" name="designation" class="select2 form-select">
                                    <option value="">Select Designation</option>
                                    @foreach(\App\Models\User::$designations as $key => $designation)
                                    <option value="{{$key}}">{{$designation}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="degrees" class="form-label">Degrees</label>
                                <input type="text" class="form-control" id="degrees" name="degrees" value="">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="chamber_number" class="form-label">Chamber Number</label>
                                <input type="text" class="form-control" id="chamber_number" name="chamber_number" value="">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="floor_number" class="form-label">Floor Number</label>
                                <input type="text" class="form-control" id="floor_number" name="floor_number" value="">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="building" class="form-label">Building</label>
                                <input type="text" class="form-control" id="building" name="building" value="">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="address">Address</label>
                                <textarea id="address" class="form-control" id="address" name="address" placeholder="Address"></textarea>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="city" class="form-label">City</label>
                                <input class="form-control" type="text" id="city" name="city" placeholder="City">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="state" class="form-label">State</label>
                                <input class="form-control" type="text" id="state" name="state" placeholder="State">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="country">Country</label>
                                <select id="country" class="select2 form-select">
                                    <option value="India">India</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="zipCode" class="form-label">Zip Code</label>
                                <input type="text" class="form-control" id="zipCode" name="zipCode" placeholder="141001" maxlength="6">
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Save changes</button>
                            <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
        </div>

    </div>
</form>
@endsection