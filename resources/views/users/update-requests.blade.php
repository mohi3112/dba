@extends('layouts.app')
@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Lawyers /</span> Update Requests</h4>
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="card mb-4">
    <h5 class="card-header">Filters</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('users.update-requests') }}">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-md-3">
                    <label for="name" class="form-label">First Name</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="name" value="{{@$_GET['name']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="l_name" class="form-label">Last Name</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="l_name" value="{{@$_GET['l_name']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="designation" class="form-label">Designation</label>
                    <select id="designation" name="designation" class="select2 form-select">
                        <option value="">Select Designation</option>
                        @foreach(\App\Models\User::$designationRoles as $key => $designation)
                        <option value="{{$key}}" @if(@$_GET['designation']==$key) selected @endif>{{$designation}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select id="gender" name="gender" class="select2 form-select">
                        <option value="">Select Gender</option>
                        @foreach(\App\Models\User::$genders as $ky => $gender)
                        <option value="{{$ky}}" @if(@$_GET['gender']==$ky) selected @endif>{{$gender}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="age">Age</label>
                    <div class="row">
                        <div class="col-md-6 pr-0">
                            <select id="ageOperator" name="ageOperator" class="select2 form-select">
                                @foreach(\App\Models\User::$ageOperator as $k => $operator)
                                <option value="{{$k}}" @if(@$_GET['ageOperator']==$k) selected @endif>{{$operator}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" id="age" value="{{@$_GET['age']}}" name="age" placeholder="Enter Age" aria-label="Age">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="approvedBySecretary" class="form-label">Approved By Secretary</label>
                    <select id="approvedBySecretary" name="approvedBySecretary" class="select2 form-select">
                        <option value="">Please Select</option>
                        <option value="yes" @if(@$_GET['approvedBySecretary']=='yes' ) selected @endif>Yes</option>
                        <option value="no" @if(@$_GET['approvedBySecretary']=='no' ) selected @endif>No</option>
                        <option value="pending" @if(@$_GET['approvedBySecretary']=='pending' ) selected @endif>Pending</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="approvedByPresident" class="form-label">Approved By President</label>
                    <select id="approvedByPresident" name="approvedByPresident" class="select2 form-select">
                        <option value="">Please Select</option>
                        <option value="yes" @if(@$_GET['approvedByPresident']=='yes' ) selected @endif>Yes</option>
                        <option value="no" @if(@$_GET['approvedByPresident']=='no' ) selected @endif>No</option>
                        <option value="pending" @if(@$_GET['approvedByPresident']=='pending' ) selected @endif>Pending</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <button class="btn btn-primary">Filter</button>
                </div>

                <div class="col-md-1 ml-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <a href="{{ route('users.update-requests') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Striped Rows -->
<div class="card">
    <div class="table-responsive text-nowrap">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Father'S Name</th>
                    <th>DOB (Age)</th>
                    <th>Gender</th>
                    <th>DESIGNATION</th>
                    <th>Request Type</th>
                    <th>Secretary Approval</th>
                    <th>President Approval</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php
                $i = 1;
                foreach ($updateRequests as $updateRequestUser) {
                ?>
                    <tr>
                        <td> {{ $i }} </td>
                        <td> {{ $updateRequestUser->full_name }} </td>
                        <td> {{ ($updateRequestUser->father_first_name) ? $updateRequestUser->father_first_name . ' ' . $updateRequestUser->father_last_name : '--' }}</td>
                        <td> {{ ($updateRequestUser->dob) ? \Carbon\Carbon::parse($updateRequestUser->dob)->format('d-M-Y') . ' (' . $updateRequestUser->age . ')' : '--' }}</td>
                        <td> {{ ($updateRequestUser->gender) ? \App\Models\User::$genders[$updateRequestUser->gender] : '--' }} </td>
                        <td>
                            {{ \App\Models\User::$designationRoles[$updateRequestUser->designation] ?? '--' }}
                        </td>
                        <td>
                            @if($updateRequestUser->change_type == \App\Models\UserUpdateRequest::CHANGE_TYPE_EDIT)
                            <span class="badge bg-label-warning me-1">Update</span>
                            @elseif($updateRequestUser->change_type == \App\Models\UserUpdateRequest::CHANGE_TYPE_DELETE)
                            <span class="badge bg-label-danger me-1">Delete</span>
                            @else
                            <span class="badge bg-label-primary">Registered</span>
                            @endif
                        </td>
                        @php
                        $secretaryApproval = \App\Models\User::PENDING_REQUEST;
                        if($updateRequestUser->approved_by_secretary == true){
                        $secretaryApproval = \App\Models\User::APPROVED_REQUEST;
                        } elseif ($updateRequestUser->approved_by_secretary == false && $updateRequestUser->approved_by_secretary !== NULL) {
                        $secretaryApproval = \App\Models\User::REJECTED_REQUEST;
                        }
                        @endphp
                        <td> {{ $secretaryApproval }} </td>
                        @php
                        $presidentApproval = \App\Models\User::PENDING_REQUEST;
                        if($updateRequestUser->approved_by_president == true){
                        $presidentApproval = \App\Models\User::APPROVED_REQUEST;
                        } elseif ($updateRequestUser->approved_by_president == false && $updateRequestUser->approved_by_president !== NULL) {
                        $presidentApproval = \App\Models\User::REJECTED_REQUEST;
                        }
                        @endphp
                        <td> {{ $presidentApproval }} </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <!-- view -->
                                <a class="pl-3 color-unset" href="{{ route('user.view-update-request', $updateRequestUser->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <!-- delete -->
                                <form action="{{ route('user.delete-update-request', $updateRequestUser->id) }}" method="POST">
                                    @csrf
                                    <a class="pl-3 delete-request color-unset" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3 mr-3">
            <!-- Add pagination links -->
            {{ $updateRequests->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->

<!-- </div> -->
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // click event listener to all delete buttons with the class 'delete-request'
        document.querySelectorAll('.delete-request').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Show the confirmation dialog
                if (confirm('Are you sure you want to delete this request?')) {
                    // If the request confirms, submit the nearest form
                    this.closest('form').submit();
                }
            });
        });
    });
</script>
@endsection