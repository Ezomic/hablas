<?php

declare(strict_types=1);

use App\Enums\SpeechSpeed;
use App\Speech\SpeechVoices;

it('configures voices for the four languages', function (): void {
    $voices = app(SpeechVoices::class);

    foreach (['es', 'pt', 'fr', 'it'] as $language) {
        expect(array_map(fn ($voice) => $voice->voice, $voices->forLanguage($language)))->toBe(['F1', 'F2', 'M1', 'M2']);
    }
});

it('gives every voice a licence, attribution and source url', function (): void {
    foreach (['es', 'pt', 'fr', 'it'] as $language) {
        foreach (app(SpeechVoices::class)->forLanguage($language) as $voice) {
            expect($voice->license)->not->toBe('')
                ->and($voice->attribution)->not->toBe('')
                ->and($voice->sourceUrl)->toStartWith('https://')
                ->and($voice->licenseUrl)->toStartWith('https://');
        }
    }
});

it('has unique voice ids and exactly one primary per language', function (): void {
    foreach (['es', 'pt', 'fr', 'it'] as $language) {
        $voices = app(SpeechVoices::class)->forLanguage($language);

        expect(array_unique(array_map(fn ($voice) => $voice->id, $voices)))->toHaveCount(count($voices))
            ->and(array_filter($voices, fn ($voice) => $voice->primary))->toHaveCount(1);
    }
});

it('maps the speed variants to engine speeds', function (): void {
    $voice = app(SpeechVoices::class)->primary('es');

    expect($voice->speedFor(SpeechSpeed::Normal))->toBe(1.05)
        ->and($voice->speedFor(SpeechSpeed::Slow))->toBe(0.8);
});

it('requires audio for spanish only', function (): void {
    $voices = app(SpeechVoices::class);

    expect($voices->requiresAudio('es'))->toBeTrue()
        ->and($voices->requiresAudio('pt'))->toBeFalse()
        ->and($voices->requiresAudio('fr'))->toBeFalse()
        ->and($voices->requiresAudio('it'))->toBeFalse()
        ->and($voices->requiresAudio('xx'))->toBeFalse();
});

it('finds a voice by id and has no voices for an unknown language', function (): void {
    $voices = app(SpeechVoices::class);

    expect($voices->find('es', 'supertonic-m2')?->voice)->toBe('M2')
        ->and($voices->find('es', 'nope'))->toBeNull()
        ->and($voices->forLanguage('xx'))->toBe([])
        ->and($voices->primary('xx'))->toBeNull();
});

it('falls back to the first voice when none is marked primary', function (): void {
    config(['speech.languages.es.voices.0.primary' => false]);

    expect(app(SpeechVoices::class)->primary('es')?->id)->toBe('supertonic-f1');
});

it('keeps voice ids and language codes safe for storage paths', function (): void {
    foreach (['es', 'pt', 'fr', 'it'] as $language) {
        expect($language)->toMatch('/^[a-z0-9-]+$/');

        foreach (app(SpeechVoices::class)->forLanguage($language) as $voice) {
            expect($voice->id)->toMatch('/^[a-z0-9-]+$/');
        }
    }
});

it('carries the openrail-m use restrictions on every supertonic voice', function (): void {
    foreach (['es', 'pt', 'fr', 'it'] as $language) {
        foreach (app(SpeechVoices::class)->forLanguage($language) as $voice) {
            expect($voice->license)->toContain('OpenRAIL-M')
                ->and($voice->restrictions)->toHaveCount(13);
        }
    }
});
