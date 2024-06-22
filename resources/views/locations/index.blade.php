@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Locations</span></h4>
<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="{{route('locations.add')}}"><i class="bx bx-user me-1"></i> Add Location</a>
    </li>
</ul>
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card mb-4">
    <h5 class="card-header">Filters</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('locations') }}">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-md-3">
                    <label for="shopNumber" class="form-label">shop_number</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="shopNumber" value="{{@$_GET['shopNumber']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="floorNumber" class="form-label">floor_number</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="floorNumber" value="{{@$_GET['floorNumber']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="complex" class="form-label">complex</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="complex" value="{{@$_GET['complex']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="rent" class="form-label">rent</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="rent" value="{{@$_GET['rent']}}">
                    </div>
                </div>
                <div class="col-md-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <button class="btn btn-primary">Filter</button>
                </div>

                <div class="col-md-1 ml-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <a href="{{ route('locations') }}" class="btn btn-secondary">Reset</a>
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
                    <th>shop number</th>
                    <th>floor</th>
                    <th>complex</th>
                    <th>rent</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($locations as $location)
                @php($requestReviewProcessStarted = 0)
                @if($location->inProgressReviewRequests && $location->inProgressReviewRequests->count() > 0)
                @php($requestReviewProcessStarted = 1)
                @endif
                <tr>
                    <td> {{ $i }} </td>
                    <td> {{ $location->shop_number }} </td>
                    <td> {{ $location->floor_number }} </td>
                    <td> {{ $location->complex }} </td>
                    <td>₹{{ $location->rent }} </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <!-- edit -->
                            @if(!$requestReviewProcessStarted)
                            <a class="color-unset" href="{{ route('locations.edit', $location->id) }}"><i class="fas fa-edit"></i></a>
                            @else
                            <span>In Review</span>
                            @endif
                            <!-- view -->
                            <!-- <a class="pl-3 color-unset" data-bs-toggle="modal" data-bs-target="#modalCenter{{$location->id}}" href="#"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <div class="modal fade" id="modalCenter{{$location->id}}" tabindex="-1" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">Location details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="shopNumber" class="form-label">Shop Numer:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $location->shop_number }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="floorNumber" class="form-label">Floor Numer:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    {{ $location->floor_number }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="Amount" class="form-label">Rent:</label>
                                                </div>
                                                <div class="col-md-7">
                                                    ₹{{ $location->rent }}
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
                            </div> -->
                            <!-- delete -->
                            @if(!$requestReviewProcessStarted)
                            <form action="{{ route('locations.destroy', $location->id) }}" method="POST">
                                @csrf
                                <a class="pl-3 delete-location color-unset" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @php($i++)
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3">
            {{ $locations->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->

<!-- </div> -->
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // click event listener to all delete buttons with the class 'delete-location'
        document.querySelectorAll('.delete-location').forEach(function(button) {
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