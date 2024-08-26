<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/helpers.js') }}" defer></script>
    <script src="{{ asset('js/config.js') }}" defer></script>
    <!-- Font awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/core.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme-default.css') }}" rel="stylesheet">
    <link href="{{ asset('css/demo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/perfect-scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

    <div id="app" class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            @if(auth()->check())
            <!-- side menu -->
            @include('layouts.sidemenu')
            @endif

            <div class="layout-page">
                @if(auth()->check())
                <!-- top nav bar -->
                <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                    <div class="container">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            {{ config('app.name', 'Laravel') }}
                        </a>

                        <nav class="layout-navbar container-xxl navbar navbar-expand-xl align-items-center " id="layout-navbar">
                            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                                <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </a>
                            </div>

                            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                                <ul class="navbar-nav flex-row align-items-center ms-auto">
                                    <!-- Place this tag where you want the button to render. -->
                                    <li class="nav-item lh-1 me-3">
                                        <span></span>
                                    </li>
                                    <!-- Authentication Links -->
                                    @guest
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                    @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                    @endif
                                    @else
                                    @php($eventAvailable = false)
                                    @if($events->count() > 0)
                                    @php($eventAvailable = true)
                                    @endif
                                    <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                                        <a class="nav-link dropdown-toggle notification-bell hide-arrow show" href="javascript:void(0);">
                                            <span class="position-relative">
                                                @if($eventAvailable)
                                                <i class='bx bxs-bell-ring bx-sm'></i>
                                                @else
                                                <i class="bx bx-bell bx-sm"></i>
                                                @endif
                                            </span>
                                        </a>
                                        @if($eventAvailable)
                                        <ul class="dropdown-menu dropdown-menu-end p-0 notifications-menu" style="min-width: 22rem;right: 0;left: auto;">
                                            <li class="dropdown-notifications-list scrollable-container ps ps--active-y">
                                                <ul class="list-group list-group-flush">
                                                    @foreach($events as $event)
                                                    <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                                        <div class="d-flex">
                                                            <div class="flex-shrink-0 me-3 mt-1">
                                                                <div class="avatar">
                                                                    <i class='bx bxs-cake bx-sm rounded-circle'></i>
                                                                    <!-- <span class="avatar-initial rounded-circle bg-label-danger">CF</span> -->
                                                                </div>
                                                            </div>
                                                            <?php
                                                                $isSameDay = false;
                                                                if($event->dob) {
                                                                    $givenDate = \Carbon\Carbon::parse($event->dob);
                                                                    $today = \Carbon\Carbon::now();
                                                                    $isSameDay = $givenDate->month === $today->month && $givenDate->day === $today->day;
                                                                }
                                                            ?>
                                                            <div class="flex-grow-1">
                                                                @if($isSameDay)
                                                                <h6 class="mb-0">Happy birthday <a class="color-unset" href="{{ route('user.view', $event->id) }}">{{ $event->full_name }}</a></h6>
                                                                @else
                                                                <h6 class="mb-0">Congratulations <a class="color-unset" href="{{ route('user.view', $event->id) }}">{{ $event->full_name }}</a></h6>
                                                                    @if($event->families)
                                                                        @foreach($event->families as $family)
                                                                            @if($family->type == \App\Models\Family::SPOUSE)
                                                                            <small class="mb-1 mt-1 d-block text-body">Happy Marriage Anniversary</small>
                                                                            @else
                                                                            <small class="mb-1 mt-1 d-block text-body">Happy Birthday to {{ $family->name }} ({{ ucfirst($family->type) }})</small>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                @endif
                                                                <!-- <small class="text-muted">Today</small> -->
                                                            </div>
                                                            <!-- <div class="flex-shrink-0 dropdown-notifications-actions">
                                                                <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                                <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="bx bx-x"></span></a>
                                                            </div> -->
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                                                    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                                                </div>
                                                <div class="ps__rail-y" style="top: 0px; right: 0px; height: 480px;">
                                                    <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 254px;"></div>
                                                </div>
                                            </li>

                                        </ul>
                                        @endif
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }} <span class="caret"></span>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </div>
                                    </li>
                                    @endguest
                                </ul>
                            </div>
                        </nav>
                    </div>
                </nav>
                @endif
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="content-wrapper">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>

    <script src="{{ asset('js/popper.js') }}" defer></script>
    <script src="{{ asset('js/menu.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap.js') }}" defer></script>
    @yield('scripts')
    <script>
        $(document).ready(function() {
            $('.notification-bell').on('click', function() {
                $('.bs-toast').toast('show');
                $('.notifications-menu').toggleClass('show');
            });
        });
    </script>
</body>

</html>