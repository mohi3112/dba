@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Rents</span></h4>
<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="{{route('rents.add')}}"><i class="bx bx-user me-1"></i> Add Rent</a>
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
                    <th>Vendor</th>
                    <th>Amount</th>
                    <th>Renewal Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($rents as $rent)
                <tr>
                    <td> {{ $i }} </td>
                    <td> {{ $activeVendors[$rent->user_id]['full_name'] }} </td>
                    <td>₹{{ $rent->rent_amount }} </td>
                    <td>{{ \Carbon\Carbon::parse($rent->renewal_date)->format('d-M-Y') }} </td>
                    <td>{{ \Carbon\Carbon::parse($rent->end_date)->format('d-M-Y') }} </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <!-- edit -->
                            <a class="color-unset" href="{{ route('rents.edit', $rent->id) }}"><i class="fas fa-edit"></i></a>
                            <!-- view -->
                            <a class="pl-3 color-unset" data-bs-toggle="modal" data-bs-target="#modalCenter{{$rent->id}}" href="#"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <div class="modal fade" id="modalCenter{{$rent->id}}" tabindex="-1" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">Rent details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="lawyerName" class="form-label">Vendor:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $activeVendors[$rent->user_id]['full_name'] }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="Amount" class="form-label">Amount:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    ₹{{ $rent->rent_amount }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="rentDate" class="form-label">Rent Renewal Date:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ \Carbon\Carbon::parse($rent->renewal_date)->format('d-M-Y') }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="rentDate" class="form-label">End Date:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ \Carbon\Carbon::parse($rent->end_date)->format('d-M-Y') }}
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
                            <form action="{{ route('rents.destroy', $rent->id) }}" method="POST">
                                @csrf
                                <a class="pl-3 delete-rent color-unset" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </form>
                        </div>
                    </td>
                </tr>
                @php($i++)
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3">
            {{ $rents->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->

@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // click event listener to all delete buttons with the class 'delete-rent'
        document.querySelectorAll('.delete-rent').forEach(function(button) {
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