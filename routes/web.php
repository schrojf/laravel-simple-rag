<?php

use App\Http\Controllers\Admin\InvitationCodeController;
use App\Http\Controllers\Auth\LogoutPageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    //
});

Route::middleware('auth')->group(function () {
    Route::get('logout', [LogoutPageController::class, '__invoke'])
        ->name('logout');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::get('/password', [PasswordController::class, 'show'])->name('password');
    });

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('invitation-codes', InvitationCodeController::class);
    });
});
