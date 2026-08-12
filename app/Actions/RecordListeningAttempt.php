<?php

namespace App\Actions;

use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\Skill;
use App\Models\ListeningAttempt;
use App\Models\ListeningExercise;
use App\Models\User;
use RuntimeException;

class RecordListeningAttempt
{
    public function __construct(
        private readonly GradeListeningAttempt $gradeListeningAttempt = new GradeListeningAttempt,
        private readonly NotifyOnBlendedLevelIncrease $notifyOnBlendedLevelIncrease = new NotifyOnBlendedLevelIncrease,
        private readonly ReassessSkillLevel $reassessSkillLevel = new ReassessSkillLevel,
        private readonly RecordStreakActivity $recordStreakActivity = new RecordStreakActivity,
    ) {}

    /**
     * @param  array<int, string>  $answers
     * @return array{attempt: ListeningAttempt, milestone: array{type: string, message: string}|null}
     */
    public function handle(User $user, ListeningExercise $exercise, array $answers, int $replaysUsed): array
    {
        $score = $this->gradeListeningAttempt->handle($exercise, $answers);

        $attempt = ListeningAttempt::create([
            'user_id' => $user->id,
            'listening_exercise_id' => $exercise->id,
            'answers' => $answers,
            'score' => $score,
            'replays_used' => $replaysUsed,
            'attempted_at' => now(),
        ]);

        $this->recordStreakActivity->handle($user);

        $language = $exercise->language;

        if ($language === null) {
            throw new RuntimeException("Listening exercise {$exercise->id} has no language.");
        }

        $milestone = $this->notifyOnBlendedLevelIncrease->handle(
            $user,
            $language,
            fn () => $this->reassessSkillLevel->handle($user, $language, Skill::Listening),
        );

        return ['attempt' => $attempt, 'milestone' => $milestone];
    }
}
