<?php

use App\Http\Controllers\ShadowingExerciseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('shadowing')->name('shadowing.')->group(function () {
    Route::get('/', [ShadowingExerciseController::class, 'index'])->name('index');
    Route::post('/{shadowingExercise}/attempts', [ShadowingExerciseController::class, 'store'])->name('attempts.store');
});
