<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Lessons\TargetRef;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\UnitSkillMastery;
use Illuminate\Support\Facades\DB;
use LogicException;

final class EvaluateSkillTest
{
    /**
     * Reads the final test of one skill, item by item. The test allows no
     * mistakes: the skill is mastered only when every item in the run was
     * right in every answer, none was flagged and none went unanswered. Any
     * mistake leaves the skill unmastered, and the items missed come back as
     * the ones still to practise.
     *
     * @return array{mastered: list<array{type: string, id: int}>, missing: list<array{type: string, id: int}>, enrolled: int, unit_completed: bool, skill: string|null, skill_mastered: bool}
     */
    public function handle(LessonRun $run): array
    {
        $lesson = $run->lesson ?? throw new LogicException("Run {$run->id} has no lesson.");
        $skill = LessonExercise::query()->whereIn('id', $run->planExerciseIds())->first()?->format->skill();

        $planned = $this->plannedItems($run);
        $answered = $this->answeredItems($run);

        $mastered = [];
        $missing = [];

        foreach ($planned as $key => $ref) {
            $verdicts = $answered[$key] ?? [];

            if ($verdicts !== [] && ! in_array(false, $verdicts, true)) {
                $mastered[] = $ref;
            } else {
                $missing[] = $ref;
            }
        }

        $passed = $skill !== null && $planned !== [] && $missing === [] && $this->everyExerciseAnswered($run);

        if ($passed) {
            UnitSkillMastery::query()->updateOrCreate(
                ['user_id' => $run->user_id, 'unit_id' => $lesson->unit_id, 'skill' => $skill],
                ['lesson_run_id' => $run->id, 'mastered_at' => now()],
            );
        }

        return [
            'mastered' => array_map(fn (TargetRef $ref): array => ['type' => $ref->type, 'id' => $ref->id], $mastered),
            'missing' => array_map(fn (TargetRef $ref): array => ['type' => $ref->type, 'id' => $ref->id], $missing),
            'enrolled' => 0,
            'unit_completed' => false,
            'skill' => $skill?->value,
            'skill_mastered' => $passed,
        ];
    }

    /** @return array<string, TargetRef> */
    private function plannedItems(LessonRun $run): array
    {
        $items = [];

        foreach (DB::table('lesson_exercise_targets')->whereIn('lesson_exercise_id', $run->planExerciseIds())->get(['targetable_type', 'targetable_id']) as $row) {
            if (! is_string($row->targetable_type) || ! is_numeric($row->targetable_id)) {
                continue;
            }

            $ref = new TargetRef($row->targetable_type, (int) $row->targetable_id);
            $items[$ref->key()] = $ref;
        }

        return $items;
    }

    /** @return array<string, list<bool>> */
    private function answeredItems(LessonRun $run): array
    {
        $verdicts = [];

        $rows = DB::table('lesson_answer_targets')
            ->join('lesson_answers', 'lesson_answers.id', '=', 'lesson_answer_targets.lesson_answer_id')
            ->where('lesson_answers.lesson_run_id', $run->id)
            ->where('lesson_answers.skipped', false)
            ->get(['lesson_answer_targets.targetable_type', 'lesson_answer_targets.targetable_id', 'lesson_answer_targets.is_correct', 'lesson_answers.flagged_at']);

        foreach ($rows as $row) {
            if (! is_string($row->targetable_type) || ! is_numeric($row->targetable_id)) {
                continue;
            }

            $verdicts[TargetRef::keyFor($row->targetable_type, (int) $row->targetable_id)][] = (bool) $row->is_correct && $row->flagged_at === null;
        }

        return $verdicts;
    }

    private function everyExerciseAnswered(LessonRun $run): bool
    {
        $answered = [];

        foreach (LessonAnswer::query()->where('lesson_run_id', $run->id)->where('skipped', false)->get(['lesson_exercise_id']) as $answer) {
            $answered[$answer->lesson_exercise_id] = true;
        }

        foreach ($run->planExerciseIds() as $id) {
            if (! isset($answered[$id])) {
                return false;
            }
        }

        return true;
    }
}
