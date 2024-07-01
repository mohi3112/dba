@extends('layouts.app')
@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Lawyers</span></h4>
<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="{{route('users.add')}}"><i class="bx bx-user me-1"></i> Add Lawyer</a>
    </li>
</ul>
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="card mb-4">
    <h5 class="card-header">Filters</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('users') }}">
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
                    <label for="licenceNo" class="form-label">Licence No</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="licenceNo" value="{{@$_GET['licenceNo']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="designation" class="form-label">Designation</label>
                    <select id="designation" name="designation" class="select2 form-select">
                        <option value="">Select Designation</option>
                        @foreach(\App\Models\User::$designationRoles as $key => $designation)
                        <?php if ($key == \App\Models\User::DESIGNATION_VENDOR) continue; ?>
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

                <div class="col-md-2">
                    <div class="form-check form-switch" style="margin-top: 25%;">
                        <label class="form-label" for="showToastPlacement">&nbsp;</label>
                        <input class="form-check-input" name="is_active" type="checkbox" id="flexSwitchCheckChecked" {{ (count($_GET) > 0 && !isset($_GET['is_active'])) ? '' : 'checked' }}>
                        <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
                    </div>
                </div>

                @if (auth()->user()->hasRole('president'))
                <div class="col-md-2">
                    <div class="form-check form-switch" style="margin-top: 25%;">
                        <label class="form-label" for="showToastPlacement">&nbsp;</label>
                        <input class="form-check-input" name="is_deceased" type="checkbox" id="flexSwitchCheckDeceased" {{ (count($_GET) > 0 && isset($_GET['is_deceased'])) ? "checked" : "" }}>
                        <label class="form-check-label" for="flexSwitchCheckDeceased">Deceased</label>
                    </div>
                </div>
                @endif

                <div class="col-md-2">
                    <div class="form-check form-switch" style="margin-top: 25%;">
                        <label class="form-label" for="showToastPlacement">&nbsp;</label>
                        <input class="form-check-input" name="is_physically_disabled" type="checkbox" id="flexSwitchCheckPhysicallyDisabled" {{ (count($_GET) > 0 && isset($_GET['is_physically_disabled'])) ? "checked" : "" }}>
                        <label class="form-check-label" for="flexSwitchCheckPhysicallyDisabled">Physically Disabled</label>
                    </div>
                </div>

                <div class="col-md-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <button class="btn btn-primary">Filter</button>
                </div>

                <div class="col-md-1 ml-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <a href="{{ route('users') }}" class="btn btn-secondary">Reset</a>
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
                    <th>Licence No</th>
                    <th>DESIGNATION</th>
                    <th>Status</th>
                    @if (auth()->user()->hasRole('president'))
                    <th>Deceased</th>
                    @endif
                    <th>Handicaped</th>
                    <th>Is Approved</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php
                $i = 1;
                foreach ($users as $user) {
                ?>
                    <tr>
                        <td> {{ $i }} </td>
                        <td> {{ $user->full_name }} </td>
                        <td> {{ ($user->father_first_name) ? $user->father_first_name . ' ' . $user->father_last_name : '--' }}</td>
                        <td> {{ \Carbon\Carbon::parse($user->dob)->format('d-M-Y') . ' (' . $user->age . ')' }}</td>
                        <td> {{ ($user->gender) ? \App\Models\User::$genders[$user->gender] : '--' }} </td>
                        <td>
                            {{ $user->licence_no ?? '--' }}
                        </td>
                        <td>
                            {{ \App\Models\User::$designationRoles[$user->designation] ?? '--' }}
                        </td>
                        <td>
                            @if($user->status == 1)
                            <span class="badge bg-label-success me-1">{{ \App\Models\User::$statuses[$user->status] }}</span>
                            @elseif($user->status == 2)
                            <span class="badge bg-label-warning me-1">{{ \App\Models\User::$statuses[$user->status] }}</span>
                            @endif
                        </td>
                        @if (auth()->user()->hasRole('president'))
                        <td> {{ ($user->is_deceased) ? 'Yes' : 'No' }} </td>
                        @endif
                        <td> {{ ($user->is_physically_disabled) ? 'Yes' : 'No' }} </td>
                        <td>
                            @if($user->account_approved)
                            <span class="badge bg-label-success me-1">Yes</span>
                            @else
                            <span class="badge bg-label-warning me-1">No</span>
                            @endif
                        <td>
                            <div class="d-flex align-items-center">
                                <!-- edit -->
                                <a class="color-unset" href="{{ route('users.edit', $user->id) }}"><i class="fas fa-edit"></i></a>
                                <!-- view -->
                                <a class="pl-3 color-unset" href="{{ route('user.view', $user->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <!-- delete -->
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    <a class="pl-3 delete-user color-unset" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
            {{ $users->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->

<!-- </div> -->
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // click event listener to all delete buttons with the class 'delete-user'
        document.querySelectorAll('.delete-user').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Show the confirmation dialog
                if (confirm('Are you sure you want to delete this user?')) {
                    // If the user confirms, submit the nearest form
                    this.closest('form').submit();
                }
            });
        });
    });
</script>
@endsection