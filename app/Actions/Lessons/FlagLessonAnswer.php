<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Models\LessonAnswer;
use App\Models\LessonRun;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class FlagLessonAnswer
{
    public function __construct(
        private readonly SettleLessonRun $settleLessonRun = new SettleLessonRun,
    ) {}

    /**
     * "My answer should count": marks a wrong answer as disputed. In a lesson
     * it settles the exercise without counting as right first time, and it
     * never counts as right for mastery. A repeat changes nothing.
     *
     * @return array{answer: LessonAnswer, run: LessonRun, completed: bool}
     */
    public function handle(User $user, LessonRun $run, string $step): array
    {
        abort_unless($run->user_id === $user->id, 404);

        return DB::transaction(function () use ($run, $step): array {
            $answer = LessonAnswer::query()->where('lesson_run_id', $run->id)->where('step', $step)->firstOrFail();

            if ($run->kind->isCheck()) {
                throw ValidationException::withMessages(['lesson' => [__('A check gives no feedback, so there is nothing to dispute.')]]);
            }

            if ($answer->is_correct !== false) {
                throw ValidationException::withMessages(['lesson' => [__('Only a wrong answer can be disputed.')]]);
            }

            if ($answer->flagged_at === null) {
                $answer->forceFill(['flagged_at' => now()])->save();
            }

            $completed = $this->settleLessonRun->handle($run);

            return ['answer' => $answer, 'run' => $run->refresh(), 'completed' => $completed];
        });
    }
}
