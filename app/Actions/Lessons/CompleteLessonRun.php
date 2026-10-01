<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Actions\NotifyOnBlendedLevelIncrease;
use App\Actions\ReassessSkillLevel;
use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Models\LessonRun;
use App\Services\RunOutcomes;
use Illuminate\Support\Facades\DB;
use LogicException;

final class CompleteLessonRun
{
    public function __construct(
        private readonly RunOutcomes $runOutcomes = new RunOutcomes,
        private readonly RecordLessonSkillScores $recordLessonSkillScores = new RecordLessonSkillScores,
        private readonly NotifyOnBlendedLevelIncrease $notifyOnBlendedLevelIncrease = new NotifyOnBlendedLevelIncrease,
        private readonly ReassessSkillLevel $reassessSkillLevel = new ReassessSkillLevel,
        private readonly EvaluateUnitMastery $evaluateUnitMastery = new EvaluateUnitMastery,
    ) {}

    /**
     * Completes a settled run: first-try accuracy, evidence for skill levels,
     * mastery for a check-kind run, and the stored result the player reads
     * after its queued answers have drained.
     *
     * @return array<string, mixed> the stored result
     */
    public function handle(LessonRun $run): array
    {
        return DB::transaction(function () use ($run): array {
            $run->refresh();

            if ($run->status === LessonRunStatus::Completed) {
                return $run->result ?? [];
            }

            return $this->complete($run);
        });
    }

    /** @return array<string, mixed> */
    private function complete(LessonRun $run): array
    {
        $lesson = $run->lesson ?? throw new LogicException("Run {$run->id} has no lesson.");
        $unit = $lesson->unit ?? throw new LogicException("Lesson {$lesson->id} has no unit.");
        $language = $unit->language ?? throw new LogicException("Unit {$unit->id} has no language.");
        $user = $run->user ?? throw new LogicException("Run {$run->id} has no user.");

        $outcomes = array_filter($this->runOutcomes->handle($run), fn (array $outcome): bool => in_array($outcome['origin'], ['lesson', 'practice'], true));
        $weight = array_sum(array_column($outcomes, 'weight'));
        $accuracy = $weight === 0 ? null : round(array_sum(array_column($outcomes, 'correct')) / $weight, 4);

        $evidence = $this->isEvidence($run, $lesson->stage->isEvidenceStage());
        $milestone = null;

        if ($evidence) {
            $run->forceFill(['counts_as_evidence' => true]);
            $counted = $this->recordLessonSkillScores->handle($run);

            if ($counted !== []) {
                $milestone = $this->notifyOnBlendedLevelIncrease->handle($user, $language, function () use ($user, $language, $counted): void {
                    foreach ($counted as $skill) {
                        $this->reassessSkillLevel->handle($user, $language, $skill);
                    }
                });
            }
        }

        $mastery = $run->kind->isCheck()
            ? $this->evaluateUnitMastery->handle($run)
            : ['mastered' => [], 'missing' => [], 'enrolled' => 0, 'unit_completed' => false];

        $result = [...$mastery, 'first_try_accuracy' => $accuracy, 'milestone' => $milestone];

        $run->forceFill([
            'status' => LessonRunStatus::Completed,
            'open_lesson_id' => null,
            'completed_at' => now(),
            'first_try_accuracy' => $accuracy,
            'result' => $result,
        ])->save();

        return $result;
    }

    private function isEvidence(LessonRun $run, bool $evidenceStage): bool
    {
        if (! $evidenceStage || ! in_array($run->kind, [LessonRunKind::Lesson, LessonRunKind::Check, LessonRunKind::TestOut], true)) {
            return false;
        }

        return ! LessonRun::query()
            ->where('user_id', $run->user_id)
            ->where('lesson_id', $run->lesson_id)
            ->where('status', LessonRunStatus::Completed)
            ->whereIn('kind', [LessonRunKind::Lesson, LessonRunKind::Check, LessonRunKind::TestOut])
            ->whereKeyNot($run->id)
            ->exists();
    }
}
