<?php

use App\Http\Controllers\ProgressShareController;
use App\Http\Controllers\PublicProgressController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('progress')->name('progress.')->group(function () {
    Route::get('share', [ProgressShareController::class, 'show'])->name('share.show');
    Route::post('share/regenerate', [ProgressShareController::class, 'regenerate'])->name('share.regenerate');
});

Route::get('shared/{token}', [PublicProgressController::class, 'show'])->name('progress.public');
