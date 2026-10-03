<?php

declare(strict_types=1);

use App\Speech\SupertonicEngine;

$supertonic = static fn (string $voice, bool $primary = false): array => [
    'id' => 'supertonic-'.strtolower($voice),
    'engine' => 'supertonic',
    'voice' => $voice,
    'speeds' => ['normal' => 1.05, 'slow' => 0.8],
    'primary' => $primary,
    'license' => 'OpenRAIL-M',
    'attribution' => 'Supertonic 3 by Supertone Inc.',
    'source_url' => 'https://github.com/supertone-inc/supertonic',
];

$voices = static fn (): array => [
    $supertonic('F1', primary: true),
    $supertonic('F2'),
    $supertonic('M1'),
    $supertonic('M2'),
];

return [

    'enabled' => (bool) env('SPEECH_ENABLED', true),

    'disk' => env('SPEECH_DISK', 'public'),

    'normaliser_version' => 1,

    'python' => env('SPEECH_PYTHON', 'python3'),

    'models_dir' => env('SPEECH_MODELS_DIR'),

    'engines' => [
        'supertonic' => SupertonicEngine::class,
    ],

    'languages' => [
        'es' => ['require_audio' => true, 'voices' => $voices()],
        'pt' => ['require_audio' => false, 'voices' => $voices()],
        'fr' => ['require_audio' => false, 'voices' => $voices()],
        'it' => ['require_audio' => false, 'voices' => $voices()],
    ],

];
