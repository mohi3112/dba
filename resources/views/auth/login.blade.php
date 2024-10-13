@extends('layouts.app')

<link href="{{ asset('css/page-auth.css') }}" rel="stylesheet">

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Login -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <!-- <div class="app-brand justify-content-center">
                        <a href="index.html" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo"></span>
                            <span class="app-brand-text demo text-body fw-bolder">Sneat</span>
                        </a>
                    </div> -->
                    <!-- /Logo -->
                    <!-- <h4 class="mb-2">Welcome to Sneat! 👋</h4>
              <p class="mb-4">Please sign-in to your account and start the adventure</p> -->
                    <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
                            <input id="email" type="text" placeholder="Enter your email or mobile" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="col-form-label">{{ __('Password') }}</label>
                                <!-- <a href="auth-forgot-password-basic.html"><small>Forgot Password?</small></a> -->
                            </div>
                            <div class="input-group input-group-merge">
                                <input id="password" type="password" aria-describedby="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>
                        <!-- <div class="mb-3"><div class="form-check"> <input class="form-check-input" type="checkbox" id="remember-me" />
                            <label class="form-check-label" for="remember-me"> Remember Me </label> </div> </div> -->
                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">{{ __('Login') }}</button>
                        </div>
                    </form>

                    <p class="text-center">
                        <span>New on our platform?</span>
                        <a href="{{ route('register') }}">
                            <span>{{ __('Register') }}</span>
                        </a>
                    </p>
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>
</div>

@endsection