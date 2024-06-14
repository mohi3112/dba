@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Books Categories</span></h4>
<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="{{route('bookCategories.add')}}"><i class="bx bx-user me-1"></i> Add Books Category</a>
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
                    <th>Category Name</th>
                    <th>Publised volumes</th>
                    <th>Publised Total volumes</th>
                    <th>Available Books</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($booksCategories as $booksCategory)

                <tr>
                    <td> {{ $i }} </td>
                    <td> {{ $booksCategory->category_name }} </td>
                    <td> {{ $booksCategory->published_volumes }} </td>
                    <td> {{ $booksCategory->published_total_volumes }} </td>
                    <td> {{ $booksCategory->available_books_count }} </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <!-- edit -->
                            <a class="color-unset" href="{{ route('bookCategories.edit', $booksCategory->id) }}"><i class="fas fa-edit"></i></a>
                            <!-- delete -->
                            <form action="{{ route('bookCategories.destroy', $booksCategory->id) }}" method="POST">
                                @csrf
                                <a class="pl-3 delete-book-category color-unset" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </form>
                        </div>
                    </td>
                </tr>
                @php($i++)
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3">
            {{ $booksCategories->links() }}
        </div>
    </div>
</div>
<!-- </div> -->
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // click event listener to all delete buttons with the class 'delete-book-category'
        document.querySelectorAll('.delete-book-category').forEach(function(button) {
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