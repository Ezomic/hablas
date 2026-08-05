<?php

namespace App\Actions\Srs;

use App\Models\GrammarPoint;
use App\Models\SrsCard;
use App\Models\VocabularyItem;
use Illuminate\Database\Eloquent\Model;
use LogicException;

class PresentSrsCardForReview
{
    /**
     * @return array{id: int, front: string, back: string, kind: string, suggestedErrorTag: string|null}
     */
    public function handle(SrsCard $card): array
    {
        $cardable = $card->cardable ?? throw new LogicException("SrsCard {$card->id} has no cardable loaded.");

        return [
            'id' => $card->id,
            'front' => $this->front($cardable),
            'back' => $this->back($cardable),
            'kind' => $this->kind($cardable),
            'suggestedErrorTag' => $this->suggestedErrorTag($cardable),
        ];
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
