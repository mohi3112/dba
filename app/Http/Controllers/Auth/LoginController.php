<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override the username method to check for either email or mobile.
     *
     * @return string
     */
    public function username()
    {
        $login = request()->input('email');

        // Check if input is an email or mobile number
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile1';

        // Modify the request to contain the correct field
        request()->merge([$field => $login]);

        return $field;
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        // Validate login for either email or mobile and password
        $request->validate([
            'email' => 'required|string',  // Either email or mobile
            'password' => 'required|string',
        ]);
    }
}
