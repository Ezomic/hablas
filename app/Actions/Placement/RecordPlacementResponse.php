<?php

namespace App\Actions\Placement;

use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestItem;
use App\Models\PlacementTestResponse;

class RecordPlacementResponse
{
    public function handle(PlacementTestAttempt $attempt, PlacementTestItem $item, string $response): PlacementTestResponse
    {
        $tierAtTime = (new DeriveCurrentPlacementTier)->handle($attempt, $item->skill);

        return PlacementTestResponse::query()->create([
            'attempt_id' => $attempt->id,
            'item_id' => $item->id,
            'skill' => $item->skill,
            'response' => $response,
            'is_correct' => $response === $item->correct_answer,
            'tier_at_time' => $tierAtTime,
            'answered_at' => now(),
        ]);
    }
}
