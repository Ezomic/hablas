<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Actions\CompleteUnit;
use App\Actions\Srs\EnrollPendingUnitContent;
use App\Enums\MasteryScope;
use App\Enums\UnitProgressStatus;
use App\Lessons\TargetRef;
use App\Models\LessonRun;
use App\Models\UnitItemMastery;
use App\Models\UserUnitProgress;
use App\Services\UnitMasteryReader;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use LogicException;

final class EvaluateUnitMastery
{
    public function __construct(
        private readonly UnitMasteryReader $unitMasteryReader = new UnitMasteryReader,
        private readonly EnrollPendingUnitContent $enrollPendingUnitContent = new EnrollPendingUnitContent,
        private readonly CompleteUnit $completeUnit = new CompleteUnit,
    ) {}

    /**
     * Reads the probes of a check-kind run, item by item:
     * - a word is mastered when all its probes in the run are right;
     * - the grammar point when every contrast probe is right and at most one
     *   other is wrong;
     * - a flagged probe is never right;
     * - an item with a probe in the plan that has no graded answer, from the
     *   exercise or its substitute, is never mastered, so a skipped probe
     *   cannot be left out of the reading.
     * Mastered items join the review deck at once, up to the daily cap, and
     * the unit completes when every item has full mastery.
     *
     * @return array{mastered: list<array{type: string, id: int}>, missing: list<array{type: string, id: int}>, enrolled: int, unit_completed: bool}
     */
    public function handle(LessonRun $run): array
    {
        $lesson = $run->lesson ?? throw new LogicException("Run {$run->id} has no lesson.");
        $unit = $lesson->unit ?? throw new LogicException("Lesson {$lesson->id} has no unit.");
        $user = $run->user ?? throw new LogicException("Run {$run->id} has no user.");
        $language = $unit->language ?? throw new LogicException("Unit {$unit->id} has no language.");

        $scope = $this->unitMasteryReader->scope($unit);
        $probes = $this->probes($run);
        $unanswered = $this->unansweredProbeTargets($run);
        $mastered = [];

        foreach ($this->unitMasteryReader->items($unit) as $ref) {
            if (! isset($unanswered[$ref->key()]) && $this->isMastered($ref, $probes[$ref->key()] ?? [])) {
                $mastered[] = $ref;
                UnitItemMastery::query()->firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'masterable_type' => $ref->type,
                        'masterable_id' => $ref->id,
                        'scope' => $scope === MasteryScope::Full ? MasteryScope::Full : MasteryScope::Words,
                    ],
                    ['unit_id' => $unit->id, 'lesson_run_id' => $run->id, 'mastered_at' => now()],
                );
            }
        }

        $enrolled = $this->enrollPendingUnitContent->handle($user, $language)['enrolled'];
        $missing = $this->unitMasteryReader->missing($user, $unit);
        $unitCompleted = false;

        if ($scope === MasteryScope::Full && $missing === [] && ! $this->isCompleted($run, $unit->id)) {
            $enrolled += $this->completeUnit->handle($user, $unit)['enrolled'];
            $unitCompleted = true;
        }

        return [
            'mastered' => array_map(fn (TargetRef $ref): array => ['type' => $ref->type, 'id' => $ref->id], $mastered),
            'missing' => array_map(fn (TargetRef $ref): array => ['type' => $ref->type, 'id' => $ref->id], $missing),
            'enrolled' => $enrolled,
            'unit_completed' => $unitCompleted,
        ];
    }

    private function isCompleted(LessonRun $run, int $unitId): bool
    {
        return UserUnitProgress::query()
            ->where('user_id', $run->user_id)
            ->where('unit_id', $unitId)
            ->where('status', UnitProgressStatus::Completed)
            ->exists();
    }

    /**
     * @param  list<array{correct: bool, contrast: bool}>  $probes
     */
    private function isMastered(TargetRef $ref, array $probes): bool
    {
        if ($probes === []) {
            return false;
        }

        if (! $ref->isGrammar()) {
            return ! in_array(false, array_column($probes, 'correct'), true);
        }

        $contrast = array_filter($probes, fn (array $probe): bool => $probe['contrast']);
        $otherWrong = array_filter($probes, fn (array $probe): bool => ! $probe['contrast'] && ! $probe['correct']);

        return $contrast !== []
            && ! in_array(false, array_column($contrast, 'correct'), true)
            && count($otherWrong) <= 1;
    }

    /**
     * The target keys of probes in the plan that nothing answered: neither
     * the exercise itself nor its substitute has an answer that was not a skip.
     *
     * @return array<string, true>
     */
    private function unansweredProbeTargets(LessonRun $run): array
    {
        $planIds = $run->planExerciseIds();
        $originals = DB::table('lesson_exercises')->whereIn('substitute_for_id', $planIds)->pluck('substitute_for_id', 'id');
        $covered = [];

        foreach (DB::table('lesson_answers')->where('lesson_run_id', $run->id)->where('skipped', false)->pluck('lesson_exercise_id') as $exerciseId) {
            $covered[(int) ($originals[$exerciseId] ?? $exerciseId)] = true;
        }

        $unanswered = array_values(array_filter($planIds, fn (int $id): bool => ! isset($covered[$id])));
        $keys = [];

        foreach (DB::table('lesson_exercise_targets')->whereIn('lesson_exercise_id', $unanswered)->where('is_probe', true)->get(['targetable_type', 'targetable_id']) as $row) {
            $type = is_string($row->targetable_type) ? $row->targetable_type : '';
            $id = is_numeric($row->targetable_id) ? (int) $row->targetable_id : 0;
            $keys[TargetRef::keyFor($type, $id)] = true;
        }

        return $keys;
    }

    /**
     * The probe verdicts of the run per target key: only answers that were
     * not skipped, on targets flagged as probes of their exercise.
     *
     * @return array<string, list<array{correct: bool, contrast: bool}>>
     */
    private function probes(LessonRun $run): array
    {
        $rows = DB::table('lesson_answer_targets as at')
            ->join('lesson_answers as a', 'a.id', '=', 'at.lesson_answer_id')
            ->join('lesson_exercise_targets as et', function (JoinClause $join): void {
                $join->on('et.lesson_exercise_id', '=', 'a.lesson_exercise_id')
                    ->on('et.targetable_type', '=', 'at.targetable_type')
                    ->on('et.targetable_id', '=', 'at.targetable_id');
            })
            ->where('a.lesson_run_id', $run->id)
            ->where('a.skipped', false)
            ->where('et.is_probe', true)
            ->get(['at.targetable_type', 'at.targetable_id', 'at.is_correct', 'et.is_contrast', 'a.flagged_at']);

        $probes = [];

        foreach ($rows as $row) {
            $type = is_string($row->targetable_type) ? $row->targetable_type : '';
            $id = is_numeric($row->targetable_id) ? (int) $row->targetable_id : 0;
            $probes[TargetRef::keyFor($type, $id)][] = [
                'correct' => (bool) $row->is_correct && $row->flagged_at === null,
                'contrast' => (bool) $row->is_contrast,
            ];
        }

        return $probes;
    }
}
