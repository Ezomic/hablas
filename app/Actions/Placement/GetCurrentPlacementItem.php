<?php

namespace App\Actions\Placement;

use App\Enums\Skill;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestItem;

class GetCurrentPlacementItem
{
    /**
     * Walks skills in a fixed order (sequential per-skill blocks, not
     * interleaved) and returns the first one whose staircase isn't done yet.
     * Null means every skill has settled and the attempt is ready to finalize.
     */
    public function handle(PlacementTestAttempt $attempt): ?PlacementTestItem
    {
        foreach (Skill::cases() as $skill) {
            $item = (new SelectNextPlacementItem)->handle($attempt, $skill);

            if ($item !== null) {
                return $item;
            }
        }

        return null;
    }
}
