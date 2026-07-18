<?php

use App\Http\Controllers\PlacementTestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('placement')->name('placement.')->group(function () {
    Route::get('/', [PlacementTestController::class, 'index'])->name('index');
    Route::get('/results', [PlacementTestController::class, 'results'])->name('results');
    Route::post('/{item}/answer', [PlacementTestController::class, 'answer'])->name('answer');
    Route::post('/skip', [PlacementTestController::class, 'skip'])->name('skip');
});
