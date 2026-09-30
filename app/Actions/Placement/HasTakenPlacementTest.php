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
     * re-take. A skip finishes an attempt without answers and does not count,
     * so the full test stays open to replace it.
     */
    public function handle(User $user, Language $language): bool
    {
        return PlacementTestAttempt::query()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->whereNotNull('completed_at')
            ->whereHas('responses')
            ->exists();
    }
}
