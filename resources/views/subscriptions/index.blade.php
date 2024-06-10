@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Subscriptions</span></h4>
<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="{{route('subscriptions.add')}}"><i class="bx bx-user me-1"></i> Add Subscription</a>
    </li>
</ul>
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<!-- Striped Rows -->

<div class="card">
    <div class="table-responsive text-nowrap">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Lawyer Name</th>
                    <th>Subscription Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($subscriptions as $subscription)
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
                    <td> {{ $activeLawyers[$subscription->user_id] }} </td>
                    <td> {{ \App\Models\Subscription::$subscriptionTypes[$subscription->subscription_type] }} </td>
                    <td> {{ \Carbon\Carbon::parse($subscription->start_date)->format('d-M-Y') }} </td>
                    <td> {{ \Carbon\Carbon::parse($subscription->end_date)->format('d-M-Y') }} </td>
                    <td> <span class='badge {{ $class }} me-1'>{{ $status }}</span> </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <!-- edit -->
                            <a class="color-unset" href="{{ route('subscriptions.edit', $subscription->id) }}"><i class="fas fa-edit"></i></a>
                            <!-- view -->
                            <a class="pl-3 color-unset" data-bs-toggle="modal" data-bs-target="#modalCenter{{$subscription->id}}" href="#"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <div class="modal fade" id="modalCenter{{$subscription->id}}" tabindex="-1" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">Subscription details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="nameWithTitle" class="form-label">Lawyer Name:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    {{ $activeLawyers[$subscription->user_id] }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="nameWithTitle" class="form-label">Subscription Type:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    {{ \App\Models\Subscription::$subscriptionTypes[$subscription->subscription_type] }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="nameWithTitle" class="form-label">Start Date:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    {{ \Carbon\Carbon::parse($subscription->start_date)->format('d-M-Y') }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="nameWithTitle" class="form-label">End Date:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    {{ \Carbon\Carbon::parse($subscription->end_date)->format('d-M-Y') }}
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
                            <!-- delete -->
                            <form action="{{ route('subscriptions.destroy', $subscription->id) }}" method="POST">
                                @csrf
                                <a class="pl-3 delete-subscription color-unset" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </form>
                        </div>
                    </td>
                </tr>
                @php($i++)
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3">
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->

<!-- </div> -->
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // click event listener to all delete buttons with the class 'delete-subscription'
        document.querySelectorAll('.delete-subscription').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Show the confirmation dialog
                if (confirm('Are you sure you want to delete this record?')) {
                    // If the user confirms, submit the nearest form
                    this.closest('form').submit();
                }
            });
        });
    });
</script>
@endsection