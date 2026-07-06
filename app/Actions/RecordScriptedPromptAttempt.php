<?php

namespace App\Actions;

use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\Skill;
use App\Models\ScriptedPromptAttempt;
use App\Models\ScriptedPromptExercise;
use App\Models\User;

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

        $levelBefore = (new ComputeBlendedCefrLevel)->handle((new GetUserSkillLevels)->handle($user, $exercise->language));
        (new ReassessSkillLevel)->handle($user, $exercise->language, Skill::Speaking);
        (new NotifyOnBlendedLevelIncrease)->handle($user, $exercise->language, $levelBefore);

        return $attempt;
    }
}
