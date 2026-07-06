<?php

namespace App\Services;

use App\Actions\Settings\GetUserSettings;
use App\Actions\Srs\GetDueSrsCards;
use App\Models\Language;
use App\Models\User;

/**
 * Caps how many brand-new items a user is offered per day, throttled down as
 * their due-review backlog grows so they clear reviews before piling on more
 * new material, and automatically back up once the backlog clears (the cap is
 * computed fresh from the current due count on every call — no separate
 * throttle-state is persisted). An explicit per-user override in their
 * settings always takes precedence over the computed cap.
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
        $override = (new GetUserSettings)->handle($user)->new_item_cap_override;

        if ($override !== null) {
            return $override;
        }

        $dueCount = (new GetDueSrsCards)->count($user, $language);

        return match (true) {
            $dueCount >= self::HEAVY_BACKLOG_THRESHOLD => self::HEAVY_BACKLOG_CAP,
            $dueCount >= self::MODERATE_BACKLOG_THRESHOLD => self::MODERATE_BACKLOG_CAP,
            default => self::BASE_CAP,
        };
    }
}
