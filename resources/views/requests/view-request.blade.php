@extends('layouts.app')
@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Other /</span> Update Request</h4>
<div class="row">
    @if(auth()->user()->hasRole('secretary') || auth()->user()->hasRole('finance_secretary') || auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('president'))
    <div class="col-md-12 p-0">
        <div class="card-body">
            @php($disabled="")
            @if($request->approved_by_president !== NULL)
            @php($disabled="disabled")
            @else
            @if((auth()->user()->hasRole('secretary') || auth()->user()->hasRole('finance_secretary')) && $request->approved_by_secretary !== NULL)
            @php($disabled="disabled")
            @endif
            @endif

            <div class="demo-inline-spacing d-flex" style="flex-direction: row-reverse;">
                <button type="button" class="btn rounded-pill btn-danger action-request-btn" data-id="{{$request->id}}" {{$disabled}} data-approved="0">Decline</button>
                <button type="button" class="btn rounded-pill btn-success action-request-btn" data-id="{{$request->id}}" {{$disabled}} data-approved="1">Approve</button>
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
                    @if($request->table_name == 'locations')
                    <tr>
                        <td> Shop Number:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->location->shop_number ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td> Floor Number:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->location->floor_number ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td> Complex:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->location->complex ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td> Rent:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->location->rent ?? '--' }}</h5>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg">
        <div class="card mb-4">
            <h5 class="card-header">Updated Profile Information
                <small class="text-muted ms-1"> ( Request Type:
                    @if($request->action == \App\Models\ModificationRequest::REQUEST_TYPE_UPDATE)
                    <span class="badge bg-label-warning me-1">Update</span>
                    @elseif($request->action == \App\Models\ModificationRequest::REQUEST_TYPE_DELETE)
                    <span class="badge bg-label-danger me-1">Delete</span>
                    @endif)
                </small>
            </h5>
            <table class="table table-borderless">
                <tbody>
                    @if($request->table_name == 'locations')
                    <tr>
                        <td> Shop Number:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['shop_number'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td> Floor Number:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['floor_number'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td> Complex:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['complex'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td> Rent:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['rent'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
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
                    url: '{{ route("request.approveRequest") }}',
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}', // Laravel CSRF token
                        request_id: recordId,
                        is_approved: fieldValue
                    },
                    success: function(response) {
                        alert('Request updated successfully!');

                        if (response.success && response.redirect_url) {
                            window.location.href = response.redirect_url;
                        } else {
                            location.reload();
                        }
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