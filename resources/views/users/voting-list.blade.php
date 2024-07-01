@extends('layouts.app')
@section('content')

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Lawyers /</span> Voting List</h4>

@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="card mb-4">
    <h5 class="card-header">Filters</h5>
    <div class="card-body">
        <form method="GET" action="{{ route('users.voting-list') }}">
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

                <div class="col-md-3">
                    <label for="licenceNo" class="form-label">Licence No</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="licenceNo" value="{{@$_GET['licenceNo']}}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="aadhaarNo" class="form-label">Aadhaar No</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="aadhaarNo" value="{{@$_GET['aadhaarNo']}}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-check form-switch" style="margin-top: 25%;">
                        <label class="form-label" for="showToastPlacement">&nbsp;</label>
                        <input class="form-check-input" name="is_active" type="checkbox" id="flexSwitchCheckChecked" {{ (count($_GET) > 0 && !isset($_GET['is_active'])) ? '' : 'checked' }}>
                        <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
                    </div>
                </div>
                @if (auth()->user()->hasRole('president'))
                <div class="col-md-2">
                    <div class="form-check form-switch" style="margin-top: 25%;">
                        <label class="form-label" for="showToastPlacement">&nbsp;</label>
                        <input class="form-check-input" name="is_deceased" type="checkbox" id="flexSwitchCheckDeceased" {{ (count($_GET) > 0 && isset($_GET['is_deceased'])) ? "checked" : "" }}>
                        <label class="form-check-label" for="flexSwitchCheckDeceased">Deceased</label>
                    </div>
                </div>
                @endif
                <div class="col-md-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <button class="btn btn-primary">Filter</button>
                </div>

                <div class="col-md-1 ml-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <a href="{{ route('users.voting-list') }}" class="btn btn-secondary">Reset</a>
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
                    <th>Licence No</th>
                    <th>Aadhaar No</th>
                    <th>Mobile No</th>
                    <th>Status</th>
                    @if (auth()->user()->hasRole('president'))
                    <th>Deceased</th>
                    @endif
                    <th>Address</th>
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
                            {{ $user->licence_no ?? NULL }}
                        </td>
                        <td>
                            {{ $user->aadhaar_no ?? NULL }}
                        </td>
                        <td>
                            {{ $user->mobile1 }} {{ ($user->mobile2) ? ' ( ' . $user->mobile2 . ' )' : '' }}
                        </td>
                        <td>
                            @if($user->status == 1)
                            <span class="badge bg-label-success me-1">{{ \App\Models\User::$statuses[$user->status] }}</span>
                            @elseif($user->status == 2)
                            <span class="badge bg-label-warning me-1">{{ \App\Models\User::$statuses[$user->status] }}</span>
                            @endif
                        </td>
                        @if (auth()->user()->hasRole('president'))
                        <td> {{ ($user->is_deceased) ? 'Yes' : 'No' }} </td>
                        @endif
                        <td> {{ $user->address ?? NULL }} </td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3 mr-3">
            <!-- Add pagination links -->
            {{ $users->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->
@endsection