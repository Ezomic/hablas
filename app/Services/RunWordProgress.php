<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\LessonRun;
use App\Models\Unit;
use App\Models\UnitItemMastery;
use App\Models\VocabularyItem;
use App\Models\WordTypingSupport;
use Carbon\CarbonImmutable;

/**
 * The words a run made known and the milestones that crossed with them. A
 * word became known in a run when its typing support reached zero while the
 * run was open, or when the run's check proved it. The counts before the run
 * are the counts now less those words, so nothing is stored.
 */
final class RunWordProgress
{
    private const int WORDS_PER_MILESTONE = 10;

    public function __construct(
        private readonly WordProgress $wordProgress = new WordProgress,
    ) {}

    /**
     * @return array{newlyKnown: list<array{term: string, translation: string}>, milestones: list<array{type: string, count?: int, level?: string}>}
     */
    public function handle(LessonRun $run): array
    {
        $unit = $run->lesson?->unit;
        $user = $run->user;

        if ($run->completed_at === null || $unit === null || $user === null) {
            return ['newlyKnown' => [], 'milestones' => []];
        }

        $items = VocabularyItem::query()
            ->where('language_id', $unit->language_id)
            ->whereIn('id', $this->knownInRun($run))
            ->orderBy('id')
            ->get();

        if ($items->isEmpty()) {
            return ['newlyKnown' => [], 'milestones' => []];
        }

        $language = $unit->language;
        $newlyInUnit = $items->where('unit_id', $unit->id)->count();
        $levelUnitIds = Unit::query()->where('language_id', $unit->language_id)->where('cefr_level', $unit->cefr_level)->pluck('id');
        $newlyInLevel = $items->whereIn('unit_id', $levelUnitIds)->count();

        $knownNow = $language === null ? 0 : $this->wordProgress->knownCount($user, $language);
        $unitNow = $this->wordProgress->forUnits($user, collect([$unit]))[$unit->id];
        $levelNow = $language === null ? ['known' => 0, 'total' => 0, 'percent' => 0] : $this->wordProgress->forLevel($user, $language, $unit->cefr_level);

        return [
            'newlyKnown' => array_values($items->take(12)->map(fn (VocabularyItem $item): array => ['term' => $item->term, 'translation' => $item->translation_en])->all()),
            'milestones' => $this->milestones(
                $knownNow - $items->count(),
                $knownNow,
                [$unitNow['known'] - $newlyInUnit, $unitNow['known'], $unitNow['total']],
                [$levelNow['known'] - $newlyInLevel, $levelNow['known'], $levelNow['total']],
                $unit->cefr_level->value,
            ),
        ];
    }

    /** @return list<int> */
    private function knownInRun(LessonRun $run): array
    {
        $from = $run->started_at;
        $to = $run->completed_at ?? CarbonImmutable::now();
        $morph = (new VocabularyItem)->getMorphClass();

        $typed = WordTypingSupport::query()
            ->where('user_id', $run->user_id)
            ->where('revealed', 0)
            ->whereBetween('updated_at', [$from, $to])
            ->get()
            ->map(fn (WordTypingSupport $row): int => $row->vocabulary_item_id);

        $proved = UnitItemMastery::query()
            ->where('user_id', $run->user_id)
            ->where('lesson_run_id', $run->id)
            ->where('masterable_type', $morph)
            ->get()
            ->map(fn (UnitItemMastery $mastery): int => $mastery->masterable_id);

        return array_values(array_unique([...$typed->all(), ...$proved->all()]));
    }

    /**
     * @param  array{int, int, int}  $unit  known before, known now, total
     * @param  array{int, int, int}  $level  known before, known now, total
     * @return list<array{type: string, count?: int, level?: string}>
     */
    private function milestones(int $before, int $after, array $unit, array $level, string $levelCode): array
    {
        $milestones = [];

        if ($before <= 0 && $after > 0) {
            $milestones[] = ['type' => 'first_word'];
        }

        if (intdiv($after, self::WORDS_PER_MILESTONE) > intdiv(max($before, 0), self::WORDS_PER_MILESTONE)) {
            $milestones[] = ['type' => 'words', 'count' => intdiv($after, self::WORDS_PER_MILESTONE) * self::WORDS_PER_MILESTONE];
        }

        [$unitBefore, $unitNow, $unitTotal] = $unit;

        if ($unitTotal > 0 && $unitBefore < $unitTotal && $unitNow >= $unitTotal) {
            $milestones[] = ['type' => 'unit_words'];
        }

        [$levelBefore, $levelNow, $levelTotal] = $level;

        if ($levelTotal > 0) {
            foreach ([50 => 'level_half', 100 => 'level_full'] as $percent => $type) {
                if (intdiv($levelBefore * 100, $levelTotal) < $percent && intdiv($levelNow * 100, $levelTotal) >= $percent) {
                    $milestones[] = ['type' => $type, 'level' => $levelCode];
                }
            }
        }

        return $milestones;
    }
}
