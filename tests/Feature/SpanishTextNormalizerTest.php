<?php

use App\Services\SpanishTextNormalizer;

it('folds vowel accents but leaves ñ alone', function () {
    $normalizer = new SpanishTextNormalizer;

    expect($normalizer->foldAccents('ESTÁ'))->toBe('esta')
        ->and($normalizer->foldAccents('año'))->toBe('año')
        ->and($normalizer->foldAccents('ano'))->toBe('ano');
});

it('collapses whitespace after folding accents', function () {
    $normalizer = new SpanishTextNormalizer;

    expect($normalizer->collapseWhitespace('  Está   bien  '))->toBe('esta bien');
});

it('splits into unique normalized words, stripping punctuation', function () {
    $normalizer = new SpanishTextNormalizer;

    $words = $normalizer->uniqueWords('¡Hola, hola! ¿Qué tal?');

    expect($words->values()->all())->toBe(['hola', 'que', 'tal']);
});
