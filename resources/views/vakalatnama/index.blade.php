@extends('layouts.app')
@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Vakalatnama</span></h4>
<ul class="nav nav-pills flex-column flex-md-row mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="{{route('vakalatnama.form')}}"><i class="bx bx-user me-1"></i> Issue Vakalatnama</a>
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
        <form method="GET" action="{{ route('vakalatnamas') }}">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-md-3">
                    <label for="lawyer" class="form-label">User</label>
                    <select id="lawyer" name="userId" class="select2 form-select">
                        <option value="">Select User</option>
                        @foreach($activeLawyers as $ky => $lawyer)
                        <option value="{{$ky}}" @if(@$_GET['userId ']==$ky) selected @endif>{{$lawyer}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="issueDate" class="form-label">Issue Date</label>
                    <div class="input-group">
                        <input type="date" class="form-control" name="issueDate" value="{{@$_GET['issueDate']}}">
                    </div>
                </div>

                <div class="col-md-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <button class="btn btn-primary">Filter</button>
                </div>

                <div class="col-md-1 ml-1">
                    <label class="form-label" for="showToastPlacement">&nbsp;</label>
                    <a href="{{ route('payments') }}" class="btn btn-secondary">Reset</a>
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
                    <th>Lawyer Name</th>
                    <th>Unique Number</th>
                    <th>Issue Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @php($i = 1)
                @foreach($vakalatnamas as $vakalatnama)
                <tr>
                    <td> {{ $i }} </td>
                    <td> {{ $activeLawyers[$vakalatnama->user_id] }} </td>
                    <td> {{ $vakalatnama->unique_id }} @if($vakalatnama->bulk_issue == 1) -- {{$vakalatnama->last_unique_id}} @endif </td>
                    <td> {{ \Carbon\Carbon::parse($vakalatnama->created_at)->format('d-M-Y') }} </td>
                    <td>
                        <a href="{{ route('vakalatnama.view-vakalatnama', $vakalatnama->unique_id) }}" target="_blank" class="color-unset">
                            <i class='bx bx-printer' style="font-size: 26px;"></i>
                        </a>
                    </td>
                </tr>
                @php($i++)
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end pt-3 mr-3">
            {{ $vakalatnamas->links() }}
        </div>
    </div>
</div>
<!--/ Striped Rows -->

@endsection