@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Books /</span> Edit Book</h4>
@if ($errors->any())
@foreach ($errors->all() as $error)
<div class="alert alert-danger alert-dismissible" role="alert">
    {{ $error }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endforeach
@endif
<form method="POST" action="{{ route('books.update', $book->id) }}" id="formBook">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Edit Book Details</h5>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="book_name" class="form-label">Name</label>
                            <input type="text" class="form-control" placeholder="Book Name" id="book_name" name="book_name" value="{{$book->book_name}}">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="book_author_name" class="form-label">Author Name</label>
                            <input type="text" class="form-control" placeholder="Book Author Name" id="book_author_name" name="book_author_name" value="{{$book->book_author_name}}">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="book_licence" class="form-label">Licence</label>
                            <input type="text" class="form-control" placeholder="Book Licence" id="book_licence" name="book_licence" value="{{$book->book_licence}}">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="book_licence_valid_upto">Licence Valid Upto <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="date" name="book_licence_valid_upto" value="{{$book->book_licence_valid_upto}}" id="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="lawyer" class="form-label">Available <span class="text-danger">*</span></label>
                            <select id="lawyer" name="available" class="select2 form-select">
                                <option value="1" {{ $book->available == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ $book->available == 0 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a type="reset" href="{{route('books')}}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                    <!-- </form> -->
                </div>
                <!-- /Account -->
            </div>
        </div>

    </div>
</form>
@endsection