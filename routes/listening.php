<?php

use App\Http\Controllers\ListeningExerciseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('listening')->name('listening.')->group(function () {
    Route::get('/', [ListeningExerciseController::class, 'index'])->name('index');
    Route::post('{listeningExercise}/attempts', [ListeningExerciseController::class, 'store'])->name('attempts.store');
});
