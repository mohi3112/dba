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
                    @if($request->table_name == 'books_categories')
                    <tr>
                        <td>Category Name:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->bookCategory->category_name ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Published Volumes:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->bookCategory->published_volumes ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Published Total Volumes:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->bookCategory->published_total_volumes ?? '--' }}</h5>
                        </td>
                    </tr>
                    @endif
                    @if($request->table_name == 'books')
                    <tr>
                        <td>Category Name:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $categories[$request->book->book_category_id] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Book Name:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->book->book_name ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Author Name:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->book->book_author_name ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Price:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->book->price ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Book Volume:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->book->book_volume ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Book Licence:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->book->book_licence ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Licence Valid Upto:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->book->book_licence_valid_upto) ? \Carbon\Carbon::parse($request->book->book_licence_valid_upto)->format('d-M-Y') :    '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Publish Date:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->book->publish_date ? \Carbon\Carbon::parse($request->book->publish_date)->format('d-M-Y') : '--' }}</h5>
                        </td>
                    </tr>
                    <?php
                    if ($request->book->available == 1) {
                        $available = 'Yes';
                        $class = 'bg-label-success';
                    } else {
                        $available = 'No';
                        $class = 'bg-label-warning';
                    }
                    ?>
                    <tr>
                        <td>Available:</td>
                        <td class="py-3">
                            <h5 class="mb-0"><span class='badge {{ $class }} me-1'>{{ $available }}</span></h5>
                        </td>
                    </tr>
                    @endif
                    @if($request->table_name == 'payments')
                    <tr>
                        <td>Lawyer:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->payment->user_id) ? $activeLawyers[$request->payment->user_id] : '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Amount:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->payment->payment_amount ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Payment Date:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->payment->payment_date) ? \Carbon\Carbon::parse($request->payment->payment_date)->format('d-M-Y') : '--' }}</h5>
                        </td>
                    </tr>
                    @if($request->payment->payment_proof)
                    <tr>
                        <td>Payment Proof:</td>
                        <td class="py-3">
                            <h5 class="mb-0">
                                <span type="button" class="pl-2 badge bg-label-dark" data-bs-toggle="modal" data-bs-target="#paymentProofModal">Uploaded Document</span>
                            </h5>
                        </td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="paymentProofModal" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <img src="data:image/jpeg;base64,{{ $request->payment->payment_proof }}" alt="Description of Image" style="max-width: 750px;">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    @if($request->table_name == 'subscriptions')
                    <tr>
                        <td>Lawyer:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->subscription->user_id ? $activeLawyers[$request->subscription->user_id] : '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Subscription Type:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->subscription->subscription_type) ? \App\Models\Subscription::$subscriptionTypes[$request->subscription->subscription_type] : '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Start Date:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->subscription->start_date) ? \Carbon\Carbon::parse($request->subscription->start_date)->format('d-M-Y') : '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>End Date:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->subscription->end_date) ? \Carbon\Carbon::parse($request->subscription->end_date)->format('d-M-Y') : '--' }}</h5>
                        </td>
                    </tr>
                    @endif

                    @if($request->table_name == 'vouchers')
                    <tr>
                        <td>Title:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->voucher->title ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Price:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->voucher->price ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Date:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->voucher->date) ? \Carbon\Carbon::parse($request->voucher->date)->format('d-M-Y') : '--' }}</h5>
                        </td>
                    </tr>
                    @endif
                    @if($request->table_name == 'rents')
                    <tr>
                        <td>Vendor:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $activeVendors[$request->rent->user_id]['full_name'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Amount:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->rent->rent_amount ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Renewal Date:</td>
                        <td class="py-3">

                            <h5 class="mb-0">{{ ($request->rent->renewal_date) ? \Carbon\Carbon::parse($request->rent->renewal_date)->format('d-M-Y') : '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>End Date:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->rent->end_date) ? \Carbon\Carbon::parse($request->rent->end_date)->format('d-M-Y') : '--' }}</h5>
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
                    @if($request->table_name == 'books_categories')
                    <tr>
                        <td>Category Name:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['category_name'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Published Volumes:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['published_volumes'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Published Total Volumes:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['published_total_volumes'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    @endif
                    @if($request->table_name == 'books')
                    <tr>
                        <td>Category Name:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $categories[$request->changes['book_category_id']] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Book Name:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['book_name'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Author Name:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['book_author_name'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Price:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['price'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Book Volume:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['book_volume'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Book Licence:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['book_licence'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Licence Valid Upto:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->changes['book_licence_valid_upto']) ? \Carbon\Carbon::parse($request->changes['book_licence_valid_upto'])->format('d-M-Y') : '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Publish Date:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['publish_date'] ? \Carbon\Carbon::parse($request->changes['publish_date'])->format('d-M-Y') : '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Available:</td>
                        <?php
                        if ($request->changes['available'] == 1) {
                            $available = 'Yes';
                            $class = 'bg-label-success';
                        } else {
                            $available = 'No';
                            $class = 'bg-label-warning';
                        }
                        ?>
                        <td class="py-3">
                            <h5 class="mb-0"><span class='badge {{ $class }} me-1'>{{ $available }}</span></h5>
                        </td>
                    </tr>
                    @endif
                    @if($request->table_name == 'payments')
                    <tr>
                        <td>Lawyer:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['user_id'] ? $activeLawyers[$request->changes['user_id']] : '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Amount:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['payment_amount'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Payment Date:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->changes['payment_date']) ? \Carbon\Carbon::parse($request->changes['payment_date'])->format('d-M-Y') : '--' }}</h5>
                        </td>
                    </tr>
                    @if(isset($request->changes['payment_proof']))
                    <tr>
                        <td>Payment Proof:</td>
                        <td class="py-3">
                            <h5 class="mb-0">
                                <span type="button" class="pl-2 badge bg-label-dark" data-bs-toggle="modal" data-bs-target="#paymentProofModal">Uploaded Document</span>
                            </h5>
                        </td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="paymentProofModal" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <img src="data:image/jpeg;base64,{{ $request->changes['payment_proof'] }}" alt="Description of Image" style="max-width: 750px;">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    @if($request->table_name == 'subscriptions')
                    <tr>
                        <td>Lawyer:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['user_id'] ? $activeLawyers[$request->changes['user_id']] : '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Subscription Type:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->changes['subscription_type']) ? \App\Models\Subscription::$subscriptionTypes[$request->changes['subscription_type']] : '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Start Date:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->changes['start_date']) ? \Carbon\Carbon::parse($request->changes['start_date'])->format('d-M-Y') : '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>End Date:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->changes['end_date']) ? \Carbon\Carbon::parse($request->changes['end_date'])->format('d-M-Y') : '--' }}</h5>
                        </td>
                    </tr>
                    @endif
                    @if($request->table_name == 'vouchers')
                    <tr>
                        <td>Title:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['title'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Price:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['price'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Date:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->changes['date']) ? \Carbon\Carbon::parse($request->changes['date'])->format('d-M-Y') : '--' }}</h5>
                        </td>
                    </tr>
                    @endif
                    @if($request->table_name == 'rents')
                    <tr>
                        <td>Vendor:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $activeVendors[$request->changes['user_id']]['full_name'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Amount:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ $request->changes['rent_amount'] ?? '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>Renewal Date:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->changes['renewal_date']) ? \Carbon\Carbon::parse($request->changes['renewal_date'])->format('d-M-Y') : '--' }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>End Date:</td>
                        <td class="py-3">
                            <h5 class="mb-0">{{ ($request->changes['renewal_date']) ? \Carbon\Carbon::parse($request->changes['renewal_date'])->format('d-M-Y') : '--' }}</h5>
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