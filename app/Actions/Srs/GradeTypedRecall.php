<?php

declare(strict_types=1);

namespace App\Actions\Srs;

use App\Models\VocabularyItem;
use App\Services\TextNormalizerResolver;
use LogicException;

final class GradeTypedRecall
{
    public function __construct(
        private readonly TextNormalizerResolver $textNormalizerResolver = new TextNormalizerResolver,
    ) {}

    /**
     * Accents, case and punctuation are forgiven, but the article is part of
     * the answer: typing the wrong one is the wrong-gender mistake the app
     * tracks, so a bare noun does not pass.
     */
    public function handle(VocabularyItem $item, string $answer): bool
    {
        $language = $item->language ?? throw new LogicException("Vocabulary item {$item->id} has no language.");
        $normalizer = $this->textNormalizerResolver->forLanguage($language);

        return $normalizer->answerKey($answer) === $normalizer->answerKey($item->term);
    }
}
