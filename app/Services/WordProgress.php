<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CefrLevel;
use App\Enums\SrsCardState;
use App\Enums\WordState;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\Unit;
use App\Models\UnitItemMastery;
use App\Models\User;
use App\Models\VocabularyItem;
use App\Models\WordTypingSupport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * How well a learner knows each word. A word moves from new to seen (a lesson
 * has shown it), to typing (the letters given are fading), to known (typed
 * from memory with none given, or proved by a check), to solid (a review deck
 * card that has graduated). The percentage of a unit is the share of its words
 * that are known or solid.
 */
final class WordProgress
{
    /**
     * @param  list<int>  $itemIds
     * @return array<int, WordState>
     */
    public function states(User $user, array $itemIds): array
    {
        if ($itemIds === []) {
            return [];
        }

        $morph = (new VocabularyItem)->getMorphClass();

        $typing = WordTypingSupport::query()->where('user_id', $user->id)->whereIn('vocabulary_item_id', $itemIds)->pluck('revealed', 'vocabulary_item_id');
        $mastered = UnitItemMastery::query()->where('user_id', $user->id)->where('masterable_type', $morph)->whereIn('masterable_id', $itemIds)->pluck('masterable_id')->flip();
        $solid = SrsCard::query()->where('user_id', $user->id)->where('cardable_type', $morph)->where('state', SrsCardState::Review)->whereIn('cardable_id', $itemIds)->pluck('cardable_id')->flip();
        $seen = DB::table('lesson_answer_targets')
            ->join('lesson_answers', 'lesson_answers.id', '=', 'lesson_answer_targets.lesson_answer_id')
            ->join('lesson_runs', 'lesson_runs.id', '=', 'lesson_answers.lesson_run_id')
            ->where('lesson_runs.user_id', $user->id)
            ->where('lesson_answer_targets.targetable_type', $morph)
            ->whereIn('lesson_answer_targets.targetable_id', $itemIds)
            ->distinct()
            ->pluck('lesson_answer_targets.targetable_id')
            ->flip();

        $states = [];

        foreach ($itemIds as $id) {
            $states[$id] = match (true) {
                $solid->has($id) => WordState::Solid,
                $typing->get($id) === 0 || $mastered->has($id) => WordState::Known,
                $typing->has($id) => WordState::Typing,
                $seen->has($id) => WordState::Seen,
                default => WordState::New,
            };
        }

        return $states;
    }

    /**
     * @param  Collection<int, Unit>  $units
     * @return array<int, array{percent: int, known: int, total: int, words: list<array{id: int, state: string}>}>
     */
    public function forUnits(User $user, Collection $units): array
    {
        $items = VocabularyItem::query()->whereIn('unit_id', $units->pluck('id'))->orderBy('id')->get(['id', 'unit_id']);
        $states = $this->states($user, array_values($items->map(fn (VocabularyItem $item): int => $item->id)->all()));
        $summaries = [];

        foreach ($units as $unit) {
            $words = array_values($items->where('unit_id', $unit->id)->map(fn (VocabularyItem $item): array => ['id' => $item->id, 'state' => $states[$item->id]->value])->all());
            $known = count(array_filter($words, fn (array $word): bool => WordState::from($word['state'])->isKnown()));

            $summaries[$unit->id] = ['percent' => $this->percent($known, count($words)), 'known' => $known, 'total' => count($words), 'words' => $words];
        }

        return $summaries;
    }

    /** @return array{known: int, total: int, percent: int} */
    public function forLevel(User $user, Language $language, CefrLevel $level): array
    {
        $units = Unit::query()->where('language_id', $language->id)->where('cefr_level', $level)->get(['id']);
        $items = array_values(VocabularyItem::query()->whereIn('unit_id', $units->pluck('id'))->get(['id'])->map(fn (VocabularyItem $item): int => $item->id)->all());
        $known = count(array_filter($this->states($user, $items), fn (WordState $state): bool => $state->isKnown()));

        return ['known' => $known, 'total' => count($items), 'percent' => $this->percent($known, count($items))];
    }

    private function percent(int $known, int $total): int
    {
        return $total === 0 ? 0 : intdiv($known * 100, $total);
    }
}
