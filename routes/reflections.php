<?php

use App\Http\Controllers\WeeklyReflectionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('reflections')->name('reflections.')->group(function () {
    Route::get('/', [WeeklyReflectionController::class, 'index'])->name('index');
    Route::post('/', [WeeklyReflectionController::class, 'store'])->name('store');
});
