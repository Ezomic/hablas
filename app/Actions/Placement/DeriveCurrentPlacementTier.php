<?php

namespace App\Actions\Placement;

use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\PlacementTestAttempt;

class DeriveCurrentPlacementTier
{
    private const CefrSubLevel STARTING_TIER = CefrSubLevel::A1_3;

    /**
     * Replays this attempt's response history for one skill to derive the
     * tier the *next* item for that skill should be drawn from. Stateless
     * between requests by design — nothing but this table is the source of
     * truth for where a skill's staircase currently stands.
     */
    public function handle(PlacementTestAttempt $attempt, Skill $skill): CefrSubLevel
    {
        $tier = self::STARTING_TIER;

        foreach ($attempt->responses()->where('skill', $skill)->orderBy('id')->get() as $response) {
            $tier = $response->is_correct ? $tier->stepUp() : $tier->stepDown();
        }

        return $tier;
    }
}
