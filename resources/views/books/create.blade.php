@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Books /</span> Add Book</h4>
<form method="POST" action="{{ route('book.store') }}" id="formBook">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Book Details</h5>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="book_name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control  @error('book_name') is-invalid @enderror" placeholder="Book Name" id="book_name" value="{{ old('book_name') }}" name="book_name">
                            @error('book_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="book_author_name" class="form-label">Author Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('book_author_name') is-invalid @enderror" placeholder="Book Author Name" id="book_author_name" value="{{ old('book_author_name') }}" name="book_author_name">
                            @error('book_author_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="book_licence" class="form-label">Licence</label>
                            <input type="text" class="form-control" placeholder="Book Licence" id="book_licence" value="{{ old('book_licence') }}" name="book_licence">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="book_licence_valid_upto">Licence Valid Upto <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control @error('book_licence_valid_upto') is-invalid @enderror" type="date" value="{{ old('book_licence_valid_upto') }}" name="book_licence_valid_upto">
                                @error('book_licence_valid_upto')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="lawyer" class="form-label">Available <span class="text-danger">*</span></label>
                            <select id="lawyer" name="available" class="select2 form-select">
                                <option value="1" {{ old('available') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('available') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('books')}}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@endsection