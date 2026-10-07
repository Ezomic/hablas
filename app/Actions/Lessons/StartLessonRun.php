<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Units\DetermineUnitAvailability;
use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Enums\LessonStage;
use App\Enums\LessonState;
use App\Enums\Skill;
use App\Enums\UnitAvailability;
use App\Enums\UnitProgressStatus;
use App\Models\Lesson;
use App\Models\LessonRun;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserUnitProgress;
use App\Services\LessonProgress;
use App\Services\UnitMasteryReader;
use App\Services\UnitSkillProgress;
use App\Services\UnitStruggles;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class StartLessonRun
{
    public function __construct(
        private readonly GetCurrentLanguage $getCurrentLanguage = new GetCurrentLanguage,
        private readonly DetermineUnitAvailability $determineUnitAvailability = new DetermineUnitAvailability,
        private readonly BuildLessonPlan $buildLessonPlan = new BuildLessonPlan,
        private readonly LessonProgress $lessonProgress = new LessonProgress,
        private readonly UnitMasteryReader $unitMasteryReader = new UnitMasteryReader,
        private readonly UnitStruggles $unitStruggles = new UnitStruggles,
        private readonly UnitSkillProgress $unitSkillProgress = new UnitSkillProgress,
    ) {}

    /**
     * Starts a run of the lesson, or resumes the one in progress: there is at
     * most one open run per lesson, so two quick taps on "Start" open the same
     * run. Asking for another kind of run while one is open is refused, so a
     * retake is never answered with a practice run. The unique index decides it, not a lock, because SQLite has none.
     */
    public function handle(User $user, Lesson $lesson, LessonRunKind $kind = LessonRunKind::Lesson, ?Skill $skill = null): LessonRun
    {
        $unit = Unit::query()->findOrFail($lesson->unit_id);
        $language = $this->getCurrentLanguage->handle($user);

        abort_if($language === null || $unit->language_id !== $language->id, 404);

        $availability = $this->determineUnitAvailability->handle($user, $language, collect([$unit]))[$unit->id];

        abort_if($availability === UnitAvailability::Locked, 403);

        $open = $this->openRun($user, $lesson);

        if ($open !== null) {
            if ($open->kind !== $kind) {
                throw $this->refuse(__('Finish the run you have open for this lesson first.'));
            }

            return $open;
        }

        if ($availability === UnitAvailability::HeldBack) {
            throw $this->refuse(__('Clear your reviews first, then start this unit.'));
        }

        $this->assertKindIsOpen($user, $unit, $lesson, $kind, $skill);

        $built = $this->buildLessonPlan->handle($user, $lesson, $kind, skill: $skill);

        if ($built['plan'] === []) {
            throw $this->refuse(__('This lesson has no exercises yet.'));
        }

        return DB::transaction(function () use ($user, $unit, $lesson, $kind, $built): LessonRun {
            $run = LessonRun::query()->createOrFirst(
                ['user_id' => $user->id, 'open_lesson_id' => $lesson->id],
                [
                    'lesson_id' => $lesson->id,
                    'kind' => $kind,
                    'probe_set' => $built['probe_set'],
                    'status' => LessonRunStatus::InProgress,
                    'plan' => $built['plan'],
                    'seed' => $built['seed'],
                    'counts_as_evidence' => false,
                    'started_at' => now(),
                ],
            );

            $progress = UserUnitProgress::query()->firstOrCreate(
                ['user_id' => $user->id, 'unit_id' => $unit->id],
                ['status' => UnitProgressStatus::InProgress],
            );

            if ($progress->status === UnitProgressStatus::Available) {
                $progress->forceFill(['status' => UnitProgressStatus::InProgress])->save();
            }

            return $run;
        });
    }

    /**
     * The kind of run a learner has open on a lesson, so that "Continue" on
     * the check lesson resumes a practice run or a retake as what it is.
     */
    public function openKind(User $user, Lesson $lesson): ?LessonRunKind
    {
        return $this->openRun($user, $lesson)?->kind;
    }

    private function openRun(User $user, Lesson $lesson): ?LessonRun
    {
        return LessonRun::query()
            ->where('user_id', $user->id)
            ->where('open_lesson_id', $lesson->id)
            ->first();
    }

    private function assertKindIsOpen(User $user, Unit $unit, Lesson $lesson, LessonRunKind $kind, ?Skill $skill = null): void
    {
        $isCheckLesson = $lesson->stage === LessonStage::Check;

        if ($kind === LessonRunKind::Lesson && $isCheckLesson || $kind !== LessonRunKind::Lesson && ! $isCheckLesson) {
            throw $this->refuse(__('That kind of run does not belong to this lesson.'));
        }

        $state = $this->lessonProgress->state($user, $lesson);

        match ($kind) {
            LessonRunKind::Lesson => $this->assertNotLocked($state),
            LessonRunKind::Check => $this->assertCheckIsOpen($state),
            LessonRunKind::TestOut => $this->assertNotStarted($user, $unit, $state),
            LessonRunKind::Retake => $this->assertRemediation($user, $unit, $kind),
            LessonRunKind::Practice => $this->assertSomethingToPractise($user, $unit, $skill),
        };
    }

    private function assertNotLocked(LessonState $state): void
    {
        if ($state === LessonState::Locked) {
            throw $this->refuse(__('Finish the lesson before it first.'));
        }
    }

    private function assertCheckIsOpen(LessonState $state): void
    {
        match ($state) {
            LessonState::Locked => throw $this->refuse(__('Finish the lessons before the check.')),
            LessonState::OpensTomorrow => throw $this->refuse(__('The check opens tomorrow.')),
            LessonState::Completed => throw $this->refuse(__('You have passed this unit check.')),
            LessonState::Remediation => throw $this->refuse(__('Practise the missed items and retake them, instead of taking the whole check again.')),
            default => null,
        };
    }

    private function assertNotStarted(User $user, Unit $unit, LessonState $state): void
    {
        $started = LessonRun::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [LessonRunStatus::Completed])
            ->whereHas('lesson', fn ($query) => $query->where('unit_id', $unit->id))
            ->exists();

        if ($started || $state === LessonState::Completed) {
            throw $this->refuse(__('Taking the check now is for a unit you have not started.'));
        }
    }

    private function assertSomethingToPractise(User $user, Unit $unit, ?Skill $skill): void
    {
        if ($skill !== null) {
            if ($this->unitSkillProgress->missing($user, $unit, $skill) === []) {
                throw $this->refuse(__('Nothing is left to practise.'));
            }

            return;
        }

        $struggling = $this->unitStruggles->handle($user, $unit) !== [];
        $missing = $this->lessonProgress->lastCheck($user, $unit) !== null && $this->unitMasteryReader->missing($user, $unit) !== [];

        if (! $struggling && ! $missing) {
            throw $this->refuse(__('Nothing is left to practise.'));
        }
    }

    private function assertRemediation(User $user, Unit $unit, LessonRunKind $kind): void
    {
        $last = $this->lessonProgress->lastCheck($user, $unit);

        if ($last === null) {
            throw $this->refuse(__('Take the unit check first.'));
        }

        if ($this->unitMasteryReader->missing($user, $unit) === []) {
            throw $this->refuse(__('Nothing is left to practise.'));
        }

        if ($kind === LessonRunKind::Retake && $this->lessonProgress->completedToday($last->completed_at)) {
            throw $this->refuse(__('The retake opens tomorrow.'));
        }
    }

    private function refuse(string $message): ValidationException
    {
        return ValidationException::withMessages(['lesson' => [$message]]);
    }
}
