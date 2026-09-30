<?php

declare(strict_types=1);

namespace App\Actions\Srs;

use App\Enums\SrsCardState;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use Carbon\CarbonImmutable;

final class ForecastReviewLoad
{
    public const int DAYS = 14;

    /**
     * Repetitions due on each of the next DAYS days of one language deck,
     * today first, in the app's UTC days like streaks and the new-item cap.
     * Overdue cards count towards today, since today is when they get
     * reviewed. New cards have no schedule of their own (the daily cap
     * releases them), so they are counted apart as waiting.
     *
     * @return array{days: list<array{date: string, cards: int}>, newWaiting: int}
     */
    public function handle(User $user, Language $language): array
    {
        $today = CarbonImmutable::today();
        $todayDate = $today->toDateString();

        $cardsPerDueDate = SrsCard::query()
            ->repetitionsInDeck($user, $language)
            ->where('due_at', '<', $today->addDays(self::DAYS))
            ->selectRaw('date(due_at) as due_date, count(*) as cards')
            ->groupBy('due_date')
            ->pluck('cards', 'due_date');

        $cardsPerDay = [];

        foreach ($cardsPerDueDate as $dueDate => $cards) {
            $day = max((string) $dueDate, $todayDate);
            $cardsPerDay[$day] = ($cardsPerDay[$day] ?? 0) + (is_numeric($cards) ? (int) $cards : 0);
        }

        $days = [];

        for ($offset = 0; $offset < self::DAYS; $offset++) {
            $date = $today->addDays($offset)->toDateString();
            $days[] = ['date' => $date, 'cards' => $cardsPerDay[$date] ?? 0];
        }

        return [
            'days' => $days,
            'newWaiting' => SrsCard::query()
                ->where('user_id', $user->id)
                ->where('language_id', $language->id)
                ->where('is_weak_spot', false)
                ->where('state', SrsCardState::New)
                ->count(),
        ];
    }
}
