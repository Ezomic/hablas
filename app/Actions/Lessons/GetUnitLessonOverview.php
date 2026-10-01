<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Enums\LessonStage;
use App\Enums\LessonState;
use App\Enums\MasteryScope;
use App\Models\Lesson;
use App\Models\LessonAnswer;
use App\Models\LessonRun;
use App\Models\Unit;
use App\Models\User;
use App\Services\LessonProgress;
use App\Services\UnitMasteryReader;

final class GetUnitLessonOverview
{
    public function __construct(
        private readonly LessonProgress $lessonProgress = new LessonProgress,
        private readonly UnitMasteryReader $unitMasteryReader = new UnitMasteryReader,
    ) {}

    /**
     * What a unit page shows about its lessons: each lesson's state and best
     * first-try accuracy, how much of the unit is mastered, how many
     * listening and speaking exercises were skipped, and whether the
     * sentence and grammar lessons are still on their way.
     *
     * @return array{
     *     lessons: list<array{stage: string, title: string, position: int, lessonId: int|null, state: string, bestAccuracy: float|null}>,
     *     mastery: array{mastered: int, total: int},
     *     skipped: array{listening: int, speaking: int},
     *     contentPending: bool
     * }
     */
    public function handle(User $user, Unit $unit): array
    {
        $states = $this->lessonProgress->states($user, $unit);
        $lessons = Lesson::query()->where('unit_id', $unit->id)->playable()->get()->keyBy(fn (Lesson $lesson): string => $lesson->stage->value);

        $rows = [];

        foreach (LessonStage::cases() as $stage) {
            $lesson = $lessons->get($stage->value);

            $rows[] = [
                'stage' => $stage->value,
                'title' => $stage->title(),
                'position' => $stage->position(),
                'lessonId' => $lesson?->id,
                'state' => $lesson === null ? LessonState::Coming->value : ($states[$lesson->id] ?? LessonState::Coming)->value,
                'bestAccuracy' => $lesson === null ? null : $this->bestAccuracy($user, $lesson),
            ];
        }

        $items = $this->unitMasteryReader->items($unit);

        return [
            'lessons' => $rows,
            'mastery' => ['mastered' => count($items) - count($this->unitMasteryReader->missing($user, $unit)), 'total' => count($items)],
            'skipped' => $this->skipped($user, $unit),
            'contentPending' => $lessons->isNotEmpty() && $this->unitMasteryReader->scope($unit) === MasteryScope::Words,
        ];
    }

    private function bestAccuracy(User $user, Lesson $lesson): ?float
    {
        $best = LessonRun::query()
            ->where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->where('status', LessonRunStatus::Completed)
            ->where('kind', '!=', LessonRunKind::Practice)
            ->max('first_try_accuracy');

        return is_numeric($best) ? (float) $best : null;
    }

    /** @return array{listening: int, speaking: int} */
    private function skipped(User $user, Unit $unit): array
    {
        $counts = ['listening' => 0, 'speaking' => 0];

        $answers = LessonAnswer::query()
            ->where('skipped', true)
            ->whereHas('lessonRun', fn ($query) => $query->where('user_id', $user->id))
            ->whereHas('lessonExercise.lesson', fn ($query) => $query->where('unit_id', $unit->id))
            ->with('lessonExercise')
            ->get();

        foreach ($answers as $answer) {
            $family = $answer->lessonExercise?->format->family()?->value;

            if ($family !== null && isset($counts[$family])) {
                $counts[$family]++;
            }
        }

        return $counts;
    }
}
