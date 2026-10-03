<?php

declare(strict_types=1);

use App\Enums\SpeechSpeed;
use App\Speech\SpeechEngine;
use App\Speech\SpeechEngineManager;
use App\Speech\SpeechVoices;
use App\Speech\VoiceConfig;

final class FakeSpeechEngine implements SpeechEngine
{
    public function synthesize(VoiceConfig $voice, SpeechSpeed $speed, string $text): string
    {
        return "{$voice->id}|{$speed->value}|{$text}";
    }

    public function synthesizeBatch(VoiceConfig $voice, SpeechSpeed $speed, array $texts): array
    {
        return array_map(fn (string $text): string => $this->synthesize($voice, $speed, $text), $texts);
    }
}

it('resolves the engine named in config', function (): void {
    config(['speech.engines.supertonic' => FakeSpeechEngine::class]);

    $voice = app(SpeechVoices::class)->primary('es');
    $engine = app(SpeechEngineManager::class)->forVoice($voice);

    expect($engine)->toBeInstanceOf(FakeSpeechEngine::class)
        ->and($engine->synthesize($voice, SpeechSpeed::Slow, 'Hola'))->toBe('supertonic-f1|slow|Hola');
});

it('rejects an engine that is not configured', function (): void {
    app(SpeechEngineManager::class)->engine('missing');
})->throws(InvalidArgumentException::class, 'Speech engine [missing] is not configured.');

it('rejects a configured class that is not an engine', function (): void {
    config(['speech.engines.odd' => stdClass::class]);

    app(SpeechEngineManager::class)->engine('odd');
})->throws(InvalidArgumentException::class, 'must implement');
