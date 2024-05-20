@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Lawyers /</span> Lawyer Details</h4>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <h5 class="card-header">Lawyer Details</h5>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 mb-4 mb-xl-0">
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-3 col-12 mb-3 mb-md-0">
                                <div class="list-group">
                                    <a class="list-group-item list-group-item-action active" id="list-basic" data-bs-toggle="list" href="#basic-details">Personal Information</a>
                                    <a class="list-group-item list-group-item-action" id="list-uploaded-document" data-bs-toggle="list" href="#uploaded-document">Uploaded Documents</a>
                                    <a class="list-group-item list-group-item-action" id="list-subscriptions" data-bs-toggle="list" href="#all-subscriptions">All subscriptions</a>
                                    <!-- <a class="list-group-item list-group-item-action" id="list-settings-list" data-bs-toggle="list" href="#list-settings">Settings</a> -->
                                </div>
                            </div>
                            <div class="col-md-9 col-12">
                                <div class="tab-content p-0">
                                    <div class="tab-pane fade active show" id="basic-details">
                                        <div class="row">
                                            <div class="col-6">
                                                <h6 class="mt-2 text-muted">Basic Information</h6>
                                                <div class="card col-12">
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item">
                                                            <label for=""> Name: </label> <span> {{ $user->fullname }} </span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <label for=""> Fathers' Name: </label> <span> {{ ($user->father_first_name) ? $user->father_first_name . ' ' . $user->father_last_name : '' }} </span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <label for=""> Email: </label> <span> {{ $user->email }} </span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <label for=""> Gender: </label> <span> {{ \App\Models\User::$genders[$user->gender] }} </span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <label for=""> Aadhaar number: </label> <span> {{ $user->aadhaar_no }} </span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <label for=""> Mobile: </label> <span> {{ $user->mobile1 }} {{ ($user->mobile2) ? ' ( ' . $user->mobile2 . ' )' : '' }}</span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <label for=""> Residence Address: </label> <span> {{ $user->address ?: '' }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <h6 class="mt-2 text-muted">Other Information</h6>
                                                <div class="card col-12">
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item">
                                                            <label for=""> Status: </label> <span>
                                                                @if($user->status == 1)
                                                                <span class="badge bg-label-success me-1">{{ \App\Models\User::$statuses[$user->status] }}</span>
                                                                @elseif($user->status == 2)
                                                                <span class="badge bg-label-warning me-1">{{ \App\Models\User::$statuses[$user->status] }}</span>
                                                                @endif
                                                            </span>
                                                        </li>
                                                        @if(auth()->user()->hasRole('superadmin'))
                                                        <li class="list-group-item">
                                                            <label for=""> Role: </label> <span> {{ \App\Models\User::$userRoles[$user->roles->first()->pivot->role_id] }} </span>
                                                        </li>
                                                        @endif
                                                        <li class="list-group-item">
                                                            <label for=""> Designation: </label> <span> {{ ($user->designation) ? \App\Models\User::$designations[$user->designation] : '' }} </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="uploaded-document">
                                        <div class="row mb-5">
                                            <div class="col-md">
                                                <div class="card mb-3">
                                                    <div class="row g-0">
                                                        @php($dataFound = false)
                                                        @if($user->picture)
                                                        <div class="col-md-4">
                                                            <img class="card-img card-img-left" src="data:image/jpeg;base64,{{ $user->picture }}" alt="Description of Image" style="max-width: 250px; max-height: 250px;">
                                                        </div>
                                                        @php($dataFound = true)
                                                        @endif
                                                        @if($user->address_proof->count() > 0)
                                                        @foreach($user->address_proof as $proof)
                                                        <div class="col-md-4">
                                                            <img class="card-img card-img-left" src="data:image/jpeg;base64,{{ $proof->image }}" alt="Description of Image" style="max-width: 250px; max-height: 250px;">
                                                        </div>
                                                        @endforeach
                                                        @php($dataFound = true)
                                                        @endif
                                                        @if($user->degree_images->count() > 0)
                                                        @foreach($user->degree_images as $proof)
                                                        <div class="col-md-4">
                                                            <img class="card-img card-img-left" src="data:image/jpeg;base64,{{ $proof->image }}" alt="Description of Image" style="max-width: 250px; max-height: 250px;">
                                                        </div>
                                                        @endforeach
                                                        @php($dataFound = true)
                                                        @endif
                                                        @if(!$dataFound)
                                                        <h6 class="mt-2 text-muted">No Data Found</h6>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="all-subscriptions">
                                        <div class="col-12">
                                            <div class="card col-12">
                                                <div class="table-responsive text-nowrap">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr. No.</th>
                                                                <th>Subscription Type</th>
                                                                <th>Start Date</th>
                                                                <th>End Date</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-border-bottom-0">
                                                            @if($user->subscriptions->count() > 0)
                                                            @php($i = 1)
                                                            @foreach($user->subscriptions as $subscription)
                                                            <?php
                                                            // Parse dates as Carbon instances
                                                            $startDate = \Carbon\Carbon::parse($subscription->start_date);
                                                            $endDate = \Carbon\Carbon::parse($subscription->end_date);
                                                            $today = \Carbon\Carbon::today();

                                                            $status = $class = "";
                                                            if ($startDate <= $today && $endDate > $today) {
                                                                $status = 'Active';
                                                                $class = 'bg-label-success';
                                                            } elseif ($endDate < $today) {
                                                                $status = 'Expired';
                                                                $class = 'bg-label-warning';
                                                            } else {
                                                                $status = 'Scheduled';
                                                                $class = 'bg-label-info';
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td> {{ $i }} </td>
                                                                <td> {{ \App\Models\Subscription::$subscriptionTypes[$subscription->subscription_type] }} </td>
                                                                <td> {{ \Carbon\Carbon::parse($subscription->start_date)->format('d-M-Y') }} </td>
                                                                <td> {{ \Carbon\Carbon::parse($subscription->end_date)->format('d-M-Y') }} </td>
                                                                <td> <span class='badge {{ $class }} me-1'>{{ $status }}</span> </td>

                                                            </tr>
                                                            @php($i++)
                                                            @endforeach
                                                            @else
                                                            <tr>
                                                                <td colspan="5">No Data Found</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                    <div class="d-flex justify-content-end pt-3"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="tab-pane fade" id="list-settings"></div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
@endsection