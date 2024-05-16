<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubscriptionController;


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
    Route::get('/home', 'HomeController@index')->name('dashboard');

    // Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    // Route::resource('users', 'UserController');

    // Routes for User
    Route::get('users', [UserController::class, 'index'])->name('users');
    Route::get('users/add', [UserController::class, 'create'])->name('users.add');
    Route::post('user/store',  [UserController::class, 'store'])->name('user.store');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('user/{id}', [UserController::class, 'destroy'])->name('users.destroy');


    // Routes for Subscription
    Route::resource('subscriptions', SubscriptionController::class);

    // Define routes that require authentication here
    // Route::get('/admin/dashboard', 'AdminController@dashboard');
    // ->middleware('checkrole:superadmin');
    // Add more routes as needed
});
