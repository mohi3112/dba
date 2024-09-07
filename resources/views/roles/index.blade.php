@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Roles</span></h4>
<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="{{route('roles.add')}}"><i class="bx bx-user me-1"></i> Add Role</a>
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
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($roles as $role)
                <tr>
                    <td> {{ $i }} </td>
                    <td> {{ $role->name }} </td>
                    <td> {{ $role->description }} </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <a class="color-unset" href="{{ route('roles.edit', $role->id) }}"><i class="fas fa-edit"></i></a>
                        </div>
                    </td>
                </tr>
                @php($i++)
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3 mr-3">
            {{ $roles->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->
@endsection