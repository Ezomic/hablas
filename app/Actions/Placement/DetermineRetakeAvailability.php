<?php

declare(strict_types=1);

namespace App\Actions\Placement;

use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;
use Carbon\CarbonImmutable;

final class DetermineRetakeAvailability
{
    /**
     * A skill can be placed again this many UTC days after the day it was
     * last placed, so a re-take cannot be repeated until it lands a level
     * the learner has not earned.
     */
    public const int COOLDOWN_DAYS = 7;

    /**
     * The UTC date from which each skill can be re-taken, or null when it can
     * be re-taken now. Only answered placements count: a skip sets the floor,
     * and there is nothing to farm below it.
     *
     * @return array<string, string|null>
     */
    public function handle(User $user, Language $language): array
    {
        $placements = PlacementTestAttempt::query()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->whereNotNull('completed_at')
            ->whereHas('responses')
            ->get(['skill', 'completed_at']);

        $today = CarbonImmutable::today();
        $availability = [];

        foreach (Skill::cases() as $skill) {
            $lastPlacedAt = $placements
                ->filter(fn (PlacementTestAttempt $attempt): bool => in_array($skill, $attempt->skills(), true))
                ->max('completed_at');

            $availableOn = $lastPlacedAt instanceof CarbonImmutable
                ? $lastPlacedAt->startOfDay()->addDays(self::COOLDOWN_DAYS)
                : null;

            $availability[$skill->value] = $availableOn?->isAfter($today) === true ? $availableOn->toDateString() : null;
        }

        return $availability;
    }
}
