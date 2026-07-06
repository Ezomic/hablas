<?php

namespace App\Actions\Srs;

use App\Models\SrsCard;

class ResolveWeakSpot
{
    /**
     * Called once the remedial drill for a weak-spot card is completed,
     * re-admitting it to the normal FSRS rotation immediately.
     */
    public function handle(SrsCard $card): SrsCard
    {
        $card->forceFill([
            'is_weak_spot' => false,
            'consecutive_lapses' => 0,
            'due_at' => now(),
        ])->save();

        return $card;
    }
}
