@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Issued Books</span></h4>
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
                    <th>Book - (Author)</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($issuedBooks as $issuedBookDetail)
                <tr>
                    <td> {{ $i }} </td>
                    <td> {{ $issuedBookDetail->user->full_name }} </td>
                    <td> {{ $issuedBookDetail->book->book_name }} - ({{ $issuedBookDetail->book->book_author_name }}) </td>
                    <td> {{ \Carbon\Carbon::parse($issuedBookDetail->issue_date)->format('d-M-Y') }} </td>
                    <td> {{ ($issuedBookDetail->return_date) ? \Carbon\Carbon::parse($issuedBookDetail->return_date)->format('d-M-Y') : '--' }} </td>
                    <td>
                        @if($issuedBookDetail->return_date == null)
                        <div class="d-flex align-items-center">
                            <a class="color-unset" data-bs-toggle="modal" data-bs-target="#modalReturnBook{{$issuedBookDetail->id}}" href="#">[Return Book]</a>
                            <div class="modal fade" id="modalReturnBook{{$issuedBookDetail->id}}" tabindex="-1" style="display: none;" aria-hidden="true">
                                <form action="{{ route('book.return_book', $issuedBookDetail->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalCenterTitle">Book details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="nameWithTitle" class="form-label">Book Name:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        {{ $issuedBookDetail->book->book_name }} - ({{ $issuedBookDetail->book->book_author_name }})
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="nameWithTitle" class="form-label">Lawyer Name:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        {{ $issuedBookDetail->user->full_name }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="nameWithTitle" class="form-label">Return Date:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input class="form-control" type="date" name="return_date" value="{{ now()->format('Y-m-d') }}" id="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                                <button type="submit" class="btn btn-primary issue-book-submit">Return Book</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @else
                        --
                        @endif
                    </td>
                </tr>
                @php($i++)
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3 mr-3">
            {{ $issuedBooks->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->

<!-- </div> -->
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $(document).on('submit', '.issue-book-submit', function(e) {
            e.preventDefault();
            $(this).closest("form").submit();
        });
    });
</script>
@endsection