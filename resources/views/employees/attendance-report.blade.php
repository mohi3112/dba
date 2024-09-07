@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Employees /</span> Attendance Report</h4>
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="card mb-4">
    <h5 class="card-header">Filters</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('employees.attendance-report') }}">
            <div class="row gx-3 gy-2 align-items-center">

                <div class="col-md-3">
                    <label for="employeeName" class="form-label">Name</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="employeeName" value="{{@$_GET['employeeName']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="startOfMonth" class="form-label">Start Date</label>
                    <input class="form-control @error('startOfMonth') is-invalid @enderror" type="date" id="startOfMonth" placeholder="Start Date" name="startOfMonth" value="{{ @$_GET['startOfMonth'] ?? \Carbon\Carbon::now()->subDays(30)->toDateString() }}" autofocus="">
                    @error('startOfMonth')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="endOfMonth" class="form-label">End Date</label>
                    <input class="form-control @error('endOfMonth') is-invalid @enderror" type="date" id="endOfMonth" placeholder="End Date" name="endOfMonth" value="{{ @$_GET['endOfMonth'] ?? \Carbon\Carbon::now()->toDateString() }}" autofocus="">
                    @error('endOfMonth')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="col-md-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <button class="btn btn-primary">Filter</button>
                </div>

                <div class="col-md-1 ml-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <a href="{{ route('employees.attendance-report') }}" class="btn btn-secondary">Reset</a>
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
                    <th>Total Attendance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($attendances as $attendance)
                <tr>
                    <td> {{ $i }} </td>
                    <td> {{ $attendance->employee->name }} </td>
                    <td> {{ ($attendance->employee->dob) ? \Carbon\Carbon::parse($attendance->employee->dob)->format('d-M-Y') : '--' }} </td>
                    <td> {{ ($attendance->employee->gender) ? \App\Models\Employee::$employeesGender[$attendance->employee->gender] : '--' }} </td>
                    <td> {{ $attendance->employee->phone ?? '--' }} </td>
                    <td> {{ $attendance->employee->position ?? '--' }} </td>
                    <td> {{ $attendance->total_attendance ?? 0 }} </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <!-- view -->
                            <a class="color-unset" href="/attendance-details/{{ $attendance->employee->id }}?startDate={{ @$_GET['startOfMonth'] ?? \Carbon\Carbon::now()->subDays(30)->toDateString() }}&endDate={{ @$_GET['endOfMonth'] ?? \Carbon\Carbon::now()->toDateString() }}">View Details</a>
                        </div>
                    </td>
                </tr>
                @php($i++)
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3 mr-3">
            {{ $attendances->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->
@endsection