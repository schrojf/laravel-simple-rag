<?php

use App\Http\Controllers\Auth\LogoutPageController;
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
});
