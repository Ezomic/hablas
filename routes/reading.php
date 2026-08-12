<?php

use App\Http\Controllers\ReadingExerciseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('reading')->name('reading.')->group(function () {
    Route::get('/', [ReadingExerciseController::class, 'index'])->name('index');
    Route::post('{readingPassage}/attempts', [ReadingExerciseController::class, 'store'])->name('attempts.store');
});
