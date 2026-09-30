<?php

declare(strict_types=1);

namespace App\Actions\Srs;

use App\Enums\ReviewMode;
use App\Models\GrammarPoint;
use App\Models\SrsCard;
use App\Models\VocabularyItem;
use Illuminate\Database\Eloquent\Model;
use LogicException;

final class PresentSrsCardForReview
{
    /**
     * A production card turns the word around: the translation is shown, and
     * the word is what the learner types and then sees revealed.
     *
     * @return array{id: int, front: string, back: string, kind: string, direction: string, needsArticle: bool, suggestedErrorTag: string|null}
     */
    public function handle(SrsCard $card, ReviewMode $mode = ReviewMode::Recognition): array
    {
        $cardable = $card->cardable ?? throw new LogicException("SrsCard {$card->id} has no cardable loaded.");
        $produce = $this->asksToType($card, $cardable, $mode);

        return [
            'id' => $card->id,
            'front' => $produce ? $this->back($cardable) : $this->front($cardable),
            'back' => $produce ? $this->front($cardable) : $this->back($cardable),
            'kind' => $this->kind($cardable),
            'direction' => $produce ? 'production' : 'recognition',
            'needsArticle' => $produce && $this->isNoun($cardable),
            'suggestedErrorTag' => $this->suggestedErrorTag($cardable),
        ];
    }

    /**
     * Only a word can be typed from memory. A grammar point's front is a title
     * and its back an explanation, so it is always read, then revealed.
     */
    private function asksToType(SrsCard $card, Model $cardable, ReviewMode $mode): bool
    {
        return $cardable instanceof VocabularyItem && $mode->asksToType($card->state);
    }

    /**
     * Nouns are stored with their article and graded with it, while the
     * English prompt ("airport") gives no hint of one, so the client says so.
     */
    private function isNoun(Model $cardable): bool
    {
        return $cardable instanceof VocabularyItem && $cardable->part_of_speech === 'noun';
    }

    private function front(Model $cardable): string
    {
        return match (true) {
            $cardable instanceof VocabularyItem => $cardable->term,
            $cardable instanceof GrammarPoint => $cardable->title,
            default => throw new LogicException('Unreachable: unknown cardable type.'),
        };
    }

    private function back(Model $cardable): string
    {
        return match (true) {
            $cardable instanceof VocabularyItem => $cardable->translation_en,
            $cardable instanceof GrammarPoint => $cardable->explanation,
            default => throw new LogicException('Unreachable: unknown cardable type.'),
        };
    }

    /**
     * Only grammar misses carry an error tag. Plain vocabulary misses stay a
     * simple right or wrong, so the client uses this to decide whether to ask
     * what went wrong at all.
     */
    private function kind(Model $cardable): string
    {
        return match (true) {
            $cardable instanceof VocabularyItem => 'vocabulary',
            $cardable instanceof GrammarPoint => 'grammar',
            default => throw new LogicException('Unreachable: unknown cardable type.'),
        };
    }

    /**
     * The category the grammar point itself is authored against, offered as
     * the pre-selected answer so the common case is one tap.
     */
    private function suggestedErrorTag(Model $cardable): ?string
    {
        return $cardable instanceof GrammarPoint
            ? $cardable->error_tag_category?->value
            : null;
    }
}
