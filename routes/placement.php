<?php

use App\Http\Controllers\PlacementTestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('placement')->name('placement.')->group(function () {
    Route::get('/', [PlacementTestController::class, 'index'])->name('index');
    Route::post('/', [PlacementTestController::class, 'store'])->name('store');
    Route::post('/skip', [PlacementTestController::class, 'skip'])->name('skip');
});
