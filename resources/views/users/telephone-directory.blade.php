@extends('layouts.app')
@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Lawyers</span></h4>
<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="{{route('users.add')}}"><i class="bx bx-user me-1"></i> Telephone Directory</a>
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
        <form method="GET" action="{{ route('users.telephone-directory') }}">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-md-3">
                    <label for="name" class="form-label">First Name</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="name" value="{{@$_GET['name']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="l_name" class="form-label">Last Name</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="l_name" value="{{@$_GET['l_name']}}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-check form-switch" style="margin-top: 25%;">
                        <label class="form-label" for="showToastPlacement">&nbsp;</label>
                        <input class="form-check-input" name="is_active" type="checkbox" id="flexSwitchCheckChecked" {{ (count($_GET) > 0 && !isset($_GET['is_active'])) ? '' : 'checked' }}>
                        <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-check form-switch" style="margin-top: 25%;">
                        <label class="form-label" for="showToastPlacement">&nbsp;</label>
                        <input class="form-check-input" name="is_deceased" type="checkbox" id="flexSwitchCheckDeceased" {{ (count($_GET) > 0 && isset($_GET['is_deceased'])) ? "checked" : "" }}>
                        <label class="form-check-label" for="flexSwitchCheckDeceased">Deceased</label>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-check form-switch" style="margin-top: 25%;">
                        <label class="form-label" for="showToastPlacement">&nbsp;</label>
                        <input class="form-check-input" name="is_physically_disabled" type="checkbox" id="flexSwitchCheckPhysicallyDisabled" {{ (count($_GET) > 0 && isset($_GET['is_physically_disabled'])) ? "checked" : "" }}>
                        <label class="form-check-label" for="flexSwitchCheckPhysicallyDisabled">Physically Disabled</label>
                    </div>
                </div>

                <div class="col-md-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <button class="btn btn-primary">Filter</button>
                </div>

                <div class="col-md-1 ml-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <a href="{{ route('users.telephone-directory') }}" class="btn btn-secondary">Reset</a>
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
                    <th>Name</th>
                    <th>Mobile (Alternate Number)</th>
                    <th>Status</th>
                    <th>Deceased</th>
                    <th>Handicaped</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php
                $i = 1;
                foreach ($users as $user) {
                ?>
                    <tr>
                        <td> {{ $i }} </td>
                        <td>
                            <a href="{{ route('user.view', $user->id) }}">
                                {{ $user->full_name }}
                            </a>
                        </td>
                        <td>
                            {{ $user->mobile1 ?? NULL }}
                            @if($user->mobile2)
                            ({{$user->mobile2}})
                            @endif
                        </td>
                        <td>
                            @if($user->status == 1)
                            <span class="badge bg-label-success me-1">{{ \App\Models\User::$statuses[$user->status] }}</span>
                            @elseif($user->status == 2)
                            <span class="badge bg-label-warning me-1">{{ \App\Models\User::$statuses[$user->status] }}</span>
                            @endif
                        </td>
                        <td> {{ ($user->is_deceased) ? 'Yes' : 'No' }} </td>
                        <td> {{ ($user->is_physically_disabled) ? 'Yes' : 'No' }} </td>
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
@endsection