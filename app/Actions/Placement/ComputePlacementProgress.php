<?php

namespace App\Actions\Placement;

use App\Enums\Skill;
use App\Models\PlacementTestAttempt;

class ComputePlacementProgress
{
    /**
     * Approximate overall completion (0-100) of an adaptive placement attempt.
     * Each skill is an equal share of the bar: a settled skill counts in full,
     * the in-progress skill counts its answered fraction of the per-skill cap.
     * Deliberately conservative — the bar only ever moves forward, since the
     * real length is unknowable until each skill's staircase settles.
     */
    public function handle(PlacementTestAttempt $attempt): int
    {
        $skills = Skill::cases();
        $perSkillShare = 1 / count($skills);
        $selector = new SelectNextPlacementItem;

        $completion = 0.0;

        foreach ($skills as $skill) {
            if ($selector->handle($attempt, $skill) === null) {
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
