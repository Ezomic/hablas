<?php

use App\Http\Controllers\ReviewSessionController;
use App\Http\Controllers\WeakSpotReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('review')->name('review.')->group(function () {
    Route::get('/', [ReviewSessionController::class, 'index'])->name('index');
    Route::post('/{srsCard}/reviews', [ReviewSessionController::class, 'store'])->name('reviews.store');

    Route::get('weak-spots', [WeakSpotReviewController::class, 'index'])->name('weak-spots.index');
    Route::post('weak-spots/{srsCard}/reviews', [WeakSpotReviewController::class, 'store'])->name('weak-spots.reviews.store');
});
