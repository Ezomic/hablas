<?php

namespace App\Actions\Placement;

use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestItem;
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

        // Single replay shared by both the settlement check and the tier
        // lookup below — DeriveCurrentPlacementTier is the one place that
        // knows the starting tier and step semantics, so nothing here
        // duplicates that walk or its starting point.
        $tierSequence = (new DeriveCurrentPlacementTier)->tierSequence($responses);

        if ($this->hasSettled($tierSequence)) {
            return null;
        }

        $tier = $tierSequence === [] ? DeriveCurrentPlacementTier::STARTING_TIER : end($tierSequence);
        $answeredItemIds = $responses->pluck('item_id');

        return $this->findUnansweredItem($attempt->language_id, $skill, $tier, $answeredItemIds);
    }

    /**
     * The staircase has stopped moving once the last N tiers in the
     * sequence are identical.
     *
     * @param  list<CefrSubLevel>  $tierSequence
     */
    private function hasSettled(array $tierSequence): bool
    {
        if (count($tierSequence) < self::CONSECUTIVE_STOP_THRESHOLD) {
            return false;
        }

        $lastN = array_slice($tierSequence, -self::CONSECUTIVE_STOP_THRESHOLD);

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
