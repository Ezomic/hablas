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

it('keeps the apostrophe of an elided word and unifies the curly ones', function () {
    $french = new FrenchTextNormalizer;
    $italian = new ItalianTextNormalizer;

    expect($french->answerKey("J'ai l'eau"))->toBe("j'ai l'eau")
        ->and($french->answerKey('J’ai l’eau'))->toBe($french->answerKey("j'ai l'eau"))
        ->and($italian->answerKey('L’italiano'))->toBe("l'italiano")
        ->and($italian->answerKey('l italiano'))->not->toBe($italian->answerKey("l'italiano"))
        ->and($italian->answerKey("'ciao'"))->toBe('ciao')
        ->and($french->uniqueWords("l'eau l'eau")->values()->all())->toBe(["l'eau"]);
});

it('sorts elided nouns under the noun and searches oe for the ligature', function () {
    $french = new FrenchTextNormalizer;

    expect($french->sortKey("l'eau"))->toBe('eau')
        ->and((new ItalianTextNormalizer)->sortKey("l'amico"))->toBe('amico')
        ->and($french->searchKey('sœur'))->toBe($french->searchKey('soeur'));
});

it('strips a hyphen in unique words without splitting the word', function () {
    expect((new ItalianTextNormalizer)->uniqueWords('Capo-reparto, ciao')->values()->all())->toBe(['caporeparto', 'ciao']);
});

it('sorts an italian noun under the noun, not its article', function () {
    $italian = new ItalianTextNormalizer;

    expect($italian->sortKey('il volo'))->toBe('volo')
        ->and($italian->sortKey('gli aeroporti'))->toBe('aeroporti')
        ->and($italian->accentWords())->toContain('è', 'città');
});
