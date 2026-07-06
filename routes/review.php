<?php

use App\Http\Controllers\ReviewSessionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('review')->name('review.')->group(function () {
    Route::get('/', [ReviewSessionController::class, 'index'])->name('index');
    Route::post('/{srsCard}/reviews', [ReviewSessionController::class, 'store'])->name('reviews.store');
});
