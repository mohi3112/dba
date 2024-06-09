@extends('layouts.app')
@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Lawyers /</span> Update Requests</h4>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        @if(auth()->user()->hasRole('secretary') || auth()->user()->hasRole('finance_secretary') || auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('president'))
        <div class="col-md-12">
            <div class="card-body">
                @php($disabled="")
                @if($updateRequest->approved_by_president !== NULL)
                @php($disabled="disabled")
                @else
                @if((auth()->user()->hasRole('secretary') || auth()->user()->hasRole('finance_secretary')) && $updateRequest->approved_by_secretary !== NULL)
                @php($disabled="disabled")
                @endif
                @endif

                <div class="demo-inline-spacing d-flex" style="flex-direction: row-reverse;">
                    <button type="button" class="btn rounded-pill btn-danger action-request-btn" data-id="{{$updateRequest->id}}" {{$disabled}} data-approved="0">Decline</button>
                    <button type="button" class="btn rounded-pill btn-success action-request-btn" data-id="{{$updateRequest->id}}" {{$disabled}} data-approved="1">Approve</button>
                </div>
            </div>
        </div>
        @endif
        <div class="col-lg">
            <div class="card mb-4">
                <h5 class="card-header">Existing Profile Information
                    <!-- <small class="text-muted ms-1">Default</small> -->
                </h5>
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td> Name:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ $updateRequest->user->fullname }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> Fathers' Name:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ ($updateRequest->user->father_first_name) ? $updateRequest->user->father_first_name . ' ' . $updateRequest->user->father_last_name : '' }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> Email:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ $updateRequest->user->email }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> Gender:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ \App\Models\User::$genders[$updateRequest->user->gender] }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> DOB (Age):</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ ($updateRequest->user->dob) ? \Carbon\Carbon::parse($updateRequest->user->dob)->format('d-M-Y') . ' (' . $updateRequest->user->age . ')' : '' }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> Aadhaar number:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ $updateRequest->user->aadhaar_no }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> Mobile:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ $updateRequest->user->mobile1 }} {{ ($updateRequest->user->mobile2) ? ' ( ' . $updateRequest->user->mobile2 . ' )' : '' }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> Residence Address:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ $updateRequest->user->address ?: '' }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> Status:</td>
                            <td class="py-3">
                                <h6 class="mb-0">
                                    @if($updateRequest->user->status == 1)
                                    <span class="badge bg-label-success me-1">{{ \App\Models\User::$statuses[$updateRequest->user->status] }}</span>
                                    @elseif($updateRequest->user->status == 2)
                                    <span class="badge bg-label-warning me-1">{{ \App\Models\User::$statuses[$updateRequest->user->status] }}</span>
                                    @endif
                                </h6>
                            </td>
                        </tr>
                        <tr>
                            <td> Deceased:</td>
                            <td class="py-3">
                                <h6 class="mb-0">
                                    @if(!$updateRequest->user->is_deceased)
                                    <span class="badge bg-label-success me-1">No</span>
                                    @else
                                    <span class="badge bg-label-warning me-1">Yes</span>
                                    @endif
                                </h6>
                            </td>
                        </tr>
                        <tr>
                            <td> Physically Disabled:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ (!$updateRequest->user->is_physically_disabled) ? 'No' : 'Yes' }}</h5>
                            </td>
                        </tr>
                        @if(auth()->user()->hasRole('superadmin'))
                        <tr>
                            <td> Role:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ \App\Models\User::$designationRoles[$updateRequest->user->roles->first()->pivot->role_id] ?? '' }}</h5>
                            </td>
                        </tr>
                        @endif

                        <tr>
                            <td> Designation:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ ($updateRequest->user->designation) ? \App\Models\User::$designationRoles[$updateRequest->user->designation] : '' }}</h5>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg">
            <div class="card mb-4">
                <h5 class="card-header">Updated Profile Information</h5>
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td> Name:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ $updateRequest->fullname }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> Fathers' Name:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ ($updateRequest->father_first_name) ? $updateRequest->father_first_name . ' ' . $updateRequest->father_last_name : '' }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> Email:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ $updateRequest->email }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> Gender:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ \App\Models\User::$genders[$updateRequest->gender] }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> DOB (Age):</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ ($updateRequest->dob) ? \Carbon\Carbon::parse($updateRequest->dob)->format('d-M-Y') . ' (' . $updateRequest->age . ')' : '' }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> Aadhaar number:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ $updateRequest->aadhaar_no }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> Mobile:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ $updateRequest->mobile1 }} {{ ($updateRequest->mobile2) ? ' ( ' . $updateRequest->mobile2 . ' )' : '' }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> Residence Address:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ $updateRequest->address ?: '' }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td> Status:</td>
                            <td class="py-3">
                                <h6 class="mb-0">
                                    @if($updateRequest->status == 1)
                                    <span class="badge bg-label-success me-1">{{ \App\Models\User::$statuses[$updateRequest->status] }}</span>
                                    @elseif($updateRequest->status == 2)
                                    <span class="badge bg-label-warning me-1">{{ \App\Models\User::$statuses[$updateRequest->status] }}</span>
                                    @endif
                                </h6>
                            </td>
                        </tr>
                        <tr>
                            <td> Deceased:</td>
                            <td class="py-3">
                                <h6 class="mb-0">
                                    @if(!$updateRequest->is_deceased)
                                    <span class="badge bg-label-success me-1">No</span>
                                    @else
                                    <span class="badge bg-label-warning me-1">Yes</span>
                                    @endif
                                </h6>
                            </td>
                        </tr>
                        <tr>
                            <td> Physically Disabled:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ (!$updateRequest->is_physically_disabled) ? 'No' : 'Yes' }}</h5>
                            </td>
                        </tr>
                        @if(auth()->user()->hasRole('superadmin'))
                        <tr>
                            <td> Role:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ \App\Models\User::$designationRoles[$updateRequest->designation] ?? '' }}</h5>
                            </td>
                        </tr>
                        @endif

                        <tr>
                            <td> Designation:</td>
                            <td class="py-3">
                                <h5 class="mb-0">{{ ($updateRequest->designation) ? \App\Models\User::$designationRoles[$updateRequest->designation] : '' }}</h5>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('.action-request-btn').on('click', function() {
            let fieldValue = $(this).data('approved');
            let recordId = $(this).data('id');

            var btnValue = "approve";
            if (fieldValue == 0) {
                btnValue = "decline";
            }

            if (confirm('Are you sure to ' + btnValue + ' the request?')) {
                $.ajax({
                    url: '{{ route("user.approveRequest") }}',
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}', // Laravel CSRF token
                        request_id: recordId,
                        is_approved: fieldValue
                    },
                    success: function(response) {
                        alert('Request approved successfully!');
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error approving request.');
                    }
                });
            }
        });
    });
</script>
@endsection