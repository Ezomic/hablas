<?php

namespace App\Actions;

use App\Enums\CefrLevel;
use App\Models\Language;
use App\Models\User;
use Inertia\Inertia;

class NotifyOnBlendedLevelIncrease
{
    /**
     * Compares the blended CEFR level against a level captured before some
     * mutation (a placement test, a skill-level reassessment) and flashes a
     * celebratory toast if it increased. Takes the "before" level rather than
     * a callback so callers keep their own mutation logic in their own Action.
     */
    public function handle(User $user, Language $language, ?CefrLevel $levelBefore): void
    {
        $levelAfter = (new ComputeBlendedCefrLevel)->handle((new GetUserSkillLevels)->handle($user, $language));

        if ($levelAfter === null) {
            return;
        }

        if ($levelBefore !== null && $levelAfter->sortOrder() <= $levelBefore->sortOrder()) {
            return;
        }

        Inertia::flash('toast', [
            'type' => 'milestone',
            'message' => "You've reached {$levelAfter->value} in {$language->name}!",
        ]);
    }
}
