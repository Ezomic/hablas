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
        $card->is_weak_spot = false;
        $card->consecutive_lapses = 0;
        $card->due_at = now();
        $card->save();

        return $card;
    }
}
