<?php

declare(strict_types=1);

use App\Enums\SpeechSpeed;
use App\Speech\SpeechKey;
use App\Speech\SpeechText;
use Illuminate\Config\Repository;

function speechKey(int $version = 1): SpeechKey
{
    return new SpeechKey(new SpeechText, new Repository(['speech' => ['normaliser_version' => $version]]));
}

it('is a stable sha256', function (): void {
    $key = speechKey()->make('es', 'supertonic-f1', SpeechSpeed::Normal, 'Hola');

    expect($key)->toBe(hash('sha256', '1|es|supertonic-f1|normal|Hola'))
        ->and($key)->toBe(speechKey()->make('es', 'supertonic-f1', SpeechSpeed::Normal, 'Hola'));
});

it('treats text that normalises the same as one string', function (): void {
    expect(speechKey()->make('es', 'v', SpeechSpeed::Normal, '  Hola   Ana '))
        ->toBe(speechKey()->make('es', 'v', SpeechSpeed::Normal, 'Hola Ana'));
});

it('differs per normaliser version, language, voice, speed and text', function (): void {
    $keys = [
        speechKey()->make('es', 'v1', SpeechSpeed::Normal, 'Hola'),
        speechKey(2)->make('es', 'v1', SpeechSpeed::Normal, 'Hola'),
        speechKey()->make('pt', 'v1', SpeechSpeed::Normal, 'Hola'),
        speechKey()->make('es', 'v2', SpeechSpeed::Normal, 'Hola'),
        speechKey()->make('es', 'v1', SpeechSpeed::Slow, 'Hola'),
        speechKey()->make('es', 'v1', SpeechSpeed::Normal, 'hola'),
    ];

    expect(array_unique($keys))->toHaveCount(6);
});
