<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\VocabularyItem;
use App\Models\WordTypingSupport;
use Illuminate\Support\Facades\DB;
use Normalizer;

/**
 * The fading letters of a typed word. A word starts with most of its letters
 * given; each time the learner types it right unaided one letter fewer is
 * given, and each miss gives one back, so the support follows how well the
 * word is known, in lessons and in the review decks alike. The letters leave
 * in a fixed order per word, so a smaller set is always a part of a larger one.
 */
final class TypingSupport
{
    private const MIN_LETTERS = 3;

    /** @var array<string, int> the stored letters given, by learner and word, for what was preloaded */
    private array $stored = [];

    public function initialReveal(int $letters): int
    {
        if ($letters < self::MIN_LETTERS) {
            return 0;
        }

        return max(1, $letters - max(2, (int) ceil($letters / 3)));
    }

    /**
     * Each position is a given character, or null where the learner types.
     * Anything that is not a letter is always given.
     *
     * @return list<string|null>
     */
    public function mask(string $term, int $revealed): array
    {
        $term = $this->composed($term);
        $chars = mb_str_split($term);
        $letters = array_keys(array_filter($chars, fn (string $char): bool => preg_match('/\p{L}/u', $char) === 1));
        $order = $this->order($term, $letters);
        $shown = array_flip(array_slice($order, 0, max(0, $revealed)));

        return array_map(
            fn (string $char, int $index): ?string => preg_match('/\p{L}/u', $char) !== 1 || isset($shown[$index]) ? $char : null,
            $chars,
            array_keys($chars),
        );
    }

    /** @return list<string|null>|null null when nothing is given, so the learner types the whole word */
    public function maskFor(int $userId, VocabularyItem $item): ?array
    {
        $revealed = $this->revealed($userId, $item);

        return $revealed === 0 ? null : $this->mask($item->term, $revealed);
    }

    /**
     * Reads what is stored for these words in one query, so presenting a whole
     * run or deck does not ask once per word.
     *
     * @param  list<int>  $itemIds
     */
    public function preload(int $userId, array $itemIds): void
    {
        $rows = WordTypingSupport::query()->where('user_id', $userId)->whereIn('vocabulary_item_id', $itemIds)->get();

        foreach ($itemIds as $itemId) {
            $key = $userId.':'.$itemId;
            $row = $rows->firstWhere('vocabulary_item_id', $itemId);

            if ($row instanceof WordTypingSupport) {
                $this->stored[$key] = $row->revealed;
            } else {
                unset($this->stored[$key]);
            }
        }
    }

    public function revealed(int $userId, VocabularyItem $item): int
    {
        $key = $userId.':'.$item->id;

        if (isset($this->stored[$key])) {
            return $this->stored[$key];
        }

        $stored = WordTypingSupport::query()->where('user_id', $userId)->where('vocabulary_item_id', $item->id)->value('revealed');

        return is_int($stored) ? $stored : $this->initialReveal($this->letters($item->term));
    }

    /**
     * An unaided right answer takes a letter away and a miss gives one back, up
     * to where the word started. An answer that used a hint changes nothing.
     */
    public function record(int $userId, VocabularyItem $item, bool $correct, bool $hinted = false): void
    {
        if ($hinted) {
            return;
        }

        $initial = $this->initialReveal($this->letters($item->term));

        DB::transaction(function () use ($userId, $item, $correct, $initial): void {
            $row = WordTypingSupport::query()->where('user_id', $userId)->where('vocabulary_item_id', $item->id)->lockForUpdate()->first();
            $revealed = $row === null ? $initial : $row->revealed;
            $next = $correct ? max(0, $revealed - 1) : min($initial, $revealed + 1);

            WordTypingSupport::query()->updateOrCreate(
                ['user_id' => $userId, 'vocabulary_item_id' => $item->id],
                ['revealed' => $next],
            );

            $this->stored[$userId.':'.$item->id] = $next;
        });
    }

    /**
     * The first letter is the last to go; the rest leave in a fixed order that
     * depends only on the word.
     *
     * @param  list<int>  $letters  the positions of the letters
     * @return list<int>
     */
    private function order(string $term, array $letters): array
    {
        if ($letters === []) {
            return [];
        }

        $first = array_shift($letters);

        usort($letters, fn (int $a, int $b): int => crc32($term.':'.$a) <=> crc32($term.':'.$b));

        return [$first, ...$letters];
    }

    private function letters(string $term): int
    {
        return (int) preg_match_all('/\p{L}/u', $this->composed($term));
    }

    private function composed(string $term): string
    {
        $composed = Normalizer::normalize($term, Normalizer::FORM_C);

        return is_string($composed) ? $composed : $term;
    }
}
