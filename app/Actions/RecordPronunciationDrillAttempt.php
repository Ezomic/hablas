<?php

namespace App\Actions;

use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\Skill;
use App\Models\PronunciationDrillAttempt;
use App\Models\PronunciationDrillExercise;
use App\Models\User;
use RuntimeException;

class RecordPronunciationDrillAttempt
{
    /**
     * @return array{attempt: PronunciationDrillAttempt, milestone: array{type: string, message: string}|null}
     */
    public function handle(User $user, PronunciationDrillExercise $exercise, string $transcriptGuess): array
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

        $language = $exercise->language;

        if ($language === null) {
            throw new RuntimeException("Exercise {$exercise->id} has no language.");
        }

        $milestone = (new NotifyOnBlendedLevelIncrease)->handle(
            $user,
            $language,
            fn () => (new ReassessSkillLevel)->handle($user, $language, Skill::Speaking),
        );

        return ['attempt' => $attempt, 'milestone' => $milestone];
    }
}
