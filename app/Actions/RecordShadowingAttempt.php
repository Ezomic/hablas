<?php

namespace App\Actions;

use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\Skill;
use App\Models\ShadowingAttempt;
use App\Models\ShadowingExercise;
use App\Models\User;

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

        $levelBefore = (new ComputeBlendedCefrLevel)->handle((new GetUserSkillLevels)->handle($user, $exercise->language));
        (new ReassessSkillLevel)->handle($user, $exercise->language, Skill::Speaking);
        (new NotifyOnBlendedLevelIncrease)->handle($user, $exercise->language, $levelBefore);

        return $attempt;
    }
}
