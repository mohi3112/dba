@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Books Categories /</span> Add Category</h4>
<form method="POST" action="{{ route('bookCategory.store') }}" id="formBookCategory">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Category Details</h5>
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
                            <label for="year_of_publishing" class="form-label">Publication Year</label>
                            <input type="text" class="form-control @error('year_of_publishing') is-invalid @enderror" value="{{ old('year_of_publishing') }}" id="year_of_publishing" name="year_of_publishing" maxlength="4" placeholder="YYYY">
                            @error('year_of_publishing')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="published_total_volumes" class="form-label">Published Total Number Of volumes <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('published_total_volumes') is-invalid @enderror" placeholder="Published Total Number Of volumes" id="published_total_volumes" value="{{ old('published_total_volumes') }}" name="published_total_volumes">
                            @error('published_total_volumes')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="published_volumes" class="form-label">Published volumes</label>
                            <input type="text" class="form-control" placeholder="Published volumes. e.g. vol-1, vol-2" id="published_volumes" value="{{ old('published_volumes') }}" name="published_volumes">
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('bookCategories')}}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection