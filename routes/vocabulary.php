<?php

declare(strict_types=1);

use App\Http\Controllers\VocabularyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('vocabulary', [VocabularyController::class, 'index'])->name('vocabulary.index');
});
