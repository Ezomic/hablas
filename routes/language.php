<?php

declare(strict_types=1);

use App\Http\Controllers\LanguageActivationController;
use App\Http\Controllers\LanguageSwitchController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::patch('language', [LanguageSwitchController::class, 'update'])->name('language.update');
    Route::post('language/{language:code}/activate', [LanguageActivationController::class, 'store'])->name('language.activate');
});
