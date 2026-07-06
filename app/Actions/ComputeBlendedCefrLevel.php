<?php

namespace App\Actions;

use App\Enums\CefrLevel;
use App\Models\UserSkillLevel;
use Illuminate\Support\Collection;

class ComputeBlendedCefrLevel
{
    /** @param  Collection<int, UserSkillLevel>  $skillLevels */
    public function handle(Collection $skillLevels): ?CefrLevel
    {
        $levels = $skillLevels->map(fn (UserSkillLevel $skillLevel): CefrLevel => $skillLevel->cefr_level);

        if ($levels->isEmpty()) {
            return null;
        }

        return CefrLevel::lowest(...$levels);
    }
}
