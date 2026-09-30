<?php

declare(strict_types=1);

namespace App\Actions\Placement;

use App\Enums\CefrSubLevel;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class SkipPlacementTest
{
    public function __construct(
        private readonly FinalizePlacementAttempt $finalizePlacementAttempt = new FinalizePlacementAttempt,
        private readonly GetOrCreateInProgressPlacementAttempt $getOrCreateInProgressPlacementAttempt = new GetOrCreateInProgressPlacementAttempt,
        private readonly HasTakenPlacementTest $hasTakenPlacementTest = new HasTakenPlacementTest,
    ) {}

    /**
     * Finalizes the user's in-progress attempt (creating one first if they
     * had never started) at the A1 floor for every skill — reuses
     * GetOrCreateInProgressPlacementAttempt rather than always inserting a
     * fresh attempt row, so skipping mid-test doesn't leave the
     * already-in-progress attempt dangling as an orphaned resumable row.
     *
     * Skipping stands in for a first placement only. Null, and nothing
     * written, once the learner has taken one, or for a one-skill re-take,
     * where it would drop that skill to A1.
     *
     * The attempt is marked skipped, since answers given before the skip do
     * not make it a placement the learner took.
     */
    public function handle(User $user, Language $language): ?PlacementTestAttempt
    {
        if ($this->hasTakenPlacementTest->handle($user, $language)) {
            return null;
        }

        $attempt = $this->getOrCreateInProgressPlacementAttempt->handle($user, $language);

        if ($attempt === null || $attempt->skill !== null) {
            return null;
        }

        return DB::transaction(function () use ($attempt): PlacementTestAttempt {
            $this->finalizePlacementAttempt->handle($attempt, fn () => CefrSubLevel::A1_1);
            $attempt->forceFill(['skipped' => true])->save();

            return $attempt;
        });
    }
}
