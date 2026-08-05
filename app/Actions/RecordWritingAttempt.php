<?php

namespace App\Actions;

use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\Skill;
use App\Models\User;
use App\Models\WritingAttempt;
use App\Models\WritingExercise;
use RuntimeException;

class RecordWritingAttempt
{
    public function __construct(
        private readonly GradeWritingAttempt $gradeWritingAttempt = new GradeWritingAttempt,
        private readonly NotifyOnBlendedLevelIncrease $notifyOnBlendedLevelIncrease = new NotifyOnBlendedLevelIncrease,
        private readonly ReassessSkillLevel $reassessSkillLevel = new ReassessSkillLevel,
        private readonly RecordStreakActivity $recordStreakActivity = new RecordStreakActivity,
    ) {}

    /**
     * @return array{attempt: WritingAttempt, milestone: array{type: string, message: string}|null}
     */
    public function handle(User $user, WritingExercise $exercise, string $response): array
    {
        $isCorrect = $this->gradeWritingAttempt->handle($exercise, $response);

        $attempt = WritingAttempt::create([
            'user_id' => $user->id,
            'writing_exercise_id' => $exercise->id,
            'response' => $response,
            'is_correct' => $isCorrect,
            'submitted_at' => now(),
        ]);

        $this->recordStreakActivity->handle($user);

        $language = $exercise->language;

        if ($language === null) {
            throw new RuntimeException("Exercise {$exercise->id} has no language.");
        }

        $milestone = $this->notifyOnBlendedLevelIncrease->handle(
            $user,
            $language,
            fn () => $this->reassessSkillLevel->handle($user, $language, Skill::Writing),
        );

        return ['attempt' => $attempt, 'milestone' => $milestone];
    }
}
