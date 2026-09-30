<?php

declare(strict_types=1);

namespace App\Actions\Srs;

use App\Contracts\TextNormalizer;
use App\Enums\SrsCardState;
use App\Enums\VocabularySort;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use App\Services\TextNormalizerResolver;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * @phpstan-type VocabularyRow array{id: int, kind: string, term: string, translation: string, state: string, dueAt: string, isWeakSpot: bool}
 * @phpstan-type DeckEntry array{card: SrsCard, kind: string, term: string, translation: string, searchKeys: list<string>, sortKey: string}
 */
final class SearchVocabulary
{
    public const PER_PAGE = 25;

    public function __construct(
        private readonly TextNormalizerResolver $textNormalizerResolver = new TextNormalizerResolver,
        private readonly PresentSrsCardForReview $presentCard = new PresentSrsCardForReview,
    ) {}

    /**
     * One user's deck in one language, filtered and sorted in PHP. A deck is
     * bounded by the course (one card per item), so loading it whole is cheap,
     * and folding in PHP is the only way to reach the language's normalizer:
     * SQLite has no accent-insensitive LIKE. Only the page is sent on, which
     * is what keeps the payload small.
     *
     * @return LengthAwarePaginator<int, VocabularyRow>
     */
    public function handle(User $user, Language $language, string $query, VocabularySort $sort, int $page): LengthAwarePaginator
    {
        $normalizer = $this->textNormalizerResolver->forLanguage($language);
        $needle = $normalizer->searchKey($query);

        $entries = $this->sorted(
            $this->deck($user, $language, $normalizer)->filter(fn (array $entry): bool => $this->matches($entry, $needle)),
            $sort,
        );

        $page = min($page, max(1, (int) ceil($entries->count() / self::PER_PAGE)));

        return new LengthAwarePaginator(
            $entries->forPage($page, self::PER_PAGE)->map(fn (array $entry): array => $this->row($entry))->values(),
            $entries->count(),
            self::PER_PAGE,
            $page,
        );
    }

    /**
     * @return Collection<int, DeckEntry>
     */
    private function deck(User $user, Language $language, TextNormalizer $normalizer): Collection
    {
        return SrsCard::query()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->with('cardable')
            ->get()
            ->toBase()
            ->map(function (SrsCard $card) use ($normalizer): array {
                $presented = $this->presentCard->handle($card);

                return [
                    'card' => $card,
                    'kind' => $presented['kind'],
                    'term' => $presented['front'],
                    'translation' => $presented['back'],
                    'searchKeys' => $this->searchKeys($presented, $normalizer),
                    'sortKey' => $normalizer->sortKey($presented['front']),
                ];
            });
    }

    /**
     * A grammar point's back is a whole explanation, which would match almost
     * any query, so grammar is found by its title only.
     *
     * @param  array{front: string, back: string, kind: string}  $presented
     * @return list<string>
     */
    private function searchKeys(array $presented, TextNormalizer $normalizer): array
    {
        return $presented['kind'] === 'grammar'
            ? [$normalizer->searchKey($presented['front'])]
            : [$normalizer->searchKey($presented['front']), $normalizer->searchKey($presented['back'])];
    }

    /**
     * @param  DeckEntry  $entry
     */
    private function matches(array $entry, string $needle): bool
    {
        return array_any($entry['searchKeys'], fn (string $key): bool => str_contains($key, $needle));
    }

    /**
     * @param  Collection<int, DeckEntry>  $entries
     * @return Collection<int, DeckEntry>
     */
    private function sorted(Collection $entries, VocabularySort $sort): Collection
    {
        return match ($sort) {
            VocabularySort::Recent => $entries->sort(fn (array $a, array $b): int => [$b['card']->created_at?->getTimestamp(), $b['card']->id] <=> [$a['card']->created_at?->getTimestamp(), $a['card']->id]),
            VocabularySort::Due => $entries->sort(fn (array $a, array $b): int => $this->dueOrder($a['card']) <=> $this->dueOrder($b['card'])),
            VocabularySort::Alphabetical => $entries->sort(fn (array $a, array $b): int => strcmp($a['sortKey'], $b['sortKey']) ?: $a['card']->id <=> $b['card']->id),
        };
    }

    /**
     * A card never studied is due from the moment it is enrolled, but the
     * daily new-item cap decides when it is actually shown. Listing it among
     * the overdue repetitions would bury what is really coming up, so it goes
     * after every scheduled card.
     *
     * @return array{bool, int, int}
     */
    private function dueOrder(SrsCard $card): array
    {
        return [$card->state === SrsCardState::New, $card->due_at->getTimestamp(), $card->id];
    }

    /**
     * @param  DeckEntry  $entry
     * @return VocabularyRow
     */
    private function row(array $entry): array
    {
        $card = $entry['card'];

        return [
            'id' => $card->id,
            'kind' => $entry['kind'],
            'term' => $entry['term'],
            'translation' => $entry['translation'],
            'state' => $card->state->value,
            'dueAt' => $card->due_at->toIso8601String(),
            'isWeakSpot' => $card->is_weak_spot,
        ];
    }
}
