@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">@if(@$_GET['type'] == 'vendor') Vendors @elseif(@$_GET['type'] == 'employee') Employees @else Lawyers @endif /</span> Edit User</h4>
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@php
$isVendor = FALSE;
if ($user->designation == \App\Models\User::DESIGNATION_VENDOR) {
$isVendor = 'disabled';
}
@endphp
<form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data" id="formUserAccount">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Edit Profile Details</h5>
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
                            <input class="form-control @error('first_name') is-invalid @enderror" type="text" id="first_name" placeholder="First Name" name="first_name" value="{{$user->first_name}}" autofocus="">
                            @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="middle_name" class="form-label">Middle Name <span>(Optional)</span></label>
                            <input class="form-control" type="text" name="middle_name" placeholder="Middle Name" id="middle_name" value="{{$user->middle_name}}">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input class="form-control" type="text" name="last_name" placeholder="Last name" id="lastName" value="{{$user->last_name}}">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail </label>
                            <input class="form-control  @error('email') is-invalid @enderror" type="text" id="email" autocomplete="off" name="email" value="{{$user->email}}" placeholder="Email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="father_first_name" class="form-label">Father's First Name</label>
                            <input class="form-control" type="text" id="father_first_name" placeholder="Father's first name" name="father_first_name" value="{{$user->father_first_name}}" autofocus="">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="father_last_name" class="form-label">Father's Last Name</label>
                            <input class="form-control" type="text" id="father_last_name" placeholder="Father's last name" name="father_last_name" value="{{$user->father_last_name}}" autofocus="">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="dob">Date of Birth </label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="date" name="dob" value="{{$user->dob}}">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select id="gender" name="gender" class="select2 form-select">
                                @foreach(\App\Models\User::$genders as $key => $gender)
                                <option value="{{$key}}" {{ $user->gender == $key ? 'selected' : '' }}>{{$gender}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="designation" class="form-label">Designation <span class="text-danger">*</span></label>
                            <select id="designation" name="designation" class="select2 form-select @error('designation') is-invalid @enderror">
                                <option value="">Select Designation</option>
                                @if(@$_GET['type'] =='employee')
                                <option value="{{\App\Models\User::DESIGNATION_EMPLOYEE}}" selected>Employee</option>
                                @else
                                @foreach(\App\Models\User::$allDesignationRoles as $key => $designation)
                                <option value="{{$key}}" {{ $user->designation == $key ? 'selected' : '' }}>{{$designation}}</option>
                                @endforeach
                                @endif
                            </select>
                            @error('designation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        @if(@$_GET['type'] != 'employee')
                        <div class="mb-3 col-md-6 not-for-vendor @if($isVendor) d-none @endif">
                            <label class="form-label" for="licence_no">Licence number</label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="licence_no" name="licence_no" value="{{$user->licence_no}}" maxlength="12" class="form-control @error('licence_no') is-invalid @enderror" placeholder="Licence number">
                                @error('licence_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        @endif
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="aadhaar_no">Aadhaar number</label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="aadhaar_no" name="aadhaar_no" value="{{$user->aadhaar_no}}" maxlength="12" class="form-control numeric-input  @error('aadhaar_no') is-invalid @enderror" placeholder="Aadhaar number">
                                @error('aadhaar_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="mobile1">Mobile</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">IN (+91)</span>
                                <input type="text" id="mobile1" name="mobile1" value="{{$user->mobile1}}" maxlength="10" class="form-control numeric-input @error('mobile1') is-invalid @enderror" placeholder="Mobile number">
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
                                <input type="text" id="mobile2" name="mobile2" value="{{$user->mobile2}}" maxlength="10" class="form-control numeric-input" placeholder="Alternate mobile number">
                            </div>
                        </div>
                        @if(@$_GET['type'] != 'employee')
                        <div class="mb-3 col-md-6 not-for-vendor @if($isVendor) d-none @endif">
                            <label for="degrees" class="form-label">Degrees</label>
                            <input type="text" class="form-control" placeholder="Degrees" @if($isVendor) {{$isVendor}} @endif id="degrees" name="degrees" value="{{@$user->degrees}}">
                        </div>
                        @endif
                        <div class="mb-3 col-md-6 for-vendor @if(!$isVendor) d-none @endif">
                            <label for="business_name" class="form-label">Business Name</label>
                            <input type="text" class="form-control" placeholder="Business Name" id="business_name" name="business_name" value="{{@$user->vendorInfo->business_name}}">
                        </div>

                        <div class="mb-3 col-md-6 for-vendor @if(!$isVendor) d-none @endif">
                            <label for="employees" class="form-label">Employees</label>
                            <input type="text" class="form-control" placeholder="Employees" id="employees" name="employees" value="{{@$user->vendorInfo->employees}}">
                        </div>

                        <div class="mb-3 col-md-6 for-vendor @if(!$isVendor) d-none @endif">
                            <label for="security_deposit" class="form-label">Security Deposit</label>
                            <input type="text" class="form-control" placeholder="Security Deposit" id="security_deposit" name="security_deposit" value="{{@$user->vendorInfo->security_deposit}}">
                        </div>

                        <div class="mb-3 col-md-6 for-vendor @if(!$isVendor) d-none @endif">
                            <label for="location" class="form-label">Location </label>
                            <select name="location_id" class="select2 form-select  @error('location_id') is-invalid @enderror">
                                <option value="">Select Location (Shop number, Floor, Complex)</option>
                                @foreach($activeLocations as $locationId => $location)
                                <option value="{{$locationId}}" @if(@$user->vendorInfo->location_id == $locationId) selected @endif>{{$location}}</option>
                                @endforeach
                            </select>
                            @error('location_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        @if(@$_GET['type'] =='employee')
                        <div class="mb-3 col-md-6">
                            <label for="position" class="form-label">Position</label>
                            <input class="form-control @error('position') is-invalid @enderror" type="text" id="position" placeholder="Position" name="position" value="{{$user->employees->position}}" autofocus="">
                            @error('position')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="salary" class="form-label">Salary</label>
                            <input class="form-control @error('salary') is-invalid @enderror" type="text" id="salary" placeholder="Salary" name="salary" value="{{ $user->employees->salary }}" autofocus="">
                            @error('salary')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        @else
                        <div class="mb-3 col-md-6 not-for-vendor @if($isVendor) d-none @endif">
                            <label for="chamber_number" class="form-label">Chamber Number</label>
                            <input type="text" class="form-control" placeholder="Chamber number" @if($isVendor) {{$isVendor}} @endif id="chamber_number" name="chamber_number" value="{{@$user->chamber_number}}">
                        </div>
                        @endif

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="address">Residence Address</label>
                            <textarea id="address" class="form-control" id="address" name="address" placeholder="Residence address">{{$user->address}}</textarea>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="other_details">Other details</label>
                            <textarea id="other_details" class="form-control" name="other_details" placeholder="Other details">{{$user->other_details}}</textarea>
                        </div>

                        @if(auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('president') || auth()->user()->hasRole('clerk'))
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
                                        <input class="form-check-input" type="checkbox" {{$user->status == 1 ? 'checked value=1' : 'value=2'}} name="status">
                                        <label class="form-check-label" for="status"> Active </label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" {{$user->is_deceased == 1 ? 'checked value=1' : ''}} name="is_deceased">
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
                                        <input class="form-check-input" type="checkbox" {{$user->is_physically_disabled == 1 ? 'checked value=1' : ''}} name="is_physically_disabled">
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
                            @if($user->picture)
                            <div class="demo-inline-spacing">
                                <span type="button" class="badge bg-label-dark" data-bs-toggle="modal" data-bs-target="#profilePictureModal">Uploaded Picture</span>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="profilePictureModal" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <img src="data:image/jpeg;base64,{{ $user->picture }}" alt="Description of Image" style="max-width: 750px;">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="button" parent-btn="#profilePictureModal" data-url="delete-lawyer-image" data-image-id="{{ $user->id }}" class="btn btn-danger delete-image">Delete Image</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal -->
                            @endif
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="address_proof" class="form-label">Address proof (Images)</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="address_proof" name="address_proofs[]" multiple accept="image/*">
                            </div>
                            @if($user->address_proof->count() > 0)
                            <div class="d-flex justify-content-end pt-1">
                                @php($i=1)
                                <div class="demo-inline-spacing">
                                    @foreach($user->address_proof as $proof)
                                    <span type="button" class="pl-2 badge bg-label-dark" data-bs-toggle="modal" data-bs-target="#addressProofModal{{ $proof->id }}">Uploaded Document - {{ $i }}</span>

                                    <!-- Modal -->
                                    <div class="modal fade" id="addressProofModal{{ $proof->id }}" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <img src="data:image/jpeg;base64,{{ $proof->image }}" alt="Description of Image" style="max-width: 750px;">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="button" parent-btn="#addressProofModal{{ $proof->id }}" data-url="delete-address-proof-image" data-image-id="{{ $proof->id }}" class="btn btn-danger delete-image">Delete Image</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal -->
                                    @php($i++)
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="mb-3 col-md-6 not-for-vendor @if($isVendor) d-none @endif">
                            <label for="degree_pictures" class="form-label">Upload Degrees (Images)</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="degree_pictures" @if($isVendor) {{$isVendor}} @endif name="degree_pictures[]" multiple accept="image/*">
                            </div>
                            @if($user->degree_images->count() > 0)
                            <div class="d-flex justify-content-end pt-1">
                                @php($j=1)
                                <div class="demo-inline-spacing">
                                    @foreach($user->degree_images as $proof)
                                    <span type="button" class="pl-2 badge bg-label-dark" data-bs-toggle="modal" data-bs-target="#lawyerOtherDocuments{{ $proof->id }}">Uploaded Document - {{ $j }}</span>

                                    <!-- Modal -->
                                    <div class="modal fade" id="lawyerOtherDocuments{{ $proof->id }}" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <img src="data:image/jpeg;base64,{{ $proof->image }}" alt="Description of Image" style="max-width: 750px;">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="button" parent-btn="#lawyerOtherDocuments{{ $proof->id }}" data-url="delete-degree-image" data-image-id="{{ $proof->id }}" class="btn btn-danger delete-image">Delete Image</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal -->
                                    @php($j++)
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @if(@$_GET['type'] =='employee')
                        <div class="divider divider-primary">
                            <div class="divider-text">Bank Details</div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="bank_account_number" class="form-label">Bank Account Number</label>
                                <input class="form-control @error('bank_account_number') is-invalid @enderror" type="text" id="bank_account_number" placeholder="Bank Account Number" name="bank_account_number" value="{{ $user->employees->bank_account_number }}" autofocus="">
                                @error('bank_account_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="bank_ifsc_code" class="form-label">IFSC Code</label>
                                <input class="form-control @error('bank_ifsc_code') is-invalid @enderror" type="text" id="bank_ifsc_code" placeholder="IFSC Code" name="bank_ifsc_code" value="{{ $user->employees->bank_ifsc_code }}" autofocus="">
                                @error('bank_ifsc_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="account_holder_name" class="form-label">Account Holder Name</label>
                                <input class="form-control @error('account_holder_name') is-invalid @enderror" type="text" id="account_holder_name" placeholder="Account Holder Name" name="account_holder_name" value="{{ $user->employees->account_holder_name }}" autofocus="">
                                @error('account_holder_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="branch_name" class="form-label">Branch Name</label>
                                <input class="form-control @error('branch_name') is-invalid @enderror" type="text" id="branch_name" placeholder="Branch Name" name="branch_name" value="{{ $user->employees->branch_name }}" autofocus="">
                                @error('branch_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="divider divider-primary">
                            <div class="divider-text">Other Details</div>
                        </div>
                        <div class="row mb-3">
                            <div class="mb-3 col-md-6">
                                <label for="esi_number" class="form-label">ESI Number</label>
                                <input class="form-control @error('esi_number') is-invalid @enderror" type="text" id="esi_number" placeholder="ESI Number" name="esi_number" value="{{ $user->employees->esi_number }}" autofocus="">
                                @error('esi_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="esi_contribution" class="form-label">ESI Contribution</label>
                                <input class="form-control @error('esi_contribution') is-invalid @enderror" type="text" id="esi_contribution" placeholder="ESI Contribution Amount" name="esi_contribution" value="{{ $user->employees->esi_contribution }}" autofocus="">
                                @error('esi_contribution')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="esi_start_date" class="form-label">ESI Start Date</label>
                                <input class="form-control @error('esi_start_date') is-invalid @enderror" type="date" id="esi_start_date" name="esi_start_date" value="{{ $user->employees->esi_start_date }}" autofocus="">
                                @error('esi_start_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="esi_end_date" class="form-label">ESI End Date</label>
                                <input class="form-control @error('esi_end_date') is-invalid @enderror" type="date" id="esi_end_date" name="esi_end_date" value="{{ $user->employees->esi_end_date }}" autofocus="">
                                @error('esi_end_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="card-header pl-0">Policies Details</h5>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="button" class="btn btn-primary" id="add-row-policy">Add Row</button>
                            </div>
                        </div>
                        @if($user->employees->policies)
                        <div class="card-body pt-0 pl-0 pr-0">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Policy Name</th>
                                            <th>Policy Number</th>
                                            <th>Issue Date</th>
                                            <th>Expiry Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php
                                        $i = 1;
                                        $policies = json_decode($user->employees->policies, true);
                                        ?>
                                        @foreach($policies as $policy)
                                        <tr id="row-{{$i}}">
                                            <td> {{ $i }} </td>
                                            <td> {{ $policy['policy_name'] }} </td>
                                            <td> {{ $policy['policy_number'] }} </td>
                                            <td> {{ ($policy['policy_issue_date']) ? \Carbon\Carbon::parse($policy['policy_issue_date'])->format('d-M-Y') : '--' }} </td>
                                            <td> {{ ($policy['policy_expiry_date']) ? \Carbon\Carbon::parse($policy['policy_expiry_date'])->format('d-M-Y') : '--' }} </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <!-- delete -->
                                                    <a onclick="confirmDelete({{ $user->employees->id }}, {{ $i }})" class="btn pl-3 delete-policy-record color-unset" href="javascript:void(0);">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <input class="form-control" type="hidden" value="{{ $policy['policy_name'] }}" name="policy_name[]">
                                            <input class="form-control" type="hidden" value="{{ $policy['policy_number'] }}" name="policy_number[]">
                                            <input class="form-control" type="hidden" value="{{ $policy['policy_issue_date'] }}" name="policy_issue_date[]">
                                            <input class="form-control" type="hidden" value="{{ $policy['policy_expiry_date'] }}" name="policy_expiry_date[]">
                                        </tr>
                                        @php($i++)
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        <div class="card-body pt-0 pl-0" id="policy-section">
                            <div class="row policy-row">
                                <div class="mb-3 col-md-3">
                                    <label for="policy_name" class="form-label">Policy Name</label>
                                    <input class="form-control @error('policy_name') is-invalid @enderror" type="text" id="policy_name" placeholder="Policy Name" name="policy_name[]" autofocus="">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label" for="policy_number">Policy Number</label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control" type="text" placeholder="Policy Number" name="policy_number[]">
                                    </div>
                                </div>
                                <div class="mb-3 col-md-2">
                                    <label class="form-label" for="policy_issue_date">Issue Date</label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control" type="date" placeholder="Issue Date" name="policy_issue_date[]">
                                    </div>
                                </div>
                                <div class="mb-3 col-md-2">
                                    <label class="form-label" for="policy_expiry_date">Expiry Date</label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control" type="date" placeholder="Expiry Date" name="policy_expiry_date[]">
                                    </div>
                                </div>
                                <div class="mb-3 col-md-2">
                                    <label for="showToastPlacement" class="form-label">&nbsp;</label>
                                    <div class="input-group input-group-merge">
                                        <button class="btn btn-danger ml-2 delete-row">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="divider divider-primary">
                            <div class="divider-text">Other Documents</div>
                        </div>

                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4 text-end">
                                <button type="button" class="btn btn-primary" id="add-row">Add Row</button>
                            </div>
                        </div>
                        <span id="other-document-section">
                            <div class="row other-document-row">
                                <div class="mb-3 col-md-6">
                                    <label for="doc_type" class="form-label">Document Type</label>
                                    <input class="form-control" type="text" id="doc_type" placeholder="Document Type" name="doc_type[]">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="document" class="form-label">Upload Document</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="document" name="document[]" multiple accept="image/*">
                                    </div>
                                </div>
                                <div class="mb-3 col-md-2 text-end">
                                    <label for="showToastPlacement" class="form-label">&nbsp;</label>
                                    <div class="text-end">
                                        <button class="btn btn-danger ml-2 delete-row">Delete Row</button>
                                    </div>
                                </div>
                            </div>
                        </span>
                        <div class="row">
                            <div class="col-md-8">
                                @if($user->other_documents->count() > 0)
                                <div class="d-flex pt-1 pb-3">
                                    @php($od=1)
                                    <div class="demo-inline-spacing">
                                        @foreach($user->other_documents as $proof)
                                        <span type="button" class="pl-2 badge bg-label-dark" data-bs-toggle="modal" data-bs-target="#lawyerDegreeProof{{ $proof->id }}">Uploaded Document - {{ $od }}</span>

                                        <!-- Modal -->
                                        <div class="modal fade" id="lawyerDegreeProof{{ $proof->id }}" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <img src="data:image/jpeg;base64,{{ $proof->document }}" alt="Description of Image" style="max-width: 750px;">
                                                        <p>{{ $proof->doc_type }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                            Close
                                                        </button>
                                                        <button type="button" parent-btn="#lawyerDegreeProof{{ $proof->id }}" data-url="delete-other-document" data-image-id="{{ $proof->id }}" class="btn btn-danger delete-image">Delete Image</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal -->
                                        @php($od++)
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <?php
                        $route = route('users');
                        if (@$_GET['type'] == 'employee') {
                            $route = route('employees');
                        } elseif (@$_GET['type'] == 'vendor') {
                            $route = route('vendors');
                        }
                        ?>
                        <a type="reset" href="{{ $route }}" class="btn btn-outline-secondary">Cancel</a>
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
    function confirmDelete(recordId, index) {
        const userConfirmed = confirm("Are you sure to delete this policy record?");
        if (userConfirmed) {
            $.ajax({
                url: `/employee-policy/${recordId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token
                    policyRecordIndex: index
                },
                success: function(response) {
                    console.log(response.success);

                    if (response.success) {
                        console.log(`#row-${index}`);

                        // Remove the row from the table or update the UI accordingly
                        $(`#row-${index}`).remove();
                    } else {
                        alert('Failed to delete the policy record. Please try again later.');
                    }
                },
                error: function(xhr) {
                    alert('Something went wrong. Please try again later.');
                }
            });
        }
    }

    function policyDatePickerInit() {
        document.querySelectorAll("input[name='policy_issue_date[]']").forEach(function(input) {
            input.addEventListener("click", function() {
                this.showPicker(); // Native datepicker (Chrome, Edge)
            });
        });
        document.querySelectorAll("input[name='policy_expiry_date[]']").forEach(function(input) {
            input.addEventListener("click", function() {
                this.showPicker(); // Native datepicker (Chrome, Edge)
            });
        });
    }

    $(document).ready(function() {
        policyDatePickerInit();
        $('.numeric-input').on('input', function() {
            // Get current value
            var currentValue = $(this).val();

            // Remove non-numeric characters
            var numericValue = currentValue.replace(/[^0-9]/g, '');

            // Update input value
            $(this).val(numericValue);
        });

        $('.delete-image').click(function() {
            // Get image id
            let imageId = $(this).data('image-id');
            let url = $(this).data('url');
            let ajaxUrl = '/' + url + '/' + imageId;

            // Get parent button
            var modalButton = $(this).attr('parent-btn');

            // Ask for confirmation
            var confirmation = confirm("Are you sure to delete this image?");

            if (confirmation) {
                $.ajax({
                    url: ajaxUrl,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        $("[data-bs-dismiss='modal']").trigger('click');
                        $('[data-bs-target="' + modalButton + '"]').remove();
                    }
                });
            }
        });

        $('#designation').on('change', function() {
            if ($(this).val() == '{{ \App\Models\User::DESIGNATION_VENDOR }}') {
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


        // add row other document on clicking the button
        if ($('#add-row').length > 0) {
            document.getElementById('add-row').addEventListener('click', function() {
                let newRow = document.querySelector('.other-document-row').cloneNode(true);
                newRow.querySelectorAll('input').forEach(input => input.value = '');
                document.getElementById('other-document-section').appendChild(newRow);
            });
        }

        // add row policy on clicking the button
        if ($('#add-row-policy').length > 0) {
            document.getElementById('add-row-policy').addEventListener('click', function() {
                let newRow = document.querySelector('.policy-row').cloneNode(true);
                newRow.querySelectorAll('input').forEach(input => input.value = '');
                document.getElementById('policy-section').appendChild(newRow);
                policyDatePickerInit();
            });
        }

        // delete row
        if ($('#other-document-section').length > 0) {
            document.getElementById('other-document-section').addEventListener('click', function(event) {
                if (event.target.classList.contains('delete-row')) {
                    let familyRows = document.querySelectorAll('.other-document-row');
                    if (familyRows.length > 1) {
                        var confirmationForDelete = confirm('Are you sure you want to delete this row?');
                        if (confirmationForDelete) {
                            event.target.closest('.other-document-row').remove();
                        }
                    } else {
                        alert("You can't delete this row. Please leave it blank if not required.");
                    }
                }
            });
        }
    });
</script>
@endsection