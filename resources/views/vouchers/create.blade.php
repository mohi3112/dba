@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Vouchers /</span> Add Voucher</h4>

<form method="POST" action="{{ route('voucher.store') }}" id="formVoucher">
    @csrf
    <input type="hidden" name="issued_by" value="{{ Auth::user()->id }}">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Voucher Details</h5>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" value="{{ old('title') }}">
                            </div>
                            @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="price" class="form-label">Amount <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control @error('price') is-invalid @enderror" type="number" name="price" value="{{ old('price') }}">
                            </div>
                            @error('price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="date">Date</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control @error('date') is-invalid @enderror" type="date" name="date" value="{{ old('date') }}">
                            </div>
                            @error('date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="issued_to" class="form-label">issued to</label>
                            <select id="userDropdown" name="issued_to" class="form-control form-select user-select">
                                @foreach($activeLawyers as $ky => $lawyer)
                                <option value="{{$ky}}" @if(old('userId')==$ky) selected @endif>{{$lawyer}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Description"> {{ old('description') }} </textarea>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('vouchers')}}" class="btn btn-outline-secondary">Cancel</a>
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
        $('.user-select').select2({
            placeholder: 'Select user',
            allowClear: true
        });
    });
</script>
@endSection