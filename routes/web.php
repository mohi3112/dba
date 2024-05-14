<?php

use App\Http\Controllers\UserController;
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
    Route::get('/home', 'HomeController@index')->name('dashboard');

    // Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    // Routes for User CRUD operations
    // Route::resource('users', 'UserController');
    Route::get('users', [UserController::class, 'index'])->name('users');
    Route::get('users/add', [UserController::class, 'create'])->name('users.add');
    Route::post('user/store',  [UserController::class, 'store'])->name('user.store');

    // Define routes that require authentication here
    // Route::get('/admin/dashboard', 'AdminController@dashboard');
    // ->middleware('checkrole:superadmin');
    // Add more routes as needed
});
