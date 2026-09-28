<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\CefrLevel;
use App\Models\UserSkillLevel;
use Illuminate\Support\Collection;

final class ComputeBlendedCefrLevel
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
