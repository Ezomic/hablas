<?php

declare(strict_types=1);

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

it('folds the nasal marks and ç for search, but not for grading', function () {
    $normalizer = new PortugueseTextNormalizer;

    expect($normalizer->searchKey('Ação'))->toBe('acao')
        ->and($normalizer->searchKey('PÃO'))->toBe('pao')
        ->and($normalizer->searchKey('lições'))->toBe('licoes')
        ->and($normalizer->foldAccents('ação'))->toBe('ação');
});

it('turns punctuation into spaces for search', function () {
    $normalizer = new PortugueseTextNormalizer;

    expect($normalizer->searchKey('o pequeno-almoço'))->toBe('o pequeno almoco')
        ->and($normalizer->searchKey('Onde fica...?'))->toBe('onde fica');
});

it('drops a leading article from the sort key', function (string $term, string $key) {
    expect((new PortugueseTextNormalizer)->sortKey($term))->toBe($key);
})->with([
    ['o aeroporto', 'aeroporto'],
    ['a mala', 'mala'],
    ['As calças', 'calcas'],
    ['uma casa', 'casa'],
    ['à direita', 'a direita'],
    ['a', 'a'],
]);
