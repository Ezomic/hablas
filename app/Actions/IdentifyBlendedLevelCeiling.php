<?php

namespace App\Actions;

use App\Enums\Skill;
use App\Models\UserSkillLevel;
use Illuminate\Support\Collection;

class IdentifyBlendedLevelCeiling
{
    /**
     * The blended headline level is deliberately the minimum across all four
     * skills. When that floor is pinned by a placement-only skill (Reading or
     * Listening, which have no daily practice in Milestone 1) while another
     * skill has climbed above it, the headline number sits frozen no matter
     * how much the learner practices — and the level-up notice never fires.
     *
     * Returns the placement-only skills sitting at that floor so the dashboard
     * can explain the ceiling instead of leaving it silent. Returns an empty
     * collection when nothing is capping progress (all skills level, or the
     * floor is a skill that practice can still move).
     *
     * @param  Collection<int, UserSkillLevel>  $skillLevels
     * @return Collection<int, Skill>
     */
    public function handle(Collection $skillLevels): Collection
    {
        if ($skillLevels->isEmpty()) {
            return collect();
        }

        $orders = $skillLevels->map(fn (UserSkillLevel $skillLevel): int => $skillLevel->cefr_level->sortOrder());

        // Nothing is being held back unless some skill is actually ahead of the floor.
        if ($orders->max() <= $orders->min()) {
            return collect();
        }

        return $skillLevels
            ->filter(fn (UserSkillLevel $skillLevel): bool => $skillLevel->skill->isPlacementOnly()
                && $skillLevel->cefr_level->sortOrder() === $orders->min())
            ->map(fn (UserSkillLevel $skillLevel): Skill => $skillLevel->skill)
            ->values();
    }
}
