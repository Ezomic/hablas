<?php

use App\Http\Middleware\EnsurePlacementTestCompleted;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->middleware(EnsurePlacementTestCompleted::class)->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/socialite.php';
require __DIR__.'/placement.php';
