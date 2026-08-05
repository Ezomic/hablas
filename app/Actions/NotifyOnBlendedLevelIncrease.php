<?php

namespace App\Actions;

use App\Enums\CefrLevel;
use App\Models\Language;
use App\Models\User;
use Closure;

class NotifyOnBlendedLevelIncrease
{
    public function __construct(
        private readonly ComputeBlendedCefrLevel $computeBlendedCefrLevel = new ComputeBlendedCefrLevel,
        private readonly GetUserSkillLevels $getUserSkillLevels = new GetUserSkillLevels,
    ) {}

    /**
     * Wraps a mutation that might change the user's skill levels (a placement
     * test, a skill-level reassessment), comparing the blended CEFR level
     * before and after and returning a celebratory toast payload if it
     * increased.
     *
     * Delivery is the caller's job rather than this action's: the practice
     * endpoints answer with JSON over fetch, where an Inertia flash would sit
     * in the session and pop on some unrelated later navigation, while the
     * placement flow does navigate through Inertia and wants exactly that.
     *
     * @return array{type: string, message: string}|null
     */
    public function handle(User $user, Language $language, Closure $mutate): ?array
    {
        $levelBefore = $this->blendedLevel($user, $language);

        $mutate();

        $levelAfter = $this->blendedLevel($user, $language);

        if ($levelAfter === null) {
            return null;
        }

        if ($levelBefore !== null && $levelAfter->sortOrder() <= $levelBefore->sortOrder()) {
            return null;
        }

        return [
            'type' => 'milestone',
            'message' => "You've reached {$levelAfter->value} in {$language->name}!",
        ];
    }

    private function blendedLevel(User $user, Language $language): ?CefrLevel
    {
        return $this->computeBlendedCefrLevel->handle($this->getUserSkillLevels->handle($user, $language));
    }
}
