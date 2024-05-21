@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Books</span></h4>
<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="{{route('books.add')}}"><i class="bx bx-user me-1"></i> Add Book</a>
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
                    <th>Book Name</th>
                    <th>Author Name</th>
                    <th>Licence</th>
                    <th>Available</th>
                    <th>Issue Book</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($books as $book)
                <?php
                if ($book->available == 1) {
                    $available = 'Yes';
                    $class = 'bg-label-success';
                } else {
                    $available = 'No';
                    $class = 'bg-label-warning';
                }
                ?>
                <tr>
                    <td> {{ $i }} </td>
                    <td> {{ $book->book_name }} </td>
                    <td> {{ $book->book_author_name }} </td>
                    <td> {{ $book->book_licence }} </td>
                    <td> <span class='badge {{ $class }} me-1'>{{ $available }}</span> </td>
                    <td>
                        @if($book->isLastIssuedBookReturned)
                        <a class="btn btn-primary issue-book" data-book-id="{{ $book->id }}" href="#">Issue Book</a>
                        @else
                        <a class="btn btn-secondary" href="#">Not Available</a>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <!-- edit -->
                            <a class="color-unset" href="{{ route('books.edit', $book->id) }}"><i class="fas fa-edit"></i></a>
                            <!-- view -->
                            <a class="pl-3 color-unset" data-bs-toggle="modal" data-bs-target="#modalCenter{{$book->id}}" href="#"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <div class="modal fade" id="modalCenter{{$book->id}}" tabindex="-1" style="display: none;" aria-hidden="true">
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
                                                    {{ $book->book_name }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="nameWithTitle" class="form-label">Author Name:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    {{ $book->book_author_name }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="nameWithTitle" class="form-label">Book Licence:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    {{ $book->book_licence }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="nameWithTitle" class="form-label">Licence Valid Upto:</label>
                                                </div>
                                                <div class="col-md-8">
                                                    {{ \Carbon\Carbon::parse($book->book_licence_valid_upto)->format('d-M-Y') }}
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
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST">
                                @csrf
                                <a class="pl-3 delete-book color-unset" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </form>
                        </div>
                    </td>
                </tr>
                @php($i++)
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3">
            {{ $books->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="issueBookModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('book.issue') }}" id="issueBookForm" method="POST">
            @csrf
            <input type="hidden" class="book_id" name="book_id" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Issue Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="lawyer" class="form-label">Lawyer <span class="text-danger">*</span></label>
                            <select id="lawyer" name="user_id" class="select2 form-select">
                                <option value="">Select Lawyer</option>
                                @foreach($activeLawyers as $lawyerId => $lawyerName)
                                <option value="{{$lawyerId}}">{{$lawyerName}}</option>
                                @endforeach
                            </select>
                            <span id="error-message" style="color:red; display:none;">Please select lawyer name from the dropdown.</span>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="issueDate" class="form-label">Book Issue Date</label>
                            <input type="date" id="issueDate" name="issue_date" class="form-control" value="{{ now()->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary issue-book-submit">Issue Book</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!--/ Striped Rows -->

<!-- </div> -->
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // click event listener to all delete buttons with the class 'delete-book'
        document.querySelectorAll('.delete-book').forEach(function(button) {
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
    $(document).ready(function() {
        $(document).on('click', '.issue-book', function() {
            $('#issueBookModal').modal('show');
            let bookId = $(this).data('book-id');
            $('.book_id').val(bookId);
        });

        $('#issueBookForm').on('submit', function(e) {
            var LawyerName = $('#lawyer').val();
            if (LawyerName === "") {
                // Prevent form submission
                e.preventDefault();
                // Show error message
                $('#error-message').show();
                return;
            } else {
                // Hide error message if dropdown is selected
                $('#error-message').hide();
            }
            $(this).closest("form").submit();
        });
    });
</script>
@endsection