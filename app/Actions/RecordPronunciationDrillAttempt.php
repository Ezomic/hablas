<?php

namespace App\Actions;

use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\Skill;
use App\Models\PronunciationDrillAttempt;
use App\Models\PronunciationDrillExercise;
use App\Models\User;

class RecordPronunciationDrillAttempt
{
    public function handle(User $user, PronunciationDrillExercise $exercise, string $transcriptGuess): PronunciationDrillAttempt
    {
        $grade = (new GradePronunciationDrillAttempt)->handle($exercise, $transcriptGuess);

        $attempt = PronunciationDrillAttempt::create([
            'user_id' => $user->id,
            'pronunciation_drill_exercise_id' => $exercise->id,
            'transcript_guess' => $transcriptGuess,
            'is_correct' => $grade['is_correct'],
            'score' => $grade['score'],
            'attempted_at' => now(),
        ]);

        (new RecordStreakActivity)->handle($user);

        (new NotifyOnBlendedLevelIncrease)->handle(
            $user,
            $exercise->language,
            fn () => (new ReassessSkillLevel)->handle($user, $exercise->language, Skill::Speaking),
        );

        return $attempt;
    }
}
