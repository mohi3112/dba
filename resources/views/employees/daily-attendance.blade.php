@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Employees /</span> Attendance</h4>
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@php
$disable = '';
if(!auth()->user()->hasRole('president')) {
if(isset($_GET['attendanceDate']) && !empty($_GET['attendanceDate'])) {
$filterDate = \Carbon\Carbon::createFromFormat('Y-m-d', $_GET['attendanceDate']);
if ($filterDate->lt(\Carbon\Carbon::today())) {
$disable = 'disabled';
}
}
}
@endphp

<div class="card mb-4">
    <h5 class="card-header">Filters</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('employees.daily-attendance') }}">
            <div class="row gx-3 gy-2 align-items-center">

                <div class="col-md-3">
                    <label for="employeeName" class="form-label">Name</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="employeeName" value="{{@$_GET['employeeName']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select id="gender" name="employeeGender" class="select2 form-select">
                        <option value="">Select Gender</option>
                        @foreach(\App\Models\Employee::$employeesGender as $ky => $gender)
                        <option value="{{$ky}}" @if(@$_GET['employeeGender']==$ky) selected @endif>{{$gender}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="employeeEmail" class="form-label">Email</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="employeeEmail" value="{{@$_GET['employeeEmail']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="employeePhone" class="form-label">Phone</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="employeePhone" value="{{@$_GET['employeePhone']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="employeePosition" class="form-label">Position</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="employeePosition" value="{{@$_GET['employeePosition']}}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="attendanceDate" class="form-label">Attendance Date</label>
                    <div class="input-group">
                        <input type="date" class="form-control" id="attendanceDate" name="attendanceDate" value="{{ $_GET['attendanceDate'] ?? date('Y-m-d') }}">
                    </div>
                </div>

                <div class="col-md-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <button class="btn btn-primary">Filter</button>
                </div>

                <div class="col-md-1 ml-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <a href="{{ route('employees.daily-attendance') }}" class="btn btn-secondary">Reset</a>
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
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Position</th>
                    <th>Attendance</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($employees as $employee)
                <tr>
                    <td> {{ $i }} </td>
                    <td> <a href="{{ route('employee.edit', $employee->id) }}">{{ $employee->name }}</a> </td>
                    <td> {{ ($employee->dob) ? \Carbon\Carbon::parse($employee->dob)->format('d-M-Y') : '--' }} </td>
                    <td> {{ ($employee->gender) ? \App\Models\Employee::$employeesGender[$employee->gender] : '--' }} </td>
                    <td> {{ $employee->phone ?? '--' }} </td>
                    <td> {{ $employee->position ?? '--' }} </td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($employee->check_in && $employee->check_in != null)
                            <button type="button" class="btn rounded-pill btn-secondary" disabled>In</button>
                            @if($employee->check_out && $employee->check_out != null)
                            <button type="button" class="btn rounded-pill btn-secondary" disabled>Out</button>
                            @else
                            <button type="button" class="btn rounded-pill btn-danger mark-attendance ml-2" {{$disable}} data-attendance-type="out" data-date="{{ $_GET['attendanceDate'] ?? date('Y-m-d') }}" data-employee-id="{{ $employee->id }}">Out</button>
                            @endif
                            @else
                            <button type="button" class="btn rounded-pill btn-success mark-attendance" {{$disable}} data-attendance-type="in" data-date="{{ $_GET['attendanceDate'] ?? date('Y-m-d') }}" data-employee-id="{{ $employee->id }}">In</button>
                            @endif
                            <!-- view -->
                            <a class="pl-3 color-unset" data-bs-toggle="modal" data-bs-target="#modalCenter{{$employee->id}}" href="#"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <div class="modal fade" id="modalCenter{{$employee->id}}" tabindex="-1" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">Employee details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="name" class="form-label">Name:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    {{ $employee->name }}

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="nameWithTitle" class="form-label">Date of Birth:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    {{ ($employee->dob) ? \Carbon\Carbon::parse($employee->dob)->format('d-M-Y') : '--' }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="gender" class="form-label">Gender:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    {{ ($employee->gender) ? \App\Models\Employee::$employeesGender[$employee->gender] : '--' }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="email" class="form-label">Email:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    {{ $employee->email ?? '--' }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="phone" class="form-label">Phone:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    {{ $employee->phone ?? '--' }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="nameWithTitle" class="form-label">Position:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    {{ $employee->position ?? '--' }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="nameWithTitle" class="form-label">salary:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    ₹{{ $employee->salary }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @php($i++)
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3 mr-3">
            {{ $employees->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // click event listener to all delete buttons with the class 'delete-employee'
        document.querySelectorAll('.delete-employee').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Show the confirmation dialog
                if (confirm('Are you sure you want to delete this record?')) {
                    // If the user confirms, submit the nearest form
                    this.closest('form').submit();
                }
            });
        });

        const dateInput = document.getElementById("attendanceDate");
        const currentDate = new Date().toISOString().split('T')[0];
        // Set the default date to today if no query parameter is provided
        if (!dateInput.value) {
            dateInput.value = currentDate;
        }
        // Disable future dates
        dateInput.max = currentDate;


        $('.mark-attendance').on('click', function() {
            var employeeId = $(this).data('employee-id');
            var attendanceType = $(this).data('attendance-type');
            var attendanceDate = $(this).data('date');
            var token = '{{ csrf_token() }}';

            $.ajax({
                url: '{{ route("employees.mark-attendance") }}',
                type: 'PUT',
                data: {
                    _token: token,
                    employee_id: employeeId,
                    attendance_date: attendanceDate,
                    attendance_type: attendanceType
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                    // Optionally, update the UI to reflect the check-in status
                },
                error: function(response) {
                    alert('Error: ' + response.responseJSON.message);
                }
            });
        });
    });
</script>
@endsection