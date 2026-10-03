<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Enums\LessonStage;
use App\Enums\LessonState;
use App\Models\Lesson;
use App\Models\LessonRun;
use App\Models\Unit;
use App\Models\User;
use Carbon\CarbonImmutable;

/**
 * Where a learner stands in a unit's lessons. Each lesson opens when the one
 * before it has a completed run, and the check opens on a later calendar day
 * than the last teaching lesson, so a unit is never mastered in one evening
 * from short-term memory.
 */
final class LessonProgress
{
    public function __construct(
        private readonly UnitMasteryReader $unitMasteryReader = new UnitMasteryReader,
    ) {}

    /**
     * @return array<int, LessonState> by lesson id, for the lessons that exist
     */
    public function states(User $user, Unit $unit): array
    {
        $lessons = Lesson::query()->where('unit_id', $unit->id)->playable()->orderBy('position')->get();

        if ($lessons->isEmpty()) {
            return [];
        }

        $runs = [];

        foreach (LessonRun::query()->where('user_id', $user->id)->whereIn('lesson_id', $lessons->pluck('id'))->get() as $run) {
            $runs[$run->lesson_id][] = $run;
        }

        $checkPassed = $this->checkPassed($user, $unit, $runs);
        $lastCheckAt = $this->lastCheckCompletedAt($runs);
        $states = [];
        $previousCompleted = true;
        $lastTeachingCompletedAt = null;
        $allTeachingCompleted = true;

        foreach ($lessons as $lesson) {
            $lessonRuns = collect($runs[$lesson->id] ?? []);
            $open = $lessonRuns->contains(fn (LessonRun $run): bool => $run->status === LessonRunStatus::InProgress);
            $done = $lessonRuns->filter(fn (LessonRun $run): bool => $run->status === LessonRunStatus::Completed && $run->kind === LessonRunKind::Lesson);

            if ($lesson->stage === LessonStage::Check) {
                $states[$lesson->id] = match (true) {
                    $open => LessonState::InProgress,
                    $checkPassed => LessonState::Completed,
                    ! $allTeachingCompleted => LessonState::Locked,
                    $this->checkTakenSince($lastCheckAt, $lastTeachingCompletedAt) => LessonState::Remediation,
                    $this->completedToday($lastTeachingCompletedAt) => LessonState::OpensTomorrow,
                    default => LessonState::Available,
                };

                continue;
            }

            $states[$lesson->id] = match (true) {
                $open => LessonState::InProgress,
                $done->isNotEmpty() => LessonState::Completed,
                $previousCompleted => LessonState::Available,
                default => LessonState::Locked,
            };

            $previousCompleted = $done->isNotEmpty();
            $allTeachingCompleted = $allTeachingCompleted && $done->isNotEmpty();
            $completedAt = $done->max('completed_at');
            $lastTeachingCompletedAt = $completedAt instanceof CarbonImmutable ? $completedAt : $lastTeachingCompletedAt;
        }

        return $states;
    }

    public function state(User $user, Lesson $lesson): LessonState
    {
        $unit = Unit::query()->findOrFail($lesson->unit_id);

        return $this->states($user, $unit)[$lesson->id] ?? LessonState::Coming;
    }

    /**
     * Whether any lesson of the unit can be started or resumed right now.
     */
    public function hasOpenLesson(User $user, Unit $unit): bool
    {
        foreach ($this->states($user, $unit) as $state) {
            if (in_array($state, [LessonState::Available, LessonState::InProgress, LessonState::Remediation], true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * The most recent completed check-kind run of the unit's check lesson.
     */
    public function lastCheck(User $user, Unit $unit): ?LessonRun
    {
        return LessonRun::query()
            ->where('user_id', $user->id)
            ->where('status', LessonRunStatus::Completed)
            ->whereIn('kind', [LessonRunKind::Check, LessonRunKind::Retake, LessonRunKind::TestOut])
            ->whereHas('lesson', fn ($query) => $query->where('unit_id', $unit->id))
            ->orderByDesc('completed_at')
            ->orderByDesc('id')
            ->first();
    }

    /**
     * What a learner who has taken a check still has to do: the items not
     * proven yet, and whether the retake is open or waits for a later day.
     *
     * @return array{lessonId: int, missing: int, retake: string}|null
     */
    public function remediation(User $user, Unit $unit): ?array
    {
        $last = $this->lastCheck($user, $unit);
        $missing = $last === null ? [] : $this->unitMasteryReader->missing($user, $unit);
        $check = Lesson::query()->where('unit_id', $unit->id)->where('stage', LessonStage::Check)->playable()->first();

        if ($last === null || $missing === [] || $check === null) {
            return null;
        }

        return [
            'lessonId' => $check->id,
            'missing' => count($missing),
            'retake' => $this->completedToday($last->completed_at) ? 'opens_tomorrow' : 'open',
        ];
    }

    public function completedToday(?CarbonImmutable $completedAt): bool
    {
        return $completedAt !== null && $completedAt->isSameDay(CarbonImmutable::today());
    }

    /**
     * A check that was taken after the last teaching lesson was finished has
     * to be followed by practice and a retake, never by another full check:
     * the full check would be open again at once and would sidestep the
     * retake's day of spacing.
     */
    private function checkTakenSince(?CarbonImmutable $checkAt, ?CarbonImmutable $teachingAt): bool
    {
        return $checkAt !== null && ($teachingAt === null || $checkAt->greaterThan($teachingAt));
    }

    /**
     * @param  array<int, list<LessonRun>>  $runs
     */
    private function lastCheckCompletedAt(array $runs): ?CarbonImmutable
    {
        $latest = null;

        foreach ($runs as $lessonRuns) {
            foreach ($lessonRuns as $run) {
                if ($run->status === LessonRunStatus::Completed && $run->kind->isCheck() && $run->completed_at !== null && ($latest === null || $run->completed_at->greaterThan($latest))) {
                    $latest = $run->completed_at;
                }
            }
        }

        return $latest;
    }

    /**
     * @param  array<int, list<LessonRun>>  $runs
     */
    private function checkPassed(User $user, Unit $unit, array $runs): bool
    {
        $anyCheck = collect($runs)->flatten()->contains(fn (LessonRun $run): bool => $run->status === LessonRunStatus::Completed && $run->kind->isCheck());

        return $anyCheck && $this->unitMasteryReader->missing($user, $unit) === [];
    }
}
