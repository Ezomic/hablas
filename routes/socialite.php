<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->prefix('auth/google')->group(function () {
    Route::get('redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});
