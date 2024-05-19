@extends('layouts.app')
@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Lawyers</span></h4>
<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="{{route('users.add')}}"><i class="bx bx-user me-1"></i> Add Lawyer</a>
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
                    <th>Name</th>
                    <th>Father'S Name</th>
                    <th>DESIGNATION</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php
                $i = 1;
                foreach ($users as $user) {
                    $userName = $user->first_name;
                    if ($user->middle_name) {
                        $userName .= ' ' . $user->middle_name . ' ' . $user->last_name;
                    } else {
                        $userName .= ' ' . $user->last_name;
                    }
                ?>
                    <tr>
                        <td> {{ $i }} </td>
                        <td> {{ $userName }} </td>
                        <td> {{ ($user->father_first_name) ? $user->father_first_name . ' ' . $user->father_last_name : '--' }}</td>
                        <td>
                            {{ \App\Models\User::$designations[$user->designation] ?? '--' }}
                        </td>
                        <td>
                            @if($user->status == 1)
                            <span class="badge bg-label-success me-1">{{ \App\Models\User::$statuses[$user->status] }}</span>
                            @elseif($user->status == 2)
                            <span class="badge bg-label-warning me-1">{{ \App\Models\User::$statuses[$user->status] }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <!-- Icon for three dots -->
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}"><i class="bx bx-edit-alt me-1"></i> Edit</a>

                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        <a class="dropdown-item delete-user" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                                    </form>

                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3">
            <!-- Add pagination links -->
            {{ $users->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->

<!-- </div> -->
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // click event listener to all delete buttons with the class 'delete-user'
        document.querySelectorAll('.delete-user').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Show the confirmation dialog
                if (confirm('Are you sure you want to delete this user?')) {
                    // If the user confirms, submit the nearest form
                    this.closest('form').submit();
                }
            });
        });
    });
</script>
@endsection