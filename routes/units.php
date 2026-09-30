<?php

declare(strict_types=1);

use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('units')->name('units.')->group(function () {
    Route::get('/', [UnitController::class, 'index'])->name('index');
    Route::get('{unit}', [UnitController::class, 'show'])->name('show');
    Route::post('{unit}/completion', [UnitController::class, 'store'])->name('completion.store');
});
