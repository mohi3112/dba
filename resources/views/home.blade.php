@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <!-- <div class="card-header">Dashboard</div> -->
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    @if ($user->account_approved == 0)
                    @php
                    $isLessThen24 = \Carbon\Carbon::now()->subHours(24)->lt($user->created_at);
                    @endphp
                    @if($isLessThen24)
                    <div class="bs-toast toast fade show bg-warning" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <i class="fas fa-bell"></i>
                            <div class="me-auto fw-semibold ml-2"> Alert!</div>
                            <!-- <small>11 mins ago</small> -->
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            Please update your profile with in 24 hours. After that your profile will be reviewed!
                        </div>
                    </div>
                    @else
                    <div class="bs-toast toast fade show bg-warning" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <i class="fas fa-bell"></i>
                            <div class="me-auto fw-semibold ml-2"> Alert!</div>
                            <!-- <small>11 mins ago</small> -->
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            Your profile is under review!
                        </div>
                    </div>
                    @endif
                    @else
                    <h1>Welcome {{ $user->full_name }}</h1>
                    @php
                    $isLessThen24 = \Carbon\Carbon::now()->subHours(24)->lt($user->updated_at);
                    @endphp
                    @if($isLessThen24)
                    <div class="bs-toast toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <i class="fas fa-bell"></i>
                            <div class="me-auto fw-semibold ml-2">Congratulations</div>
                            <!-- <small>11 mins ago</small> -->
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            Your profile is Approved!
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection