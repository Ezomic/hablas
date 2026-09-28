<?php

use App\Http\Controllers\Auth\EmailCodeController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;

// Requesting a sign-in code. Fortify still owns POST /login itself; it just
// verifies a code instead of a password (see FortifyServiceProvider).
Route::middleware(['guest', 'throttle:login-code'])
    ->post('login/code', [EmailCodeController::class, 'store'])
    ->name('login.code.store');

// Re-authentication before a sensitive action, replacing password confirmation.
Route::middleware(['auth', 'throttle:login-code'])
    ->post('user/confirm-code', [EmailCodeController::class, 'confirm'])
    ->name('user.confirm-code.store');

// Fortify registers this route with no limiter hook. App routes load after
// Fortify's, so declaring the same method, URI and name replaces its route
// with a throttled one.
Route::middleware(['auth', 'throttle:confirm-password'])
    ->post('user/confirm-password', [ConfirmablePasswordController::class, 'store'])
    ->name('password.confirm.store');
