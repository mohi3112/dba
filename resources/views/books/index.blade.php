@extends('layouts.app')
@section('content')
<span class="nonPrintArea">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Books</span></h4>
    <ul class="nav nav-pills flex-column flex-md-row mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="{{route('books.add')}}"><i class="bx bx-user me-1"></i> Add Book</a>
        </li>
    </ul>
</span>
@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div id="printArea" style="display:none;">
    <div id="codesList"></div>
</div>

<div class="card mb-4 nonPrintArea">
    <h5 class="card-header">Filters</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('books') }}">
            <div class="row gx-3 gy-2 align-items-center">

                <div class="col-md-3">
                    <label for="category" class="form-label">Category</label>
                    <select id="category" name="bookCategoryId" class="select2 form-select">
                        <option value="">Select Category</option>
                        @foreach($categories as $ky => $category)
                        <option value="{{$ky}}" @if(@$_GET['bookCategoryId']==$ky) selected @endif>{{$category}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="bookName" class="form-label">Book Name</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="bookName" value="{{@$_GET['bookName']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="bookAuthorName" class="form-label">Author Name</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="bookAuthorName" value="{{@$_GET['bookAuthorName']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="bookLicence" class="form-label">Book Licence</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="bookLicence" value="{{@$_GET['bookLicence']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="bookVolume" class="form-label">Book Volume</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="bookVolume" value="{{@$_GET['bookVolume']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="publishDate" class="form-label">Book Publish Date</label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="publishDate" value="{{@$_GET['publishDate']}}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-check form-switch" style="margin-top: 25%;">
                        <label class="form-label" for="showToastPlacement">&nbsp;</label>
                        <input class="form-check-input" name="is_available" type="checkbox" id="flexSwitchCheckChecked" {{ (count($_GET) > 0 && !isset($_GET['is_available'])) ? '' : 'checked' }}>
                        <label class="form-check-label" for="flexSwitchCheckChecked">Available</label>
                    </div>
                </div>

                <div class="col-md-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <button class="btn btn-primary">Filter</button>
                </div>

                <div class="col-md-1 ml-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <a href="{{ route('books') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Striped Rows -->

<form id="printForm" class="nonPrintArea">
    @csrf
    <button type="button" class="btn btn-primary mb-2" onclick="printSelectedBooks()">Print Selected Codes</button>
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <!-- <th>Sr. No.</th> -->
                        <th>Book Name</th>
                        <th>Category Name</th>
                        <th>Author Name</th>
                        <th>Licence</th>
                        <th>Unique Code</th>
                        <th>Available</th>
                        <th>Published On</th>
                        <!-- <th>Issue Book</th> -->
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
                        <td><input type="checkbox" name="selected_books[]" value="{{ $book->id }}"></td>
                        <td> {{ $book->book_name }} </td>
                        <td> {{ $categories[$book->book_category_id] }} </td>
                        <td> {{ $book->book_author_name }} </td>
                        <td> {{ $book->book_licence }} </td>
                        <td> {{ $book->unique_code }} </td>
                        <td> <span class='badge {{ $class }} me-1'>{{ $available }}</span> </td>
                        <td>
                            {{ \Carbon\Carbon::parse($book->publish_date)->format('d-M-Y') }}
                            {{-- @if($book->isLastIssuedBookReturned)
                        <a class="btn btn-primary issue-book" data-book-id="{{ $book->id }}" href="#">Issue Book</a>
                            @else
                            <a class="btn btn-secondary" href="#">Not Available</a>
                            @endif --}}
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
                                                        <label for="nameWithTitle" class="form-label">Category Name:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        {{ $categories[$book->book_category_id] }}
                                                    </div>
                                                </div>
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
                                                        <label for="nameWithTitle" class="form-label">Price:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        ₹{{ $book->price }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="nameWithTitle" class="form-label">Book Volume:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        {{ $book->book_volume }}
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
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="nameWithTitle" class="form-label">Publish Date:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        {{ \Carbon\Carbon::parse($book->publish_date)->format('d-M-Y') }}
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
            <div class="d-flex justify-content-end pt-3 mr-3">
                {{ $books->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
</form>

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

    function printSelectedBooks() {
        // Get the selected book IDs
        const selectedBooks = Array.from(document.querySelectorAll('input[name="selected_books[]"]:checked'))
            .map(checkbox => checkbox.value);

        if (selectedBooks.length === 0) {
            alert('No book(s) are selected.');
            return;
        }

        // Fetch the unique codes for the selected books
        fetch('{{ route("books.getUniqueCodes") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    selected_books: selectedBooks
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const codesList = document.getElementById('codesList');
                    codesList.innerHTML = '';

                    data.codes.forEach(code => {
                        const listItem = document.createElement('p');
                        listItem.textContent = code;
                        codesList.appendChild(listItem);
                    });

                    $('.nonPrintArea, .navbar.navbar-expand-md.navbar-light').hide();
                    // Show the print area and trigger the print
                    const printArea = document.getElementById('printArea');
                    printArea.style.display = 'block';
                    window.print();
                    printArea.style.display = 'none';

                    $('.nonPrintArea, .navbar.navbar-expand-md.navbar-light').show();
                } else {
                    alert('Failed to fetch unique codes.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while fetching unique codes.');
            });
    }
</script>
@endsection