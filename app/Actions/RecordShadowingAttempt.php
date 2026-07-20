<?php

namespace App\Actions;

use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\Skill;
use App\Models\ShadowingAttempt;
use App\Models\ShadowingExercise;
use App\Models\User;
use RuntimeException;

class RecordShadowingAttempt
{
    public function handle(User $user, ShadowingExercise $exercise, string $transcriptGuess): ShadowingAttempt
    {
        $score = (new GradeShadowingAttempt)->handle($exercise, $transcriptGuess);

        $attempt = ShadowingAttempt::create([
            'user_id' => $user->id,
            'shadowing_exercise_id' => $exercise->id,
            'transcript_guess' => $transcriptGuess,
            'score' => $score,
            'attempted_at' => now(),
        ]);

        (new RecordStreakActivity)->handle($user);

        $language = $exercise->language;

        if ($language === null) {
            throw new RuntimeException("Exercise {$exercise->id} has no language.");
        }

        (new NotifyOnBlendedLevelIncrease)->handle(
            $user,
            $language,
            fn () => (new ReassessSkillLevel)->handle($user, $language, Skill::Speaking),
        );

        return $attempt;
    }
}
