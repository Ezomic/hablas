<?php

declare(strict_types=1);

use App\Speech\SupertonicEngine;

$openRailMRestrictions = [
    'law' => 'In any way that violates any applicable law or regulation.',
    'minors' => 'To exploit or harm minors, or attempt to.',
    'disinformation' => 'To generate or spread verifiably false information in order to harm others.',
    'pii' => 'To generate or spread personal information that can be used to harm an individual.',
    'machine_generated' => 'To place generated content in any context without clearly saying it is machine generated.',
    'harassment' => 'To defame, disparage or harass others.',
    'impersonation' => 'To impersonate others without their consent, for example with deepfakes.',
    'automated_decisions' => 'For fully automated decisions that adversely affect the legal rights of a person or create or modify a binding, enforceable obligation.',
    'social_discrimination' => 'To discriminate against or harm people based on online or offline social behaviour or personal characteristics.',
    'vulnerable_groups' => 'To exploit the vulnerabilities of a group of people, based on age or social, physical or mental characteristics, in order to materially distort their behaviour in a way that is likely to cause physical or psychological harm.',
    'protected_discrimination' => 'To discriminate against people based on legally protected characteristics.',
    'medical' => 'To give medical advice or interpret medical results.',
    'justice' => 'To generate or spread information meant for administration of justice, law enforcement, immigration or asylum processes.',
];

$supertonic = static fn (string $voice, bool $primary = false): array => [
    'id' => 'supertonic-'.strtolower($voice),
    'engine' => 'supertonic',
    'voice' => $voice,
    'speeds' => ['normal' => 1.05, 'slow' => 0.8],
    'primary' => $primary,
    'license' => 'BigScience OpenRAIL-M License (18 August 2022)',
    'license_url' => 'https://huggingface.co/Supertone/supertonic-3/blob/main/LICENSE',
    'attribution' => 'Supertonic 3 by Supertone Inc. Copyright (c) 2026 Supertone Inc.',
    'source_url' => 'https://huggingface.co/Supertone/supertonic-3',
    'restrictions' => $openRailMRestrictions,
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
