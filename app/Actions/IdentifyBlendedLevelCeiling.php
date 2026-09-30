<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\Skill;
use App\Models\UserSkillLevel;
use Illuminate\Support\Collection;

final class IdentifyBlendedLevelCeiling
{
    /**
     * The blended headline level is deliberately the minimum across all four
     * skills, so the skills sitting at that floor hold it while another skill
     * has climbed above it. The dashboard names them, so the learner knows
     * which skill to practice, or to re-take if its placement landed wrong.
     *
     * Returns an empty collection when nothing is held back (every skill at
     * the same level).
     *
     * @param  Collection<int, UserSkillLevel>  $skillLevels
     * @return Collection<int, Skill>
     */
    public function handle(Collection $skillLevels): Collection
    {
        if ($skillLevels->isEmpty()) {
            return new Collection;
        }

        $orders = $skillLevels->map(fn (UserSkillLevel $skillLevel): int => $skillLevel->cefr_level->sortOrder());

        // Nothing is being held back unless some skill is actually ahead of the floor.
        if ($orders->max() <= $orders->min()) {
            return new Collection;
        }

        return $skillLevels
            ->filter(fn (UserSkillLevel $skillLevel): bool => $skillLevel->cefr_level->sortOrder() === $orders->min())
            ->map(fn (UserSkillLevel $skillLevel): Skill => $skillLevel->skill)
            ->values();
    }
}
