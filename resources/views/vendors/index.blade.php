@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Vendors</span></h4>
<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="{{route('vendors.add')}}"><i class="bx bx-user me-1"></i> Add Vendor</a>
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
                    <th>Name</th>
                    <th>Father's Name </th>
                    <th>Mobile</th>
                    <th>Business name</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($vendors as $vendor)
                <tr>
                    <td> {{ $i }} </td>
                    <td> {{ $vendor->full_name }} </td>
                    <td> {{ $vendor->full_father_name }} </td>
                    <td> {{ $vendor->mobile }} </td>
                    <td> {{ $vendor->business_name }} </td>
                    <td> {{ $activeLocations[$vendor->location_id] }} </td>
                    <td>
                        @if($vendor->status == true)
                        <span class="badge bg-label-success me-1">{{ \App\Models\Vendor::$statuses[$vendor->status] }}</span>
                        @else
                        <span class="badge bg-label-warning me-1">{{ \App\Models\Vendor::$statuses[$vendor->status] }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <!-- edit -->
                            <a class="color-unset" href="{{ route('vendors.edit', $vendor->id) }}"><i class="fas fa-edit"></i></a>
                            <!-- view -->
                            <a class="pl-3 color-unset" data-bs-toggle="modal" data-bs-target="#modalCenter{{$vendor->id}}" href="#"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <div class="modal fade" id="modalCenter{{$vendor->id}}" tabindex="-1" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">Vendor details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="shopNumber" class="form-label">Vendor Name:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $vendor->full_name }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="floorNumber" class="form-label">Vendor Father's Name:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $vendor->full_father_name }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="Amount" class="form-label">Gender:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ \App\Models\Vendor::$genders[$vendor->gender] }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="Amount" class="form-label">DOB:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ \Carbon\Carbon::parse($vendor->dob)->format('d-M-Y') }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="Amount" class="form-label">Mobile:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $vendor->mobile }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="Amount" class="form-label">Business Name:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $vendor->business_name }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="Amount" class="form-label">Employees:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $vendor->employees }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="Amount" class="form-label">Location:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $activeLocations[$vendor->location_id] }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="Amount" class="form-label">Residence Address:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $vendor->residence_address }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="Amount" class="form-label">Status:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    @if($vendor->status == true)
                                                    <span class="badge bg-label-success me-1">{{ \App\Models\Vendor::$statuses[$vendor->status] }}</span>
                                                    @else
                                                    <span class="badge bg-label-warning me-1">{{ \App\Models\Vendor::$statuses[$vendor->status] }}</span>
                                                    @endif
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
                            <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST">
                                @csrf
                                <a class="pl-3 delete-vendor color-unset" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </form>
                        </div>
                    </td>
                </tr>
                @php($i++)
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3">
            {{ $vendors->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->

<!-- </div> -->
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // click event listener to all delete buttons with the class 'delete-vendor'
        document.querySelectorAll('.delete-vendor').forEach(function(button) {
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