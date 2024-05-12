<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/', 'Auth\LoginController@showLoginForm');

Route::middleware('auth')->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/dashboard', 'DashboardController@index');
    // Define routes that require authentication here
    // Route::get('/admin/dashboard', 'AdminController@dashboard');
    // ->middleware('checkrole:superadmin');
    // Add more routes as needed
});
