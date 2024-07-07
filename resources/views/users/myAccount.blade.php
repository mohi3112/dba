@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account </h4>
@php
$disabled = "";
if( isset($user['approved_by_secretary']) && isset($user['approved_by_president']) && $user['approved_by_secretary'] == 1 && $user['approved_by_president'] == 0) {
$disabled = "disabled";
}
$vendor = False;
if($user['designation'] == \App\Models\User::DESIGNATION_VENDOR) {
$vendor = True;
}
@endphp

@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="row">
    <div class="col-xl-12">
        <div class="nav-align-top mb-4">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="true">
                        <i class="tf-icons bx bx-user"></i> My Account
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="false">
                        <i class='bx bx-male-female'></i> Family Details
                    </button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-top-home" role="tabpanel">
                    <form method="POST" action="{{ route('users.update', $user['id']) }}" enctype="multipart/form-data" id="formUserAccount">
                        @csrf
                        @method('PUT')
                        <h5 class="card-header">Edit Profile Details</h5>
                        @if($user['account_modified'])
                        <div class="alert alert-warning" role="alert">Your profile is under review!</div>
                        @else
                        <div class="alert alert-info" role="alert">Note: Any updates or changes to your profile will require a review.</div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                @php
                                $profilePicture = asset('images/user.webp');
                                if($user['picture']){
                                $profilePicture = "data:image/jpeg;base64,".$user['picture'];
                                }
                                @endphp
                                <img src="{{ $profilePicture }}" alt="user-avatar" data-bs-toggle="modal" data-bs-target="#profilePictureModal" class="d-block rounded" height="100" width="100" style="cursor: pointer;" id="uploadedAvatar">
                                <div class="button-wrapper">
                                    <label for="profilePicture" class="btn btn-primary me-2 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">Upload new photo</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input type="file" id="profilePicture" {{$disabled}} name="image" class="account-file-input" hidden="" accept="image/png, image/jpeg">
                                    </label>
                                    <button type="button" id="resetButton" class="btn btn-outline-secondary account-image-reset mb-4">
                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Reset</span>
                                    </button>

                                    <p class="text-muted mb-0">Allowed JPG, GIF or PNG.</p>
                                </div>

                                @if($user['picture'])
                                <!-- Modal -->
                                <div class="modal fade" id="profilePictureModal" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <img src="data:image/jpeg;base64,{{ $user['picture'] }}" alt="Description of Image" style="max-width: 750px;">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                                <button type="button" parent-btn="#profilePictureModal" data-url="delete-lawyer-image" data-image-id="{{ $user['id'] }}" class="btn btn-danger delete-image">Delete Image</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                                @endif
                            </div>
                        </div>
                        <hr class="my-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input class="form-control @error('first_name') is-invalid @enderror" type="text" id="first_name" placeholder="First Name" {{$disabled}} name="first_name" value="{{$user['first_name']}}" autofocus="">
                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="middle_name" class="form-label">Middle Name <span>(Optional)</span></label>
                                    <input class="form-control" type="text" {{$disabled}} name="middle_name" placeholder="Middle Name" id="middle_name" value="{{$user['middle_name']}}">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input class="form-control" type="text" {{$disabled}} name="last_name" placeholder="Last name" id="lastName" value="{{$user['last_name']}}">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                                    <input class="form-control  @error('email') is-invalid @enderror" type="text" id="email" {{$disabled}} name="email" value="{{$user['email']}}" placeholder="Email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="father_first_name" class="form-label">Father's First Name</label>
                                    <input class="form-control" type="text" id="father_first_name" placeholder="Father's first name" {{$disabled}} name="father_first_name" value="{{$user['father_first_name']}}" autofocus="">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="father_last_name" class="form-label">Father's Last Name</label>
                                    <input class="form-control" type="text" id="father_last_name" placeholder="Father's last name" {{$disabled}} name="father_last_name" value="{{$user['father_last_name']}}" autofocus="">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="dob">Date of Birth <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control" type="date" {{$disabled}} name="dob" value="{{$user['dob']}}">
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select id="gender" {{$disabled}} name="gender" class="select2 form-select">
                                        @foreach(\App\Models\User::$genders as $key => $gender)
                                        <option value="{{$key}}" {{ $user['gender'] == $key ? 'selected' : '' }}>{{$gender}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="licence_no">Licence number <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="licence_no" {{$disabled}} name="licence_no" value="{{$user['licence_no']}}" maxlength="12" class="form-control numeric-input  @error('licence_no') is-invalid @enderror" placeholder="Aadhaar number">
                                        @error('licence_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="aadhaar_no">Aadhaar number <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="aadhaar_no" {{$disabled}} name="aadhaar_no" value="{{$user['aadhaar_no']}}" maxlength="12" class="form-control numeric-input  @error('aadhaar_no') is-invalid @enderror" placeholder="Aadhaar number">
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
                                        <input type="text" id="mobile1" {{$disabled}} name="mobile1" value="{{$user['mobile1']}}" maxlength="10" class="form-control numeric-input @error('mobile1') is-invalid @enderror" placeholder="Mobile number">
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
                                        <input type="text" id="mobile2" {{$disabled}} name="mobile2" value="{{$user['mobile2']}}" maxlength="10" class="form-control numeric-input" placeholder="Alternate mobile number">
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="designation" class="form-label">Designation</label>
                                    <select id="designation" disabled name="designation" class="select2 form-select">
                                        <option value="">Select Designation</option>
                                        @foreach(\App\Models\User::$designationRoles as $key => $designation)
                                        <option value="{{$key}}" {{ $user['designation'] == $key ? 'selected' : '' }}>{{$designation}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="designation" value="{{$user['designation']}}">
                                @if(!$vendor)
                                <div class="mb-3 col-md-6">
                                    <label for="degrees" class="form-label">Degrees</label>
                                    <input type="text" class="form-control" placeholder="Degrees" id="degrees" {{$disabled}} name="degrees" value="{{$user['degrees']}}">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="chamber_number" class="form-label">Chamber Number</label>
                                    <input type="text" class="form-control" placeholder="Chamber number" id="chamber_number" {{$disabled}} name="chamber_number" value="{{$user['chamber_number']}}">
                                </div>
                                @else
                                <div class="mb-3 col-md-6 for-vendor">
                                    <label for="business_name" class="form-label">Business Name</label>
                                    <input type="text" class="form-control" placeholder="Business Name" id="business_name" name="business_name" value="{{@$user['business_name']}}">
                                </div>

                                <div class="mb-3 col-md-6 for-vendor">
                                    <label for="employees" class="form-label">Employees</label>
                                    <input type="text" class="form-control" placeholder="Employees" id="employees" name="employees" value="{{@$user['employees']}}">
                                </div>

                                <div class="mb-3 col-md-6 for-vendor">
                                    <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                    <select name="location_id" class="select2 form-select  @error('location_id') is-invalid @enderror">
                                        <option value="">Select Location (Shop number, Floor, Complex)</option>
                                        @foreach($activeLocations as $locationId => $location)
                                        <option value="{{$locationId}}" @if(@$user['location_id']==$locationId) selected @endif>{{$location}}</option>
                                        @endforeach
                                    </select>
                                    @error('location_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                @endif
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="address">Residence Address</label>
                                    <textarea id="address" class="form-control" id="address" {{$disabled}} name="address" placeholder="Residence address">{{$user['address']}}</textarea>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="other_details">Other details</label>
                                    <textarea id="other_details" class="form-control" {{$disabled}} name="other_details" placeholder="Other details">{{$user['other_details']}}</textarea>
                                </div>

                                @if(auth()->user()->hasRole('superadmin'))
                                <div class="mb-3 col-md-6">
                                    <label for="user_role" class="form-label">User Role</label>
                                    <select id="user_role" {{$disabled}} name="user_role" class="select2 form-select">
                                        <option value="">Select Role</option>
                                        @foreach(\App\Models\User::$designationRoles as $key => $userRole)
                                        <option value="{{$key}}" {{ $user['roles']->first()->pivot->role_id == $key ? 'selected' : '' }}>{{$userRole}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" {{$user['status'] == 1 ? 'checked value=1' : 'value=2'}} {{$disabled}} name="status">
                                                <label class="form-check-label" for="status"> Active </label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" {{$user['is_deceased'] == 1 ? 'checked value=1' : ''}} {{$disabled}} name="is_deceased">
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
                                                <input class="form-check-input" type="checkbox" {{$user['is_physically_disabled'] == 1 ? 'checked value=1' : ''}} {{$disabled}} name="is_physically_disabled">
                                                <label class="form-check-label" for="is_physically_disabled"> Is Physically Disabled? </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider divider-primary">
                                    <div class="divider-text">Upload Documents</div>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="address_proof" class="form-label">Address proof (Images)</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="address_proof" {{$disabled}} name="address_proofs[]" multiple accept="image/*">
                                    </div>
                                    @if($user['address_proof']->count() > 0)
                                    <div class="d-flex justify-content-end pt-1">
                                        @php($i=1)
                                        <div class="demo-inline-spacing">
                                            @foreach($user['address_proof'] as $proof)
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
                                @if(!$vendor)
                                <div class="mb-3 col-md-6">
                                    <label for="degree_pictures" class="form-label">Upload Degrees (Images)</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="degree_pictures" {{$disabled}} name="degree_pictures[]" multiple accept="image/*">
                                    </div>
                                    @if($user['degree_images']->count() > 0)
                                    <div class="d-flex justify-content-end pt-1">
                                        @php($j=1)
                                        <div class="demo-inline-spacing">
                                            @foreach($user['degree_images'] as $proof)
                                            <span type="button" class="pl-2 badge bg-label-dark" data-bs-toggle="modal" data-bs-target="#lawyerDegreeProof{{ $proof->id }}">Uploaded Document - {{ $j }}</span>

                                            <!-- Modal -->
                                            <div class="modal fade" id="lawyerDegreeProof{{ $proof->id }}" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <img src="data:image/jpeg;base64,{{ $proof->image }}" alt="Description of Image" style="max-width: 750px;">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                                Close
                                                            </button>
                                                            <button type="button" parent-btn="#lawyerDegreeProof{{ $proof->id }}" data-url="delete-degree-image" data-image-id="{{ $proof->id }}" class="btn btn-danger delete-image">Delete Image</button>
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
                                @endif
                            </div>
                            <div class="mt-2">
                                <button type="submit" {{$disabled}} class="btn btn-primary me-2">Save changes</button>
                            </div>
                        </div>
                        <!-- /Account -->
                    </form>
                    <!-- </div> -->
                </div>
                <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
                    @if($user->families->count() > 0)
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Name</th>
                                        <th>Relation Type</th>
                                        <th>Date of Birth / Aniversary Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @php($i = 1)
                                    @foreach($user->families as $familyMember)
                                    <tr>
                                        <td> {{ $i }} </td>
                                        <td> {{ $familyMember->name }} </td>
                                        <td>{{ \App\Models\Family::$familyRelations[$familyMember->type] }} </td>
                                        <td>{{ \Carbon\Carbon::parse($familyMember->date)->format('d-M-Y') }} </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <!-- delete -->
                                                <form action="{{ route('familyRecord.destroy', $familyMember->id) }}" method="POST">
                                                    @csrf
                                                    <a class="pl-3 delete-family-record color-unset" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @php($i++)
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('users.storeFamily') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="card-header">Family information Details</h5>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="button" class="btn btn-primary" id="add-row">Add Row</button>
                            </div>
                        </div>
                        <div class="card-body pt-0" id="family-section">
                            <div class="row family-row">
                                <div class="mb-3 col-md-3">
                                    <label for="type" class="form-label">Relation <span class="text-danger">*</span></label>
                                    <select id="type" name="type[]" class="select2 form-select type-select">
                                        <option value="">Please Select</option>
                                        @foreach(\App\Models\Family::$familyRelations as $key => $realtion)
                                        <option value="{{$key}}">{{$realtion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" placeholder="Name" name="name[]" required autofocus="">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label dob-anniversary-label" for="date">Date of Birth / Aniversary Date</label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control" type="date" name="date[]">
                                    </div>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="showToastPlacement" class="form-label">&nbsp;</label>
                                    <div class="input-group input-group-merge">
                                        <button class="btn btn-danger ml-2 delete-row">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div>
                                <button type="submit" class="btn btn-primary me-2">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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

        document.getElementById('resetButton').addEventListener('click', function() {
            document.getElementById('profilePicture').value = '';
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
        // add row family on clicking the button
        document.getElementById('add-row').addEventListener('click', function() {
            let newRow = document.querySelector('.family-row').cloneNode(true);

            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelector('select').value = '';
            // Reset the date label text
            newRow.querySelector('.dob-anniversary-label').textContent = 'DATE OF BIRTH / ANIVERSARY DATE';

            document.getElementById('family-section').appendChild(newRow);
        });

        // delete row
        document.getElementById('family-section').addEventListener('click', function(event) {
            if (event.target.classList.contains('delete-row')) {
                let familyRows = document.querySelectorAll('.family-row');
                if (familyRows.length > 1) {
                    event.target.closest('.family-row').remove();
                } else {
                    alert('You must have at least one family member record.');
                }
            }
        });

        // make spouse disabled by default after selecting
        document.getElementById('family-section').addEventListener('change', function(event) {
            if (event.target.classList.contains('type-select')) {

                let type = event.target.value;
                let dateLabel = event.target.closest('.family-row').querySelector('.dob-anniversary-label');
                if (type === 'spouse') {
                    dateLabel.textContent = 'Anniversary Date';
                } else {
                    dateLabel.textContent = 'Date of Birth';
                }
                // remove spouse from select is pending
                // let spouseSelected = false;
                // document.querySelectorAll('.type-select').forEach(function(select) {
                //     if (select.value === 'spouse') {
                //         spouseSelected = true;
                //     }
                // });

                // document.querySelectorAll('.type-select').forEach(function(select) {
                //     if (spouseSelected) {
                //         select.querySelector('option[value="spouse"]').disabled = true;
                //     } else {
                //         select.querySelector('option[value="spouse"]').disabled = false;
                //     }
                // });
            }
        });

        // click event listener to all delete buttons with the class 'delete-family-record'
        document.querySelectorAll('.delete-family-record').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default form submission
                // Show the confirmation dialog
                if (confirm('Are you sure you want to delete this record?')) {
                    // If the user confirms, submit the nearest form
                    this.closest('form').submit();
                }
            });
        });
    });
</script>
@endsection