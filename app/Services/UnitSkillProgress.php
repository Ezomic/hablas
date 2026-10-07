<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\LessonExerciseFormat;
use App\Enums\Skill;
use App\Lessons\TargetRef;
use App\Models\LessonExercise;
use App\Models\Unit;
use App\Models\UnitSkillMastery;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * How far a learner is, skill by skill, in a unit: of the words and grammar
 * points the unit's exercises train in a skill, how many were last answered
 * right in that skill. A skill a unit does not train for an item leaves that
 * item out of the count, so 100% can always be reached.
 */
final class UnitSkillProgress
{
    private const SKILLS = [Skill::Reading, Skill::Listening, Skill::Speaking, Skill::Writing];

    public function __construct(
        private readonly UnitMasteryReader $unitMasteryReader = new UnitMasteryReader,
    ) {}

    /** @return list<array{skill: string, done: int, total: int, percent: int, mastered: bool}> */
    public function handle(User $user, Unit $unit): array
    {
        $rows = [];

        foreach (self::SKILLS as $skill) {
            $trained = $this->trained($unit)[$skill->value] ?? [];
            $missing = $this->missing($user, $unit, $skill);
            $total = count($trained);
            $done = $total - count($missing);

            $rows[] = ['skill' => $skill->value, 'done' => $done, 'total' => $total, 'percent' => $total === 0 ? 100 : (int) floor($done / $total * 100), 'mastered' => $this->isMastered($user, $unit, $skill)];
        }

        return $rows;
    }

    public function isMastered(User $user, Unit $unit, Skill $skill): bool
    {
        return UnitSkillMastery::query()->where('user_id', $user->id)->where('unit_id', $unit->id)->where('skill', $skill)->exists();
    }

    /**
     * How far the learner is in the unit as a whole: each skill the unit
     * trains counts half for its training and half for being mastered.
     *
     * @param  list<array{skill: string, done: int, total: int, percent: int, mastered: bool}>  $rows
     */
    public function overall(array $rows): int
    {
        $trained = array_values(array_filter($rows, fn (array $row): bool => $row['total'] > 0));

        if ($trained === []) {
            return 0;
        }

        $sum = array_sum(array_map(fn (array $row): float => $row['percent'] / 2 + ($row['mastered'] ? 50 : 0), $trained));

        return (int) floor($sum / count($trained));
    }

    /**
     * The items the unit trains in a skill that were not last answered right.
     *
     * @return list<TargetRef>
     */
    public function missing(User $user, Unit $unit, Skill $skill): array
    {
        $trained = $this->trained($unit)[$skill->value] ?? [];

        if ($trained === []) {
            return [];
        }

        $latest = $this->latest($user, $trained, $skill);

        return array_values(array_filter($trained, fn (TargetRef $ref): bool => ($latest[$ref->key()] ?? false) !== true));
    }

    /**
     * @var array<int, array<string, array<string, TargetRef>>>
     */
    private array $cache = [];

    /** @return array<string, list<TargetRef>> */
    private function trained(Unit $unit): array
    {
        if (isset($this->cache[$unit->id])) {
            return array_map(fn (array $refs): array => array_values($refs), $this->cache[$unit->id]);
        }

        $items = [];

        foreach ($this->unitMasteryReader->items($unit) as $ref) {
            $items[$ref->key()] = $ref;
        }

        $bySkill = [];

        $exercises = LessonExercise::query()
            ->whereNull('retired_at')
            ->whereNull('substitute_for_id')
            ->whereNull('probe_set')
            ->whereHas('lesson', fn ($query) => $query->where('unit_id', $unit->id))
            ->with('targets')
            ->get();

        foreach ($exercises as $exercise) {
            $skill = $exercise->format->skill();

            if ($skill === null) {
                continue;
            }

            foreach ($exercise->targets as $target) {
                $key = TargetRef::keyFor($target->targetable_type, $target->targetable_id);

                if (isset($items[$key])) {
                    $bySkill[$skill->value][$key] = $items[$key];
                }
            }
        }

        $this->cache[$unit->id] = $bySkill;

        return array_map(fn (array $refs): array => array_values($refs), $bySkill);
    }

    /**
     * @param  list<TargetRef>  $refs
     * @return array<string, bool>
     */
    private function latest(User $user, array $refs, Skill $skill): array
    {
        $latest = [];

        $rows = DB::table('lesson_answer_targets')
            ->join('lesson_answers', 'lesson_answers.id', '=', 'lesson_answer_targets.lesson_answer_id')
            ->join('lesson_runs', 'lesson_runs.id', '=', 'lesson_answers.lesson_run_id')
            ->join('lesson_exercises', 'lesson_exercises.id', '=', 'lesson_answers.lesson_exercise_id')
            ->where('lesson_runs.user_id', $user->id)
            ->where('lesson_answers.skipped', false)
            ->whereNotNull('lesson_answer_targets.is_correct')
            ->whereIn('lesson_answer_targets.targetable_id', array_map(fn (TargetRef $ref): int => $ref->id, $refs))
            ->orderBy('lesson_answers.id')
            ->get(['lesson_answer_targets.targetable_type', 'lesson_answer_targets.targetable_id', 'lesson_answer_targets.is_correct', 'lesson_exercises.format']);

        foreach ($rows as $row) {
            if (! is_string($row->targetable_type) || ! is_numeric($row->targetable_id) || ! is_string($row->format)) {
                continue;
            }

            if (LessonExerciseFormat::tryFrom($row->format)?->skill() !== $skill) {
                continue;
            }

            $latest[TargetRef::keyFor($row->targetable_type, (int) $row->targetable_id)] = (bool) $row->is_correct;
        }

        return $latest;
    }
}
