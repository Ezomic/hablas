<?php

declare(strict_types=1);

use App\Http\Controllers\ExercisePauseController;
use App\Http\Controllers\LessonAnswerController;
use App\Http\Controllers\LessonAnswerFlagController;
use App\Http\Controllers\LessonRunController;
use App\Http\Controllers\SpeakingTryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('units/{unit}/lessons/{lesson}/runs', [LessonRunController::class, 'store'])
        ->scopeBindings()
        ->name('lessons.runs.store');

    Route::get('lesson-runs/{lessonRun}', [LessonRunController::class, 'show'])
        ->name('lesson-runs.show');

    Route::post('lesson-runs/{lessonRun}/answers/{step}', [LessonAnswerController::class, 'store'])
        ->whereUuid('step')
        ->name('lesson-runs.answers.store');

    Route::post('lesson-runs/{lessonRun}/answers/{step}/flag', [LessonAnswerFlagController::class, 'store'])
        ->whereUuid('step')
        ->name('lesson-runs.answers.flag.store');

    Route::post('lesson-runs/{lessonRun}/exercises/{lessonExercise}/speaking-tries', [SpeakingTryController::class, 'store'])
        ->middleware('throttle:30,1')
        ->name('lesson-runs.speaking-tries.store');

    Route::post('exercise-pauses/{family}', [ExercisePauseController::class, 'store'])
        ->whereIn('family', ['listening', 'speaking'])
        ->name('exercise-pauses.store');
});
