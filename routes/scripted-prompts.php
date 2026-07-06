<?php

use App\Http\Controllers\ScriptedPromptExerciseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('scripted-prompts')->name('scripted-prompts.')->group(function () {
    Route::get('/', [ScriptedPromptExerciseController::class, 'index'])->name('index');
    Route::post('/{scriptedPromptExercise}/attempts', [ScriptedPromptExerciseController::class, 'store'])->name('attempts.store');
});
