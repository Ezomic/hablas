<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

final class WebManifestController extends Controller
{
    public const string ID = '/dashboard';

    public const string START_URL = '/?source=pwa';

    public function __invoke(): JsonResponse
    {
        return response()->json([
            'id' => self::ID,
            'name' => 'Hablas',
            'short_name' => 'Hablas',
            'description' => 'Spanish/Portuguese learning app',
            'start_url' => self::START_URL,
            'scope' => '/',
            'display' => 'standalone',
            'theme_color' => '#be185d',
            'background_color' => '#be185d',
            'icons' => [
                ['src' => '/pwa-192.png', 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any'],
                ['src' => '/pwa-512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any'],
                ['src' => '/pwa-maskable-512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'maskable'],
            ],
        ], headers: ['Content-Type' => 'application/manifest+json']);
    }
}
