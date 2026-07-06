<?php

use App\Http\Controllers\WritingExerciseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('writing')->name('writing.')->group(function () {
    Route::get('/', [WritingExerciseController::class, 'index'])->name('index');
    Route::post('/{writingExercise}/attempts', [WritingExerciseController::class, 'store'])->name('attempts.store');
});
