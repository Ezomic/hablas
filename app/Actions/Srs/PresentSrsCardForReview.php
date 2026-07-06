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
     * @return array{id: int, front: string, back: string}
     */
    public function handle(SrsCard $card): array
    {
        $cardable = $card->cardable ?? throw new LogicException("SrsCard {$card->id} has no cardable loaded.");

        return [
            'id' => $card->id,
            'front' => $this->front($cardable),
            'back' => $this->back($cardable),
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
}
