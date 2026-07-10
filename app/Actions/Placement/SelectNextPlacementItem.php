<?php

namespace App\Actions\Placement;

use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestItem;
use App\Models\PlacementTestResponse;
use Illuminate\Support\Collection;

class SelectNextPlacementItem
{
    private const int MAX_ITEMS_PER_SKILL = 8;

    private const int CONSECUTIVE_STOP_THRESHOLD = 3;

    /**
     * Server-authoritative: returns the next PlacementTestItem to present
     * for this skill in this attempt, or null when this skill's staircase
     * is done (either the item cap or the consistency threshold was hit).
     */
    public function handle(PlacementTestAttempt $attempt, Skill $skill): ?PlacementTestItem
    {
        $responses = $attempt->responses()->where('skill', $skill)->orderBy('id')->get();

        if ($responses->count() >= self::MAX_ITEMS_PER_SKILL) {
            return null;
        }

        if ($this->hasSettled($responses)) {
            return null;
        }

        $tier = (new DeriveCurrentPlacementTier)->handle($attempt, $skill);
        $answeredItemIds = $responses->pluck('item_id');

        return $this->findUnansweredItem($attempt->language_id, $skill, $tier, $answeredItemIds);
    }

    /**
     * Walks the response history tracking each intermediate tier (not just
     * the final one) and checks whether the last N tiers in that sequence
     * are identical — i.e. the staircase has stopped moving.
     *
     * @param  Collection<int, PlacementTestResponse>  $responses
     */
    private function hasSettled(Collection $responses): bool
    {
        if ($responses->count() < self::CONSECUTIVE_STOP_THRESHOLD) {
            return false;
        }

        $tier = CefrSubLevel::A1_3;
        $tiersAfterEachResponse = [];

        foreach ($responses as $response) {
            $tier = $response->is_correct ? $tier->stepUp() : $tier->stepDown();
            $tiersAfterEachResponse[] = $tier;
        }

        $lastN = array_slice($tiersAfterEachResponse, -self::CONSECUTIVE_STOP_THRESHOLD);

        return count(array_unique(array_map(fn (CefrSubLevel $t): string => $t->value, $lastN))) === 1;
    }

    /** @param  Collection<int, int>  $answeredItemIds */
    private function findUnansweredItem(int $languageId, Skill $skill, CefrSubLevel $tier, Collection $answeredItemIds): ?PlacementTestItem
    {
        $tiersByDistance = collect(CefrSubLevel::cases())
            ->sortBy(fn (CefrSubLevel $candidate): int => abs($candidate->sortOrder() - $tier->sortOrder()))
            ->values();

        foreach ($tiersByDistance as $candidateTier) {
            $item = PlacementTestItem::query()
                ->where('language_id', $languageId)
                ->where('skill', $skill)
                ->where('cefr_sublevel_tag', $candidateTier)
                ->whereNotIn('id', $answeredItemIds)
                ->orderBy('sort_order')
                ->first();

            if ($item !== null) {
                return $item;
            }
        }

        return null;
    }
}
