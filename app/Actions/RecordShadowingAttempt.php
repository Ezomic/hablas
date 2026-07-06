<?php

namespace App\Actions;

use App\Models\ShadowingAttempt;
use App\Models\ShadowingExercise;
use App\Models\User;

class RecordShadowingAttempt
{
    public function handle(User $user, ShadowingExercise $exercise, string $transcriptGuess): ShadowingAttempt
    {
        $score = (new GradeShadowingAttempt)->handle($exercise, $transcriptGuess);

        return ShadowingAttempt::create([
            'user_id' => $user->id,
            'shadowing_exercise_id' => $exercise->id,
            'transcript_guess' => $transcriptGuess,
            'score' => $score,
            'attempted_at' => now(),
        ]);
    }
}
