<?php

declare(strict_types=1);

namespace App\Actions\Placement;

use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestItem;

final class GetCurrentPlacementItem
{
    public function __construct(
        private readonly SelectNextPlacementItem $selectNextPlacementItem = new SelectNextPlacementItem,
    ) {}

    /**
     * Walks skills in a fixed order (sequential per-skill blocks, not
     * interleaved) and returns the first one whose staircase isn't done yet.
     * Null means every skill has settled and the attempt is ready to finalize.
     */
    public function handle(PlacementTestAttempt $attempt): ?PlacementTestItem
    {
        foreach ($attempt->skills() as $skill) {
            $item = $this->selectNextPlacementItem->handle($attempt, $skill);

            if ($item !== null) {
                return $item;
            }
        }

        return null;
    }
}
