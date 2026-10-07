<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\LessonStage;
use App\Lessons\TargetRef;
use App\Models\GrammarPoint;
use App\Models\Lesson;
use App\Models\Unit;
use App\Models\User;
use App\Models\VocabularyItem;
use Illuminate\Support\Facades\DB;

/**
 * The words and grammar points of a unit a learner is struggling with: those
 * whose latest answer, in any lesson or practice run, was wrong. One right
 * answer after that clears it, and a new miss brings it back.
 */
final class UnitStruggles
{
    public function __construct(
        private readonly UnitMasteryReader $unitMasteryReader = new UnitMasteryReader,
    ) {}

    /** @return list<TargetRef> */
    public function handle(User $user, Unit $unit): array
    {
        $refs = [];

        foreach ($this->unitMasteryReader->items($unit) as $ref) {
            $refs[$ref->key()] = $ref;
        }

        if ($refs === []) {
            return [];
        }

        $latest = [];

        $answers = DB::table('lesson_answer_targets')
            ->join('lesson_answers', 'lesson_answers.id', '=', 'lesson_answer_targets.lesson_answer_id')
            ->join('lesson_runs', 'lesson_runs.id', '=', 'lesson_answers.lesson_run_id')
            ->where('lesson_runs.user_id', $user->id)
            ->where('lesson_answers.skipped', false)
            ->whereNotNull('lesson_answer_targets.is_correct')
            ->whereIn('lesson_answer_targets.targetable_id', array_map(fn (TargetRef $ref): int => $ref->id, $refs))
            ->orderBy('lesson_answers.id')
            ->get(['lesson_answer_targets.targetable_type', 'lesson_answer_targets.targetable_id', 'lesson_answer_targets.is_correct']);

        foreach ($answers as $answer) {
            if (! is_string($answer->targetable_type) || ! is_numeric($answer->targetable_id)) {
                continue;
            }

            $key = TargetRef::keyFor($answer->targetable_type, (int) $answer->targetable_id);

            if (isset($refs[$key])) {
                $latest[$key] = (bool) $answer->is_correct;
            }
        }

        return array_values(array_filter($refs, fn (TargetRef $ref): bool => ($latest[$ref->key()] ?? true) === false));
    }

    /**
     * What the unit page shows: how many there are, what they are called, and
     * the lesson a practice run belongs to.
     *
     * @return array{count: int, items: list<array{label: string, meaning: string|null}>, lessonId: int|null}
     */
    public function describe(User $user, Unit $unit): array
    {
        $refs = $this->handle($user, $unit);
        $words = VocabularyItem::query()->where('unit_id', $unit->id)->get()->keyBy(fn (VocabularyItem $item): string => TargetRef::vocabulary($item->id)->key());
        $points = GrammarPoint::query()->where('unit_id', $unit->id)->get()->keyBy(fn (GrammarPoint $point): string => TargetRef::grammar($point->id)->key());
        $items = [];

        foreach ($refs as $ref) {
            $word = $words->get($ref->key());
            $point = $points->get($ref->key());

            if ($word !== null) {
                $items[] = ['label' => $word->term, 'meaning' => $word->meaning()];
            } elseif ($point !== null) {
                $items[] = ['label' => $point->title, 'meaning' => null];
            }
        }

        return [
            'count' => count($refs),
            'items' => $items,
            'lessonId' => $refs === [] ? null : Lesson::query()->where('unit_id', $unit->id)->where('stage', LessonStage::Check)->playable()->first()?->id,
        ];
    }
}
