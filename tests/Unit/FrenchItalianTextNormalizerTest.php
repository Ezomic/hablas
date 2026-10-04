<?php

declare(strict_types=1);

use App\Services\FrenchTextNormalizer;
use App\Services\ItalianTextNormalizer;

it('folds french vowel accents but keeps the cedilla', function () {
    $normalizer = new FrenchTextNormalizer;

    expect($normalizer->foldAccents('ÉTÉ'))->toBe('ete')
        ->and($normalizer->foldAccents('Où'))->toBe('ou')
        ->and($normalizer->foldAccents('Garçon'))->toBe('garçon');
});

it('folds italian vowel accents', function () {
    $normalizer = new ItalianTextNormalizer;

    expect($normalizer->foldAccents('Caffè'))->toBe('caffe')
        ->and($normalizer->foldAccents('È'))->toBe('e')
        ->and($normalizer->foldAccents('Città'))->toBe('citta');
});

it('splits elided words on the apostrophe', function () {
    expect((new FrenchTextNormalizer)->answerKey("J'ai l'eau"))->toBe('j ai l eau')
        ->and((new ItalianTextNormalizer)->answerKey('L’italiano'))->toBe('l italiano');
});

it('sorts elided nouns under the noun and searches oe for the ligature', function () {
    $french = new FrenchTextNormalizer;

    expect($french->sortKey("l'eau"))->toBe('eau')
        ->and((new ItalianTextNormalizer)->sortKey("l'amico"))->toBe('amico')
        ->and($french->searchKey('sœur'))->toBe($french->searchKey('soeur'));
});
