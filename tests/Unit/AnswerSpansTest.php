<?php

declare(strict_types=1);

use App\Lessons\AnswerSpans;
use App\Services\FrenchTextNormalizer;

it('finds a target by its first form present in the answer', function () {
    $spans = new AnswerSpans;
    $normalizer = new FrenchTextNormalizer;
    $forms = ['t' => ['tous les jours', 'chaque jour']];

    expect($spans->find($normalizer, 'Je travaille tous les jours.', $forms))->toBe(['t' => [2, 3]])
        ->and($spans->find($normalizer, 'Je travaille chaque jour.', $forms))->toBe(['t' => [2, 2]])
        ->and($spans->find($normalizer, 'Je travaille souvent.', $forms))->toBeNull();
});
