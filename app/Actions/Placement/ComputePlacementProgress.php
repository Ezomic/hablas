<?php

declare(strict_types=1);

namespace App\Actions\Placement;

use App\Models\PlacementTestAttempt;

final class ComputePlacementProgress
{
    public function __construct(
        private readonly SelectNextPlacementItem $selectNextPlacementItem = new SelectNextPlacementItem,
    ) {}

    /**
     * Approximate overall completion (0-100) of an adaptive placement attempt.
     * Each skill is an equal share of the bar: a settled skill counts in full,
     * the in-progress skill counts its answered fraction of the per-skill cap.
     * Deliberately conservative — the bar only ever moves forward, since the
     * real length is unknowable until each skill's staircase settles.
     */
    public function handle(PlacementTestAttempt $attempt): int
    {
        $skills = $attempt->skills();
        $perSkillShare = 1 / count($skills);

        $completion = 0.0;

        foreach ($skills as $skill) {
            if ($this->selectNextPlacementItem->handle($attempt, $skill) === null) {
                $completion += $perSkillShare;

                continue;
            }

            $answered = $attempt->responses()->where('skill', $skill)->count();
            $fraction = min($answered / SelectNextPlacementItem::MAX_ITEMS_PER_SKILL, 1.0);

            $completion += $fraction * $perSkillShare;
        }

        return (int) round($completion * 100);
    }
}
