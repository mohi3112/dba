@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Rents /</span> Edit Rent</h4>
@if ($errors->any())
@foreach ($errors->all() as $error)
<div class="alert alert-danger alert-dismissible" role="alert">
    {{ $error }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endforeach
@endif
<form method="POST" action="{{ route('rents.update', $rent->id) }}" id="formRent" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Rent Details</h5>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="vendor" class="form-label">Vendor <span class="text-danger">*</span></label>
                            <select id="vendor" name="user_id" class="select2 form-select @error('user_id') is-invalid @enderror">
                                <option value="">Select Vendor</option>
                                @foreach($activeVendors as $vendorId => $vendorInfo)
                                <option value="{{$vendorId}}" data-locationid="{{$vendorInfo['location_id']}}" {{ $rent->user_id == $vendorId ? 'selected' : ''}}>{{$vendorInfo['full_name']}}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="rent_amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input id="rent_amount" class="form-control @error('rent_amount') is-invalid @enderror" type="number" name="rent_amount" value="{{ $rent->rent_amount }}">
                            </div>
                            @error('rent_amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="renewal_date"> Renewal Date <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control @error('renewal_date') is-invalid @enderror" type="date" name="renewal_date" value="{{ $rent->renewal_date }}">
                            </div>
                            @error('renewal_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="end_date"> End Date</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control @error('end_date') is-invalid @enderror" type="date" name="end_date" value="{{ $rent->end_date }}">
                            </div>
                            @error('end_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('rents')}}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('#vendor').change(function() {
            var locationId = $(this).find(':selected').data('locationid');
            if (locationId) {
                $.ajax({
                    url: '/get-rent/' + locationId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.rent !== undefined) {
                            $('#rent_amount').val(response.rent);
                        } else {
                            $('#rent_amount').val(0);
                        }
                    },
                    error: function() {
                        $('#rent_amount').val(0);
                    }
                });
            } else {
                $('#rent_amount').val(0);
            }
        });
    });
</script>
@endsection