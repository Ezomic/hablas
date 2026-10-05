<?php

declare(strict_types=1);

use App\Http\Controllers\ContinueController;
use App\Http\Controllers\CreditsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\TtsPreviewController;
use App\Http\Controllers\WebManifestController;
use App\Http\Middleware\EnsurePlacementTestCompleted;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\SetLocale;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

Route::get('/', HomeController::class)->name('home');

Route::get('credits', CreditsController::class)->name('credits');

Route::get('manifest.webmanifest', WebManifestController::class)
    ->withoutMiddleware([
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        ShareErrorsFromSession::class,
        PreventRequestForgery::class,
        HandleAppearance::class,
        SetLocale::class,
        HandleInertiaRequests::class,
    ])
    ->name('manifest');

// Temporary, removed in HAB-109 PR 1. No nav link, reachable by URL only.
Route::get('voice-test', TtsPreviewController::class)->middleware('auth')->name('tts-preview');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('continue', ContinueController::class)
        ->middleware(EnsurePlacementTestCompleted::class)
        ->name('continue');
    Route::get('practice', [PracticeController::class, 'index'])->name('practice');
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware(EnsurePlacementTestCompleted::class)
        ->name('dashboard');
});

require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
require __DIR__.'/placement.php';
require __DIR__.'/units.php';
require __DIR__.'/lessons.php';
require __DIR__.'/reading.php';
require __DIR__.'/listening.php';
require __DIR__.'/shadowing.php';
require __DIR__.'/writing.php';
require __DIR__.'/reflections.php';
require __DIR__.'/review.php';
require __DIR__.'/language.php';
require __DIR__.'/scripted-prompts.php';
require __DIR__.'/pronunciation-drills.php';
require __DIR__.'/progress.php';
require __DIR__.'/vocabulary.php';
