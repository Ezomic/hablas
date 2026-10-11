<?php

declare(strict_types=1);

use App\Http\Controllers\DutchReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dutch-review', [DutchReviewController::class, 'index'])->name('dutch-review.index');
    Route::post('dutch-review/{vocabularyItem}', [DutchReviewController::class, 'store'])->name('dutch-review.store');
});
