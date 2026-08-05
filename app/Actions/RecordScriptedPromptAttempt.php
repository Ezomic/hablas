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
    public function __construct(
        private readonly GradeScriptedPromptAttempt $gradeScriptedPromptAttempt = new GradeScriptedPromptAttempt,
        private readonly NotifyOnBlendedLevelIncrease $notifyOnBlendedLevelIncrease = new NotifyOnBlendedLevelIncrease,
        private readonly ReassessSkillLevel $reassessSkillLevel = new ReassessSkillLevel,
        private readonly RecordStreakActivity $recordStreakActivity = new RecordStreakActivity,
    ) {}

    /**
     * @return array{attempt: ScriptedPromptAttempt, milestone: array{type: string, message: string}|null}
     */
    public function handle(User $user, ScriptedPromptExercise $exercise, string $transcriptGuess): array
    {
        $score = $this->gradeScriptedPromptAttempt->handle($exercise, $transcriptGuess);

        $attempt = ScriptedPromptAttempt::create([
            'user_id' => $user->id,
            'scripted_prompt_exercise_id' => $exercise->id,
            'transcript_guess' => $transcriptGuess,
            'score' => $score,
            'attempted_at' => now(),
        ]);

        $this->recordStreakActivity->handle($user);

        $language = $exercise->language;

        if ($language === null) {
            throw new RuntimeException("Exercise {$exercise->id} has no language.");
        }

        $milestone = $this->notifyOnBlendedLevelIncrease->handle(
            $user,
            $language,
            fn () => $this->reassessSkillLevel->handle($user, $language, Skill::Speaking),
        );

        return ['attempt' => $attempt, 'milestone' => $milestone];
    }
}
