<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Inertia\Response;

// Temporary: listening page for comparing TTS voices. Removed in HAB-109 PR 1.
final class TtsPreviewController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('TtsPreview', [
            'samples' => File::json(public_path('tts-preview/manifest.json')),
        ]);
    }
}
