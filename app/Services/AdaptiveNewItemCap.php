<?php

namespace App\Services;

use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;

/**
 * Caps how many brand-new items a user is offered per day, throttled down as
 * their due-review backlog grows so they clear reviews before piling on more
 * new material, and automatically back up once the backlog clears (the cap is
 * computed fresh from the current due count on every call — no separate
 * throttle-state is persisted).
 *
 * Does not yet account for a per-user override (planned for THI-193's
 * user_settings table) — that will layer a check in front of this once it
 * exists.
 */
class AdaptiveNewItemCap
{
    private const BASE_CAP = 10;

    private const MODERATE_BACKLOG_THRESHOLD = 50;

    private const MODERATE_BACKLOG_CAP = 5;

    private const HEAVY_BACKLOG_THRESHOLD = 100;

    private const HEAVY_BACKLOG_CAP = 0;

    public function forUser(User $user, Language $language): int
    {
        $dueCount = $this->dueCardCount($user, $language);

        return match (true) {
            $dueCount >= self::HEAVY_BACKLOG_THRESHOLD => self::HEAVY_BACKLOG_CAP,
            $dueCount >= self::MODERATE_BACKLOG_THRESHOLD => self::MODERATE_BACKLOG_CAP,
            default => self::BASE_CAP,
        };
    }

    /**
     * Matches GetDueSrsCards' definition of the normal review queue: weak-spot
     * cards are gated into a separate remedial drill rather than counted here,
     * so they don't double up with the ordinary due-backlog signal.
     */
    private function dueCardCount(User $user, Language $language): int
    {
        return SrsCard::query()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->where('is_weak_spot', false)
            ->where('due_at', '<=', now())
            ->count();
    }
}
