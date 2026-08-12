<?php

namespace App\Actions;

use App\Actions\Streaks\RecordStreakActivity;
use App\Enums\Skill;
use App\Models\ReadingAttempt;
use App\Models\ReadingPassage;
use App\Models\User;
use RuntimeException;

class RecordReadingAttempt
{
    public function __construct(
        private readonly GradeReadingAttempt $gradeReadingAttempt = new GradeReadingAttempt,
        private readonly NotifyOnBlendedLevelIncrease $notifyOnBlendedLevelIncrease = new NotifyOnBlendedLevelIncrease,
        private readonly ReassessSkillLevel $reassessSkillLevel = new ReassessSkillLevel,
        private readonly RecordStreakActivity $recordStreakActivity = new RecordStreakActivity,
    ) {}

    /**
     * @param  array<int, string>  $answers
     * @return array{attempt: ReadingAttempt, milestone: array{type: string, message: string}|null}
     */
    public function handle(User $user, ReadingPassage $passage, array $answers): array
    {
        $score = $this->gradeReadingAttempt->handle($passage, $answers);

        $attempt = ReadingAttempt::create([
            'user_id' => $user->id,
            'reading_passage_id' => $passage->id,
            'answers' => $answers,
            'score' => $score,
            'attempted_at' => now(),
        ]);

        $this->recordStreakActivity->handle($user);

        $language = $passage->language;

        if ($language === null) {
            throw new RuntimeException("Reading passage {$passage->id} has no language.");
        }

        $milestone = $this->notifyOnBlendedLevelIncrease->handle(
            $user,
            $language,
            fn () => $this->reassessSkillLevel->handle($user, $language, Skill::Reading),
        );

        return ['attempt' => $attempt, 'milestone' => $milestone];
    }
}
