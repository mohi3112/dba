<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PaymentController;


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

    // Routes for Books
    Route::get('payments', [PaymentController::class, 'index'])->name('payments');
    Route::get('payment/{id}', [PaymentController::class, 'show'])->name('payment.view');
    Route::get('payments/add', [PaymentController::class, 'create'])->name('payments.add');
    Route::post('payment/store',  [PaymentController::class, 'store'])->name('payment.store');
    Route::get('payment/edit/{id}', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('payments/update/{id}', [PaymentController::class, 'update'])->name('payments.update');
    Route::post('payment/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    Route::delete('delete-payment-image/{id}', [PaymentController::class, 'deleteImage'])->name('delete-payment-image');

    // Define routes that require authentication here
    // Route::get('/admin/dashboard', 'AdminController@dashboard');
    // ->middleware('checkrole:superadmin');
    // Add more routes as needed
});
