@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Employees /</span> Attendance Details</h4>

<?php
$employeeDetails = \App\Models\User::where('id', $employee->user_id)->first();
?>

<div class="card mb-1">
    <h5 class="card-header">Employee Details</h5>
    <div class="row">
        <div class="col-12">
            <div class="card col-12">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <label for=""> Employee Name: </label> <span> {{ $employeeDetails->fullname }} </span>
                    </li>
                    <li class="list-group-item">
                        <label for=""> Actual Salary: </label> <span> ₹{{ $employee->salary }} </span>
                    </li>
                    <li class="list-group-item">
                        <label for=""> ESI Contribution: </label> <span> ₹{{ ($employee->esi_contribution) ? $employee->esi_contribution : 0 }} </span>
                    </li>
                    <li class="list-group-item">
                        <label for=""> Attendance Start Date: </label> <span> {{ ($startDate) ? \Carbon\Carbon::parse($startDate)->format('d-M-Y') : NULL }} </span>
                    </li>
                    <li class="list-group-item">
                        <label for=""> Attendance End Date: </label> <span> {{ ($endDate) ? \Carbon\Carbon::parse($endDate)->format('d-M-Y') : NULL }} </span>
                    </li>
                    <li class="list-group-item">
                        <label for=""> Total Attendance: </label> <span> {{ $employee->total_attendance }} </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Striped Rows -->

<div class="card mb-4">
    <div class="row">
        <div class="col-md-10">
            <h5 class="card-header">Attendance Details</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6"></div>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Date of Attendance</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php($i = 1)
                    @foreach($attendances as $attendance)
                    <tr>
                        <td> {{ $i }} </td>
                        <td> {{ $attendance->date ? \Carbon\Carbon::parse($attendance->date)->format('d-M-Y') : '--' }} </td>
                        <td> {{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : '--' }} </td>
                        <td> {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : '--' }} </td>
                    </tr>
                    @php($i++)
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>

<!-- show image modal -->
<div class="modal fade" id="showImage" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img src="" id="showImageSrc" alt="Description of Image" style="max-width: 750px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<!-- show image modal -->
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('.show-image').click(function() {
            let imgSrc = $(this).attr('img-src');
            $('#showImageSrc').attr('src', imgSrc);
            $('#showImage').modal('show');
        });
    });
</script>
@endsection