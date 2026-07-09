<?php

use App\Http\Controllers\DashboardController;
use App\Http\Middleware\EnsurePlacementTestCompleted;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware(EnsurePlacementTestCompleted::class)
        ->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/socialite.php';
require __DIR__.'/placement.php';
require __DIR__.'/shadowing.php';
require __DIR__.'/writing.php';
require __DIR__.'/reflections.php';
require __DIR__.'/review.php';
require __DIR__.'/language.php';
require __DIR__.'/scripted-prompts.php';
require __DIR__.'/pronunciation-drills.php';
require __DIR__.'/progress.php';
