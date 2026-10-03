<?php

declare(strict_types=1);

use App\Enums\SpeechSpeed;
use App\Models\SpeechClip;
use App\Speech\SpeechClipResolver;
use App\Speech\SpeechKey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('public');
    config(['speech.disk' => 'public']);
});

function storeClip(string $language, string $voiceId, SpeechSpeed $speed, string $text): SpeechClip
{
    return SpeechClip::query()->create([
        'language' => $language,
        'voice_id' => $voiceId,
        'speed' => $speed,
        'hash' => app(SpeechKey::class)->make($language, $voiceId, $speed, $text),
        'bytes' => 1000,
    ]);
}

it('returns a url for a stored clip and null for the rest', function (): void {
    $clip = storeClip('es', 'supertonic-f1', SpeechSpeed::Normal, 'Hola');

    $urls = app(SpeechClipResolver::class)->resolve('es', ['Hola', 'Adiós']);

    expect($urls['Hola'])->toBe(Storage::disk('public')->url($clip->path()))
        ->and($urls['Hola'])->toContain("speech/es/supertonic-f1/{$clip->hash[0]}{$clip->hash[1]}/{$clip->hash}.mp3")
        ->and($urls['Adiós'])->toBeNull();
});

it('looks every text up with one query', function (): void {
    storeClip('es', 'supertonic-f1', SpeechSpeed::Normal, 'Hola');

    DB::enableQueryLog();
    app(SpeechClipResolver::class)->resolve('es', ['Hola', 'Adiós', 'Gracias']);

    expect(DB::getQueryLog())->toHaveCount(1);
});

it('keys the lookup on the speed variant', function (): void {
    storeClip('es', 'supertonic-f1', SpeechSpeed::Normal, 'Hola');

    $resolver = app(SpeechClipResolver::class);

    expect($resolver->resolve('es', ['Hola'], speed: SpeechSpeed::Slow)['Hola'])->toBeNull()
        ->and($resolver->resolve('es', ['Hola'], speed: SpeechSpeed::Normal)['Hola'])->not->toBeNull();
});

it('keys the lookup on the voice, defaulting to the primary', function (): void {
    storeClip('es', 'supertonic-m1', SpeechSpeed::Normal, 'Hola');

    $resolver = app(SpeechClipResolver::class);

    expect($resolver->resolve('es', ['Hola'])['Hola'])->toBeNull()
        ->and($resolver->resolve('es', ['Hola'], 'supertonic-m1')['Hola'])->not->toBeNull();
});

it('matches text that normalises to a stored string', function (): void {
    storeClip('es', 'supertonic-f1', SpeechSpeed::Normal, 'Hola Ana');

    expect(app(SpeechClipResolver::class)->resolve('es', ['  Hola   Ana '])['  Hola   Ana '])->not->toBeNull();
});

it('returns nothing, without a query, when speech is disabled', function (): void {
    storeClip('es', 'supertonic-f1', SpeechSpeed::Normal, 'Hola');
    config(['speech.enabled' => false]);

    DB::enableQueryLog();

    expect(app(SpeechClipResolver::class)->resolve('es', ['Hola']))->toBe(['Hola' => null])
        ->and(DB::getQueryLog())->toBe([]);
});

it('returns nothing for an empty list, an unknown voice or an unknown language', function (): void {
    $resolver = app(SpeechClipResolver::class);

    expect($resolver->resolve('es', []))->toBe([])
        ->and($resolver->resolve('es', ['Hola'], 'nope'))->toBe(['Hola' => null])
        ->and($resolver->resolve('xx', ['Hola']))->toBe(['Hola' => null]);
});
