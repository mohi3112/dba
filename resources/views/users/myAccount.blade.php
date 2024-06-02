@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account </h4>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item">
                <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Account</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="bx bx-bell me-1"></i> Uploaded Documents</a>
            </li>
        </ul>
        <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data" id="formUserAccount">
            @csrf
            @method('PUT')
            <div class="card mb-4">
                <h5 class="card-header">Edit Profile Details</h5>
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img src="{{ asset('images/user.webp') }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar">
                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Upload new photo</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" class="account-file-input" hidden="" accept="image/png, image/jpeg">
                            </label>
                            <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Reset</span>
                            </button>

                            <p class="text-muted mb-0">Allowed JPG, GIF or PNG.</p>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-body">
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
                            <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                            <input class="form-control  @error('email') is-invalid @enderror" type="text" id="email" name="email" value="{{$user->email}}" placeholder="Email">
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
                            <label class="form-label" for="dob">Date of Birth <span class="text-danger">*</span></label>
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
                            <label class="form-label" for="aadhaar_no">Aadhaar number <span class="text-danger">*</span></label>
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
                            <label class="form-label" for="mobile1">Mobile <span class="text-danger">*</span></label>
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
                        <div class="mb-3 col-md-6">
                            <label for="designation" class="form-label">Designation</label>
                            <select id="designation" name="designation" class="select2 form-select">
                                <option value="">Select Designation</option>
                                @foreach(\App\Models\User::$designationRoles as $key => $designation)
                                <option value="{{$key}}" {{ $user->designation == $key ? 'selected' : '' }}>{{$designation}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="degrees" class="form-label">Degrees</label>
                            <input type="text" class="form-control" placeholder="Degrees" id="degrees" name="degrees" value="{{$user->degrees}}">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="chamber_number" class="form-label">Chamber Number</label>
                            <input type="text" class="form-control" placeholder="Chamber number" id="chamber_number" name="chamber_number" value="{{$user->chamber_number}}">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="address">Residence Address</label>
                            <textarea id="address" class="form-control" id="address" name="address" placeholder="Residence address">{{$user->address}}</textarea>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="other_details">Other details</label>
                            <textarea id="other_details" class="form-control" name="other_details" placeholder="Other details">{{$user->other_details}}</textarea>
                        </div>

                        @if(auth()->user()->hasRole('superadmin'))
                        <div class="mb-3 col-md-6">
                            <label for="user_role" class="form-label">User Role</label>
                            <select id="user_role" name="user_role" class="select2 form-select">
                                <option value="">Select Role</option>
                                @foreach(\App\Models\User::$designationRoles as $key => $userRole)
                                <option value="{{$key}}" {{ $user->roles->first()->pivot->role_id == $key ? 'selected' : '' }}>{{$userRole}}</option>
                                @endforeach
                            </select>
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
                            <div class="d-flex justify-content-end pt-1">
                                <span type="button" class="text-danger" data-bs-toggle="modal" data-bs-target="#profilePictureModal">Check Uploaded Picture</span>
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
                                @foreach($user->address_proof as $proof)
                                <span type="button" class="text-danger pl-2" data-bs-toggle="modal" data-bs-target="#addressProofModal{{ $proof->id }}">Check Uploaded Document - {{ $i }}</span>

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
                            @endif
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="degree_pictures" class="form-label">Upload Degrees (Images)</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="degree_pictures" name="degree_pictures[]" multiple accept="image/*">
                            </div>
                            @if($user->degree_images->count() > 0)
                            <div class="d-flex justify-content-end pt-1">
                                @php($j=1)
                                @foreach($user->degree_images as $proof)
                                <span type="button" class="text-danger pl-2" data-bs-toggle="modal" data-bs-target="#lawyerDegreeProof{{ $proof->id }}">Check Uploaded Document - {{ $j }}</span>

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
                            @endif
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('users')}}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
                <!-- /Account -->
            </div>
        </form>
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
    });
</script>
@endsection