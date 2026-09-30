<?php

declare(strict_types=1);

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

it('folds ñ for search, so an unaccented query still finds the word', function () {
    $normalizer = new SpanishTextNormalizer;

    expect($normalizer->searchKey('AÑO'))->toBe('ano')
        ->and($normalizer->searchKey('Adiós'))->toBe('adios')
        ->and($normalizer->foldAccents('año'))->toBe('año');
});

it('turns punctuation into spaces for search, keeping every word', function () {
    $normalizer = new SpanishTextNormalizer;

    expect($normalizer->searchKey('¿Dónde está...?'))->toBe('donde esta')
        ->and($normalizer->searchKey('  poco   a poco '))->toBe('poco a poco')
        ->and($normalizer->searchKey('well / fine'))->toBe('well fine');
});

it('drops a leading article from the sort key', function (string $term, string $key) {
    expect((new SpanishTextNormalizer)->sortKey($term))->toBe($key);
})->with([
    ['el aeropuerto', 'aeropuerto'],
    ['La Maleta', 'maleta'],
    ['los pantalones', 'pantalones'],
    ['unas llaves', 'llaves'],
    ['a la derecha', 'a la derecha'],
    ['él come', 'el come'],
    ['la', 'la'],
    ['¿cómo estás?', 'como estas'],
    ['¡hola!', 'hola'],
]);
