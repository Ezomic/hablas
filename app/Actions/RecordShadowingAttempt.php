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
    public function __construct(
        private readonly GradeShadowingAttempt $gradeShadowingAttempt = new GradeShadowingAttempt,
        private readonly NotifyOnBlendedLevelIncrease $notifyOnBlendedLevelIncrease = new NotifyOnBlendedLevelIncrease,
        private readonly ReassessSkillLevel $reassessSkillLevel = new ReassessSkillLevel,
        private readonly RecordStreakActivity $recordStreakActivity = new RecordStreakActivity,
    ) {}

    /**
     * @return array{attempt: ShadowingAttempt, milestone: array{type: string, message: string}|null}
     */
    public function handle(User $user, ShadowingExercise $exercise, string $transcriptGuess): array
    {
        $score = $this->gradeShadowingAttempt->handle($exercise, $transcriptGuess);

        $attempt = ShadowingAttempt::create([
            'user_id' => $user->id,
            'shadowing_exercise_id' => $exercise->id,
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
