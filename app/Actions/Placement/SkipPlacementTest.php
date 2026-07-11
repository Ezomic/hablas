<?php

namespace App\Actions\Placement;

use App\Enums\CefrSubLevel;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;

class SkipPlacementTest
{
    /**
     * Finalizes the user's in-progress attempt (creating one first if they
     * had never started) at the A1 floor for every skill — reuses
     * GetOrCreateInProgressPlacementAttempt rather than always inserting a
     * fresh attempt row, so skipping mid-test doesn't leave the
     * already-in-progress attempt dangling as an orphaned resumable row.
     */
    public function handle(User $user, Language $language): PlacementTestAttempt
    {
        $attempt = (new GetOrCreateInProgressPlacementAttempt)->handle($user, $language);

        return (new FinalizePlacementAttempt)->handle($attempt, fn () => CefrSubLevel::A1_1);
    }
}
