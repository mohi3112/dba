@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Employees /</span> Attendance Report</h4>
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<?php
$publicHolidays = $_GET['publicHolidays'] ?? 0;
$calculateSalary = (count($_GET) > 0 && isset($_GET['calculate_salary'])) ? true : false;
$totalSalaries = 0;
?>
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

                <div class="col-md-3">
                    <label for="publicHolidays" class="form-label">Public Holidays</label>
                    <div class="input-group">
                        <input type="number" min="0" class="form-control" name="publicHolidays" value="{{@$_GET['publicHolidays']}}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-check form-switch" style="margin-top: 25%;">
                        <label class="form-label" for="showToastPlacement">&nbsp;</label>
                        <input class="form-check-input" name="calculate_salary" type="checkbox" id="flexSwitchCheckCalculateSalary" {{ (count($_GET) > 0 && isset($_GET['calculate_salary'])) ? "checked" : "" }}>
                        <label class="form-check-label" for="flexSwitchCheckChecked">Calculate Salary</label>
                    </div>
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
    <div class="d-flex" style="flex-direction: row-reverse;">
        <span>&nbsp;Enter the number of public holidays in a month to calculate the salary.</span>
        <span style="color:red">*Note:</span>
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
                    @if($calculateSalary)
                    <th>Calculated Salary</th>
                    @endif
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($attendances as $attendance)
                <?php
                $user = \App\Models\User::with('employees')->find($attendance->user_id);
                ?>
                <tr>
                    <td> {{ $i }} </td>
                    <td> {{ $user->first_name ?? '--' }} </td>
                    <td> {{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d-M-Y') : '--' }} </td>
                    <td> {{ $user->gender ? \App\Models\Employee::$employeesGender[$user->gender] : '--' }} </td>
                    <td> {{ $user->mobile1 ?? '--' }} </td>
                    <td> {{ $user->employees->position ?? '--' }} </td>
                    <td> {{ $attendance->total_attendance ?? 0 }} </td>
                    @if($calculateSalary)
                    <?php
                    $salary = (int) $user->employees->salary ?? 0;
                    $totalAttendance = (int) $attendance->total_attendance ?? 0;
                    // $esi_contribution = $user->employees->esi_contribution;
                    $allowedPaidLeaves = 2 + (int) $publicHolidays;
                    $presentDays =  $totalAttendance ?? 0 + $allowedPaidLeaves;
                    if ($presentDays < 30) {
                        $monthlySalary = $salary / 30 * $totalAttendance;
                    } else {
                        $monthlySalary = $salary;
                    }
                    $totalSalaries += $monthlySalary;
                    ?>
                    <td>₹{{ number_format($monthlySalary, 2) }}</td>
                    @endif
                    <td>
                        <div class="d-flex align-items-center">
                            <!-- view -->
                            <a class="color-unset" href="{{route('employees.attendance-details', $user->employees->id)}}?startDate={{ @$_GET['startOfMonth'] ?? \Carbon\Carbon::now()->subDays(30)->toDateString() }}&endDate={{ @$_GET['endOfMonth'] ?? \Carbon\Carbon::now()->toDateString() }}">View Details</a>
                        </div>
                    </td>
                </tr>
                @php($i++)
                @endforeach
            </tbody>
        </table>
        @if($calculateSalary && $totalSalaries > 0)
        <div class="m-3 d-flex justify-content-end" style="color: #5F61E6;"> Total Amount: ₹{{ number_format($totalSalaries, 2) }} </div>
        @endif
        <div class="d-flex justify-content-end pt-3 mr-3">
            {{ $attendances->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->
@endsection