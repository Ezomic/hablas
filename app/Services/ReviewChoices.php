<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\VocabularyItem;
use Illuminate\Support\Collection;

final class ReviewChoices
{
    private const DISTRACTORS = 3;

    /** @var array<int, Collection<int, VocabularyItem>> */
    private array $pools = [];

    /**
     * A word asked as a multiple choice: the right text and three other words
     * of the same kind, so the options differ in meaning and not in shape. The
     * pick is a function of the card, so reloading the page keeps the options.
     * A word with too few neighbours returns null and is read as a flashcard.
     *
     * @return array{options: list<string>, answer: string}|null
     */
    public function handle(VocabularyItem $item, bool $askMeaning, int $seed): ?array
    {
        $text = fn (VocabularyItem $word): string => $askMeaning ? $word->translation_en : $word->term;
        $answer = $text($item);

        $candidates = $this->pool($item->language_id)
            ->filter(fn (VocabularyItem $word): bool => $word->id !== $item->id && $word->part_of_speech === $item->part_of_speech)
            ->unique(fn (VocabularyItem $word): string => mb_strtolower($text($word)))
            ->reject(fn (VocabularyItem $word): bool => mb_strtolower($text($word)) === mb_strtolower($answer))
            ->values();

        $sameArticle = $candidates->filter(fn (VocabularyItem $word): bool => $item->part_of_speech !== 'noun' || strtok($word->term, ' ') === strtok($item->term, ' '));

        $picked = ($sameArticle->count() >= self::DISTRACTORS ? $sameArticle : $candidates)
            ->sortBy(fn (VocabularyItem $word): int => crc32($seed.'-'.$word->id))
            ->take(self::DISTRACTORS)
            ->map($text)
            ->values()
            ->all();

        if (count($picked) < self::DISTRACTORS) {
            return null;
        }

        $options = [...$picked, $answer];
        usort($options, fn (string $a, string $b): int => crc32($seed.'-'.$a) <=> crc32($seed.'-'.$b));

        return ['options' => $options, 'answer' => $answer];
    }

    /**
     * @param  iterable<VocabularyItem>  $items
     */
    public function preload(iterable $items): void
    {
        foreach ($items as $item) {
            $this->pool($item->language_id);
        }
    }

    /** @return Collection<int, VocabularyItem> */
    private function pool(int $languageId): Collection
    {
        return $this->pools[$languageId] ??= VocabularyItem::query()
            ->where('language_id', $languageId)
            ->get(['id', 'language_id', 'term', 'translation_en', 'part_of_speech']);
    }
}
