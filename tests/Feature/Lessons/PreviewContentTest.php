<?php

declare(strict_types=1);

use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\PreviewContent;
use App\Lessons\ReviewGate;
use Tests\Fixtures\Lessons\HotelContent;

it('passes the words through and treats them as reviewed, with no authored exercise', function () {
    $content = new PreviewContent(new HotelContent(wordsReviewed: false));

    expect($content->languageCode())->toBe('es')
        ->and($content->unitSlug())->toBe('checking-into-a-hotel')
        ->and($content->words())->toHaveCount(3)
        ->and($content->grammarExamples())->toHaveCount(2)
        ->and($content->exercises())->toBe([])
        ->and(ReviewGate::wordsReleased($content))->toBeTrue()
        ->and(ReviewGate::lessonsReleased($content))->toBeFalse()
        ->and($content->reviews()[0]->kind)->toBe(ReviewKind::IndependentAi)
        ->and($content->reviews()[0]->scope)->toBe(ReviewScope::Words);
});
