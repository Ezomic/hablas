<?php

namespace App\Actions;

use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\Skill;
use App\Models\User;
use App\Models\WritingAttempt;
use App\Models\WritingExercise;

class RecordWritingAttempt
{
    public function handle(User $user, WritingExercise $exercise, string $response): WritingAttempt
    {
        $isCorrect = (new GradeWritingAttempt)->handle($exercise, $response);

        $attempt = WritingAttempt::create([
            'user_id' => $user->id,
            'writing_exercise_id' => $exercise->id,
            'response' => $response,
            'is_correct' => $isCorrect,
            'submitted_at' => now(),
        ]);

        (new RecordStreakActivity)->handle($user);

        (new NotifyOnBlendedLevelIncrease)->handle(
            $user,
            $exercise->language,
            fn () => (new ReassessSkillLevel)->handle($user, $exercise->language, Skill::Writing),
        );

        return $attempt;
    }
}
