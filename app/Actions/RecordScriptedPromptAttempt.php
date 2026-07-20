<?php

namespace App\Actions;

use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\Skill;
use App\Models\ScriptedPromptAttempt;
use App\Models\ScriptedPromptExercise;
use App\Models\User;
use RuntimeException;

class RecordScriptedPromptAttempt
{
    public function handle(User $user, ScriptedPromptExercise $exercise, string $transcriptGuess): ScriptedPromptAttempt
    {
        $score = (new GradeScriptedPromptAttempt)->handle($exercise, $transcriptGuess);

        $attempt = ScriptedPromptAttempt::create([
            'user_id' => $user->id,
            'scripted_prompt_exercise_id' => $exercise->id,
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
