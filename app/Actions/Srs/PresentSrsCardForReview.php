<?php

declare(strict_types=1);

namespace App\Actions\Srs;

use App\Enums\ReviewMode;
use App\Enums\SrsCardState;
use App\Models\GrammarPoint;
use App\Models\SrsCard;
use App\Models\VocabularyItem;
use App\Services\ReviewChoices;
use App\Services\TypingSupport;
use Illuminate\Database\Eloquent\Model;
use LogicException;

final class PresentSrsCardForReview
{
    public function __construct(
        private readonly TypingSupport $typingSupport = new TypingSupport,
        private readonly ReviewChoices $reviewChoices = new ReviewChoices,
    ) {}

    /**
     * Reads what a deck's learner has stored for its words in one query.
     *
     * @param  iterable<SrsCard>  $cards
     */
    public function preload(iterable $cards): void
    {
        $byUser = [];
        $items = [];

        foreach ($cards as $card) {
            if ($card->cardable instanceof VocabularyItem) {
                $byUser[$card->user_id][] = $card->cardable->id;
                $items[] = $card->cardable;
            }
        }

        $this->reviewChoices->preload($items);

        foreach ($byUser as $userId => $itemIds) {
            $this->typingSupport->preload($userId, $itemIds);
        }
    }

    /**
     * A vocabulary card is an exercise: typed from the translation, or chosen
     * from four options. A word still being learnt is asked as its meaning, a
     * word that has graduated as the word itself. A production card turns the
     * word around: the translation is shown, and the word is what the learner
     * types and then sees revealed. The word is therefore already in the page
     * props before the learner answers: the deck has to reveal it with no
     * round trip when the check cannot be reached. A known and accepted trade-off,
     * as answering from memory is self-discipline.
     *
     * @return array{id: int, front: string, back: string, kind: string, exercise: string, options: list<string>|null, direction: string, needsArticle: bool, mask: list<string|null>|null, suggestedErrorTag: string|null}
     */
    public function handle(SrsCard $card, ReviewMode $mode = ReviewMode::Recognition): array
    {
        $cardable = $card->cardable ?? throw new LogicException("SrsCard {$card->id} has no cardable loaded.");
        $typed = $this->asksToType($card, $cardable, $mode);
        $choice = $cardable instanceof VocabularyItem && ! $typed ? $this->choice($card, $cardable) : null;
        $produce = $typed || ($choice !== null && $choice['askMeaning'] === false);

        return [
            'id' => $card->id,
            'front' => $produce ? $this->back($cardable) : $this->front($cardable),
            'back' => $produce ? $this->front($cardable) : $this->back($cardable),
            'kind' => $this->kind($cardable),
            'exercise' => match (true) {
                $typed => 'type',
                $choice === null => 'flip',
                $choice['askMeaning'] => 'choose_meaning',
                default => 'choose_word',
            },
            'options' => $choice['options'] ?? null,
            'direction' => $produce ? 'production' : 'recognition',
            'needsArticle' => $typed && $this->isNoun($cardable),
            'mask' => $typed && $cardable instanceof VocabularyItem ? $this->typingSupport->maskFor($card->user_id, $cardable) : null,
            'suggestedErrorTag' => $this->suggestedErrorTag($cardable),
        ];
    }

    /** @return array{options: list<string>, askMeaning: bool}|null */
    private function choice(SrsCard $card, VocabularyItem $item): ?array
    {
        $askMeaning = in_array($card->state, [SrsCardState::New, SrsCardState::Learning], true);
        $choices = $this->reviewChoices->handle($item, $askMeaning, $card->id);

        return $choices === null ? null : ['options' => $choices['options'], 'askMeaning' => $askMeaning];
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
            $cardable instanceof VocabularyItem => $cardable->meaning(),
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
