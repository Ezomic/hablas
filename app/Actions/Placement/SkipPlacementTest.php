<?php

namespace App\Actions\Placement;

use App\Enums\CefrSubLevel;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;

class SkipPlacementTest
{
    public function __construct(
        private readonly FinalizePlacementAttempt $finalizePlacementAttempt = new FinalizePlacementAttempt,
        private readonly GetOrCreateInProgressPlacementAttempt $getOrCreateInProgressPlacementAttempt = new GetOrCreateInProgressPlacementAttempt,
    ) {}

    /**
     * Finalizes the user's in-progress attempt (creating one first if they
     * had never started) at the A1 floor for every skill — reuses
     * GetOrCreateInProgressPlacementAttempt rather than always inserting a
     * fresh attempt row, so skipping mid-test doesn't leave the
     * already-in-progress attempt dangling as an orphaned resumable row.
     */
    public function handle(User $user, Language $language): PlacementTestAttempt
    {
        $attempt = $this->getOrCreateInProgressPlacementAttempt->handle($user, $language);

        return $this->finalizePlacementAttempt->handle($attempt, fn () => CefrSubLevel::A1_1);
    }
}
