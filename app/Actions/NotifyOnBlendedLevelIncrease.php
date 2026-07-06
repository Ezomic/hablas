<?php

namespace App\Actions;

use App\Enums\CefrLevel;
use App\Models\Language;
use App\Models\User;
use Closure;
use Inertia\Inertia;

class NotifyOnBlendedLevelIncrease
{
    /**
     * Wraps a mutation that might change the user's skill levels (a placement
     * test, a skill-level reassessment), comparing the blended CEFR level
     * before and after and flashing a celebratory toast if it increased.
     */
    public function handle(User $user, Language $language, Closure $mutate): void
    {
        $levelBefore = $this->blendedLevel($user, $language);

        $mutate();

        $levelAfter = $this->blendedLevel($user, $language);

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

    private function blendedLevel(User $user, Language $language): ?CefrLevel
    {
        return (new ComputeBlendedCefrLevel)->handle((new GetUserSkillLevels)->handle($user, $language));
    }
}
