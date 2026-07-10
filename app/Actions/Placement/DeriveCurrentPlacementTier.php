<?php

namespace App\Actions\Placement;

use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestResponse;
use Illuminate\Support\Collection;

class DeriveCurrentPlacementTier
{
    public const CefrSubLevel STARTING_TIER = CefrSubLevel::A1_3;

    /**
     * Replays this attempt's response history for one skill to derive the
     * tier the *next* item for that skill should be drawn from. Stateless
     * between requests by design — nothing but this table is the source of
     * truth for where a skill's staircase currently stands.
     */
    public function handle(PlacementTestAttempt $attempt, Skill $skill): CefrSubLevel
    {
        $responses = $attempt->responses()->where('skill', $skill)->orderBy('id')->get();
        $sequence = $this->tierSequence($responses);

        return $sequence === [] ? self::STARTING_TIER : end($sequence);
    }

    /**
     * Every intermediate tier reached while replaying $responses in order,
     * starting from STARTING_TIER — the single source of truth for both
     * "what's the current tier" (handle(), above) and "has this settled"
     * (SelectNextPlacementItem::hasSettled(), which needs the whole walk,
     * not just where it ends up).
     *
     * @param  Collection<int, PlacementTestResponse>  $responses  Must already be ordered by id.
     * @return list<CefrSubLevel>
     */
    public function tierSequence(Collection $responses): array
    {
        $tier = self::STARTING_TIER;
        $sequence = [];

        foreach ($responses as $response) {
            $tier = $response->is_correct ? $tier->stepUp() : $tier->stepDown();
            $sequence[] = $tier;
        }

        return $sequence;
    }
}
