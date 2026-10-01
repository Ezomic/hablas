<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\UserSkillLevel;
use Illuminate\Support\Collection;

final class DescribeBlendedLevel
{
    /**
     * The lowest skill, tier included, as the learner reads it ("A2.1", or
     * "C1" where a level has no tiers). It is a label only: everything that
     * gates content keeps reading the parent level from ComputeBlendedCefrLevel.
     *
     * @param  Collection<int, UserSkillLevel>  $skillLevels
     */
    public function handle(Collection $skillLevels): ?string
    {
        return $skillLevels
            ->sortBy(fn (UserSkillLevel $skillLevel): int => $skillLevel->cefr_level->sortOrder() * 100 + ($skillLevel->currentTier()?->sortOrder() ?? 0))
            ->first()
            ?->displayLevel();
    }
}
