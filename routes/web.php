<?php

use App\Http\Controllers\Admin\InvitationCodeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LogoutPageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\EntryTypeController;
use App\Http\Controllers\McpLogController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\TokenController;
use App\Http\Controllers\Settings\TwoFactorController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    //
});

Route::middleware(['auth'])->group(function () {
    Route::get('logout', [LogoutPageController::class, '__invoke']);

    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', [ProfileController::class, 'show'])->name('settings.profile');
    Route::get('settings/password', [PasswordController::class, 'show'])->name('settings.password');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('settings/tokens', [TokenController::class, 'show'])->name('settings.tokens');
    Route::post('settings/tokens', [TokenController::class, 'store'])->name('settings.tokens.store')->middleware(['password.confirm']);
    Route::delete('settings/tokens/{token}', [TokenController::class, 'destroy'])->name('settings.tokens.destroy')->middleware(['password.confirm']);

    Route::get('settings/two-factor', [TwoFactorController::class, 'show'])
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::resource('entries', EntryController::class);
    Route::resource('entries.responses', ResponseController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('entry-types', EntryTypeController::class)->except('show');
    Route::resource('topics', TopicController::class)->except('show');
    Route::get('mcp-logs', [McpLogController::class, 'index'])->name('mcp-logs.index');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->only(['index', 'show']);
        Route::resource('invitation-codes', InvitationCodeController::class);
    });
});
