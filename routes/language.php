<?php

use App\Http\Controllers\LanguageSwitchController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->patch('language', [LanguageSwitchController::class, 'update'])->name('language.update');
