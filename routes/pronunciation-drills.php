<?php

use App\Http\Controllers\PronunciationDrillExerciseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('pronunciation-drills')->name('pronunciation-drills.')->group(function () {
    Route::get('/', [PronunciationDrillExerciseController::class, 'index'])->name('index');
    Route::post('/{pronunciationDrillExercise}/attempts', [PronunciationDrillExerciseController::class, 'store'])->name('attempts.store');
});
