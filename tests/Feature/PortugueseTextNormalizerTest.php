<?php

use App\Services\PortugueseTextNormalizer;

it('folds non-nasal vowel accents but leaves nasal marks alone', function () {
    $normalizer = new PortugueseTextNormalizer;

    expect($normalizer->foldAccents('PÃO'))->toBe('pão')
        ->and($normalizer->foldAccents('MÃO'))->toBe('mão')
        ->and($normalizer->foldAccents('AVÔ'))->toBe('avo')
        ->and($normalizer->foldAccents('ESTÁ'))->toBe('esta');
});

it('does not fold ç to c, since they are distinct Portuguese phonemes', function () {
    $normalizer = new PortugueseTextNormalizer;

    expect($normalizer->foldAccents('AÇÃO'))->toBe('ação');
});

it('collapses whitespace after folding accents', function () {
    $normalizer = new PortugueseTextNormalizer;

    expect($normalizer->collapseWhitespace('  Está   bem  '))->toBe('esta bem');
});

it('splits into unique normalized words, stripping punctuation', function () {
    $normalizer = new PortugueseTextNormalizer;

    $words = $normalizer->uniqueWords('Olá, olá! Como estás?');

    expect($words->values()->all())->toBe(['ola', 'como', 'estas']);
});

it('treats a nasal word and its oral-vowel minimal pair as distinct tokens', function () {
    $normalizer = new PortugueseTextNormalizer;

    $words = $normalizer->uniqueWords('pão pau');

    expect($words->values()->all())->toBe(['pão', 'pau'])
        ->and($words)->toContain('pão')
        ->and($words)->not->toContain('pao');
});
