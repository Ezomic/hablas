<?php

declare(strict_types=1);

namespace App\Actions\Units;

use App\Actions\Srs\GetDueSrsCards;
use App\Actions\Streaks\ReconcileStreak;
use App\Models\Language;
use App\Models\User;
use App\Services\DailyGoal;

final class GetDayStrip
{
    public function __construct(
        private readonly DailyGoal $dailyGoal = new DailyGoal,
        private readonly ReconcileStreak $reconcileStreak = new ReconcileStreak,
        private readonly GetDueSrsCards $getDueSrsCards = new GetDueSrsCards,
    ) {}

    /**
     * What the unit page says about today: words practised against the goal,
     * the streak, and the reviews waiting.
     *
     * @return array{words: int, goal: int, streak: int, due: int}
     */
    public function handle(User $user, Language $language): array
    {
        return [
            'words' => $this->dailyGoal->wordsToday($user, $language),
            'goal' => DailyGoal::WORDS,
            'streak' => $this->reconcileStreak->handle($user)->current_length,
            'due' => $this->getDueSrsCards->count($user, $language),
        ];
    }
}
