@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Books Categories /</span> Add Category</h4>
<form method="POST" action="{{ route('bookCatgory.store') }}" id="formBookCategory">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Catgory Details</h5>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="category_name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control  @error('category_name') is-invalid @enderror" placeholder="Category Name" id="category_name" value="{{ old('category_name') }}" name="category_name">
                            @error('category_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="published_total_volumns" class="form-label">Published Total Number Of Volumns <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('published_total_volumns') is-invalid @enderror" placeholder="Published Total Number Of Volumns" id="published_total_volumns" value="{{ old('published_total_volumns') }}" name="published_total_volumns">
                            @error('published_total_volumns')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="published_volumns" class="form-label">Published Volumns</label>
                            <input type="text" class="form-control" placeholder="Published Volumns. e.g. vol-1, vol-2" id="published_volumns" value="{{ old('published_volumns') }}" name="published_volumns">
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('bookCatgories')}}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection