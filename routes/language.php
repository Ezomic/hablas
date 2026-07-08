<?php

use App\Http\Controllers\LanguageSwitchController;
use App\Http\Controllers\PortugueseActivationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::patch('language', [LanguageSwitchController::class, 'update'])->name('language.update');
    Route::post('language/portuguese/activate', [PortugueseActivationController::class, 'store'])->name('language.activate-portuguese');
});
