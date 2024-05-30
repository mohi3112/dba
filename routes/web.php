<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\VendorController;

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

    // Routes for Lawyer / User
    Route::get('lawyers', [UserController::class, 'index'])->name('users');
    Route::get('lawyers/add', [UserController::class, 'create'])->name('users.add');
    Route::post('lawyer/store',  [UserController::class, 'store'])->name('user.store');
    Route::get('lawyer/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('lawyers/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('lawyer/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('lawyer/{id}', [UserController::class, 'show'])->name('user.view');
    Route::get('lawyers/telephone-directory', [UserController::class, 'telephoneDirectory'])->name('users.telephone-directory');
    Route::delete('delete-lawyer-image/{id}', [UserController::class, 'deleteImage'])->name('delete-image');

    // Routes for Address Proof
    Route::delete('delete-address-proof-image/{id}', [UserController::class, 'deleteAddressProofImage'])->name('delete-address-proof-image');

    // Routes for Address Proof
    Route::delete('delete-degree-image/{id}', [UserController::class, 'deleteDegreeImage'])->name('delete-degree-image');

    // Routes for Subscription
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions');
    Route::get('subscription/{id}', [SubscriptionController::class, 'show'])->name('subscription.view');
    Route::get('subscriptions/add', [SubscriptionController::class, 'create'])->name('subscriptions.add');
    Route::post('subscription/store',  [SubscriptionController::class, 'store'])->name('subscription.store');
    Route::get('subscription/edit/{id}', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::put('subscriptions/update/{id}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::post('subscription/{id}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    // Routes for Roles
    Route::get('roles', [UserRoleController::class, 'index'])->name('roles');
    Route::get('roles/add', [UserRoleController::class, 'create'])->name('roles.add');
    Route::post('role/store',  [UserRoleController::class, 'store'])->name('role.store');
    Route::get('role/edit/{id}', [UserRoleController::class, 'edit'])->name('roles.edit');
    Route::put('roles/update/{id}', [UserRoleController::class, 'update'])->name('roles.update');

    // Routes for Books
    Route::get('books', [BookController::class, 'index'])->name('books');
    Route::get('book/{id}', [BookController::class, 'show'])->name('book.view');
    Route::get('books/add', [BookController::class, 'create'])->name('books.add');
    Route::post('book/store',  [BookController::class, 'store'])->name('book.store');
    Route::get('book/edit/{id}', [BookController::class, 'edit'])->name('books.edit');
    Route::put('books/update/{id}', [BookController::class, 'update'])->name('books.update');
    Route::post('book/{id}', [BookController::class, 'destroy'])->name('books.destroy');
    // issue a book
    Route::post('issue-book', [BookController::class, 'issueBook'])->name('book.issue');
    Route::get('books/issued-books', [BookController::class, 'getAllIssuedBooks'])->name('books.issued-books');
    Route::post('return_book/{id}', [BookController::class, 'returnBook'])->name('book.return_book');

    // Routes for payments
    Route::get('payments', [PaymentController::class, 'index'])->name('payments');
    Route::get('payment/{id}', [PaymentController::class, 'show'])->name('payment.view');
    Route::get('payments/add', [PaymentController::class, 'create'])->name('payments.add');
    Route::post('payment/store',  [PaymentController::class, 'store'])->name('payment.store');
    Route::get('payment/edit/{id}', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('payments/update/{id}', [PaymentController::class, 'update'])->name('payments.update');
    Route::post('payment/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    Route::delete('delete-payment-image/{id}', [PaymentController::class, 'deleteImage'])->name('delete-payment-image');

    // Routes for locations
    Route::get('locations', [LocationController::class, 'index'])->name('locations');
    Route::get('location/{id}', [LocationController::class, 'show'])->name('location.view');
    Route::get('locations/add', [LocationController::class, 'create'])->name('locations.add');
    Route::post('location/store',  [LocationController::class, 'store'])->name('location.store');
    Route::get('location/edit/{id}', [LocationController::class, 'edit'])->name('locations.edit');
    Route::put('locations/update/{id}', [LocationController::class, 'update'])->name('locations.update');
    Route::post('location/{id}', [LocationController::class, 'destroy'])->name('locations.destroy');

    // Routes for locations
    Route::get('vendors', [VendorController::class, 'index'])->name('vendors');
    Route::get('vendor/{id}', [VendorController::class, 'show'])->name('vendor.view');
    Route::get('vendors/add', [VendorController::class, 'create'])->name('vendors.add');
    Route::post('vendor/store',  [VendorController::class, 'store'])->name('vendor.store');
    Route::get('vendor/edit/{id}', [VendorController::class, 'edit'])->name('vendors.edit');
    Route::put('vendors/update/{id}', [VendorController::class, 'update'])->name('vendors.update');
    Route::post('vendor/{id}', [VendorController::class, 'destroy'])->name('vendors.destroy');

    // Define routes that require authentication here
    // Route::get('/admin/dashboard', 'AdminController@dashboard');
    // ->middleware('checkrole:superadmin');
    // Add more routes as needed
});
