@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pending Rents</span></h4>
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@php
$currentDate = \Carbon\Carbon::now();
@endphp
<div class="card mb-4">
    <h5 class="card-header">Filters</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('rents.pending-rents') }}">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-md-3">
                    <label for="userId" class="form-label">Vendor Name</label>
                    <div class="input-group">
                        <select id="vendor" name="userId" class="select2 form-select">
                            <option value="">Select Vendor</option>
                            @foreach($activeVendors as $ky => $vendor)
                            <option value="{{$ky}}" @if(@$_GET['userId']==$ky) selected @endif>{{$vendor['full_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="rentAmount" class="form-label">Amount</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="rentAmount" value="{{@$_GET['rentAmount']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="renewalDate" class="form-label">Renewal Date</label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="renewalDate" value="{{@$_GET['renewalDate']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="endDate" class="form-label">Expiring Date</label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="endDate" value="{{@$_GET['endDate']}}">
                    </div>
                </div>

                <div class="col-md-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <button class="btn btn-primary">Filter</button>
                </div>

                <div class="col-md-1 ml-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <a href="{{ route('rents.pending-rents') }}" class="btn btn-secondary">Reset</a>
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
                    <th>Vendor Name</th>
                    <th>Amount</th>
                    <th>Renewal Date</th>
                    <th>Expiring Date</th>
                    <th>Expiring Days</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($expiredRents as $rent)
                <tr>
                    <td> {{ $i }} </td>
                    <td> {{ $activeVendors[$rent['user_id']]['full_name'] }} </td>
                    <td>₹{{ $rent['rent_amount'] }} </td>
                    <td>{{ \Carbon\Carbon::parse($rent['renewal_date'])->format('d-M-Y') }} </td>
                    <td>{{ \Carbon\Carbon::parse($rent['end_date'])->format('d-M-Y') }} </td>
                    <td>
                        <?php
                        $endDate = \Carbon\Carbon::parse($rent['end_date']);
                        $diffInMonths = $currentDate->diffInMonths($endDate, false);
                        $remainingDays = $currentDate->copy()->addMonths($diffInMonths)->diffInDays($endDate, false);

                        $pendingTime = '';

                        if ($diffInMonths > 0) {
                            $pendingTime .= $diffInMonths . ' months ';
                        }

                        if ($remainingDays > 0) {
                            $pendingTime .= $remainingDays . ' days';
                        }

                        if (empty($pendingTime)) {
                            $pendingTime = 'Expired'; // Handle case where there is no time difference
                        }
                        ?>

                        {{ $pendingTime }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <!-- view -->
                            <a class="pl-3 color-unset" data-bs-toggle="modal" data-bs-target="#modalCenter{{$rent['id']}}" href="#"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <div class="modal fade" id="modalCenter{{$rent['id']}}" tabindex="-1" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">Rent details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="lawyerName" class="form-label">Vendor Name:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $activeVendors[$rent['user_id']]['full_name'] }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="Amount" class="form-label">Amount:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    ₹{{ $rent['rent_amount'] }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="rentDate" class="form-label">Rent Renewal Date:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ \Carbon\Carbon::parse($rent['renewal_date'])->format('d-M-Y') }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="rentDate" class="form-label">End Date:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ \Carbon\Carbon::parse($rent['end_date'])->format('d-M-Y') }}
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
            {{ $expiredRents->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->
@endsection