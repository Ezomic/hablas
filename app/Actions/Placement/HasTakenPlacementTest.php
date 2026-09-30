<?php

declare(strict_types=1);

namespace App\Actions\Placement;

use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;

final class HasTakenPlacementTest
{
    /**
     * Whether the learner finished a placement they answered, full test or
     * re-take. A skip does not count, even one pressed after a few answers,
     * so the full test stays open to replace it. A finished attempt without
     * answers counts as a skip too, which covers skips from before the
     * skipped flag.
     */
    public function handle(User $user, Language $language): bool
    {
        return PlacementTestAttempt::query()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->whereNotNull('completed_at')
            ->where('skipped', false)
            ->whereHas('responses')
            ->exists();
    }
}
