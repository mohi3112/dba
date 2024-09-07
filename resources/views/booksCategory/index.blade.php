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

<div class="card mb-4">
    <h5 class="card-header">Filters</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('bookCategories') }}">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-md-3">
                    <label for="categoryName" class="form-label">Category Name</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="categoryName" value="{{@$_GET['categoryName']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="publishedVolumes" class="form-label">Published Volumes</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="publishedVolumes" value="{{@$_GET['publishedVolumes']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="publishedTotalVolumes" class="form-label">Published Total Volumes</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="publishedTotalVolumes" value="{{@$_GET['publishedTotalVolumes']}}">
                    </div>
                </div>

                <div class="col-md-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <button class="btn btn-primary">Filter</button>
                </div>

                <div class="col-md-1 ml-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <a href="{{ route('bookCategories') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Striped Rows -->

<div class="card">
    <div class="table-responsive text-nowrap">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Category Name</th>
                    <th>Publication Year</th>
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
                    <td> {{ $booksCategory->year_of_publishing }} </td>
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
        <div class="d-flex justify-content-end pt-3 mr-3">
            {{ $booksCategories->appends(request()->except('page'))->links() }}
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