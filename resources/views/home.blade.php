@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <!-- <div class="col-md-12"> -->
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
                <h5>Welcome {{ $user->full_name }}</h5>
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
        <!-- </div> -->
    </div>
    @if(auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('president'))
    <div class="row mt-3">
        <div class="card mb-4">
            <!-- <h5 class="card-header">Bootstrap Toasts Example With Placement</h5> -->
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard') }}">
                    <div class="row gx-3 gy-2 align-items-center">
                        <div class="col-md-3">
                            <label class="form-label" for="selectTypeOpt">From Date</label>
                            <input class="form-control" type="date" value="{{@$_GET['startDate']}}" name="startDate" id="startDate">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="selectPlacement">To Date</label>
                            <input class="form-control" type="date" value="{{@$_GET['endDate']}}" name="endDate" id="endDate">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label" for="showToastPlacement">&nbsp;</label>
                            <button id="showToastPlacement" class="btn btn-primary d-block">Filter</button>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label" for="showToastPlacement">&nbsp;</label>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-6 col-md-6 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{url('icons/lawyer.webp')}}" alt="chart success" class="rounded">
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Lawyers</span>
                            <h3 class="card-title mb-2">{{ $dashboardData['total_lawyers'] }}</h3>
                            <!-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +72.80%</small> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{url('icons/user.png')}}" alt="Credit Card" class="rounded">
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <span>Vendors</span>
                            <h3 class="card-title text-nowrap mb-1">{{ $dashboardData['total_vendors'] }}</h3>
                            <!-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.42%</small> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{url('icons/wallet-info.png')}}" alt="chart success" class="rounded">
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Received Subscriptions</span>
                            <h3 class="card-title mb-2">₹{{ $dashboardData['total_subscriptions_received'] }}</h3>
                            <!-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +72.80%</small> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{url('icons/cc-primary.png')}}" alt="chart success" class="rounded">
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <span>Rent Received</span>
                            <h3 class="card-title text-nowrap mb-1">₹{{ $dashboardData['total_rent_received'] }}</h3>
                            <!-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.42%</small> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{url('icons/paypal.png')}}" alt="chart success" class="rounded">
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <span>Spent Amount</span>
                            <h3 class="card-title text-nowrap mb-1">₹{{ $dashboardData['total_voucher_spent'] }}</h3>
                            <!-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.42%</small> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection