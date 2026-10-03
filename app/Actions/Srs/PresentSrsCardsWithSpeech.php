<?php

declare(strict_types=1);

namespace App\Actions\Srs;

use App\Enums\ReviewMode;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\VocabularyItem;
use App\Speech\SpeechClipResolver;
use Illuminate\Support\Collection;

final class PresentSrsCardsWithSpeech
{
    public function __construct(
        private readonly PresentSrsCardForReview $presentCard,
        private readonly SpeechClipResolver $speechClipResolver,
    ) {}

    /**
     * Each card as the review deck shows it, plus the clip urls of the word it
     * speaks, looked up for the whole deck at once.
     *
     * @param  Collection<int, SrsCard>  $cards
     * @return list<array<string, mixed>>
     */
    public function handle(Language $language, Collection $cards, ReviewMode $mode): array
    {
        $terms = $cards
            ->map(fn (SrsCard $card): ?string => $card->cardable instanceof VocabularyItem ? $card->cardable->term : null)
            ->filter()
            ->all();
        $clips = $this->speechClipResolver->resolveBoth($language->code, array_values($terms));

        return array_values($cards->map(function (SrsCard $card) use ($clips, $mode): array {
            $term = $card->cardable instanceof VocabularyItem ? $card->cardable->term : null;

            return [
                ...$this->presentCard->handle($card, $mode),
                'audioUrl' => $term === null ? null : $clips[$term]['audioUrl'],
                'audioSlowUrl' => $term === null ? null : $clips[$term]['audioSlowUrl'],
            ];
        })->all());
    }
}
