<?php

declare(strict_types=1);

use App\Speech\SpeechText;

it('composes decomposed accents to NFC', function (): void {
    expect((new SpeechText)->normalise("cafe\u{0301}"))->toBe("caf\u{00E9}");
});

it('trims and collapses whitespace', function (): void {
    expect((new SpeechText)->normalise("  ¿Dónde \t está\n la  plaza?  "))->toBe('¿Dónde está la plaza?');
});

it('unifies apostrophes', function (): void {
    expect((new SpeechText)->normalise("l\u{2019}ami d\u{00B4}Anne c\u{02BC}est"))->toBe("l'ami d'Anne c'est");
});

it('keeps case and punctuation, which carry prosody', function (): void {
    expect((new SpeechText)->normalise('¡Hola, Ana!'))->toBe('¡Hola, Ana!');
});

it('rejects invalid UTF-8 instead of returning an empty string', function (): void {
    (new SpeechText)->normalise("caf\xE9");
})->throws(InvalidArgumentException::class, 'valid UTF-8');
