<?php

namespace App\Actions;

use App\Enums\CefrLevel;
use App\Models\Language;
use App\Models\User;
use App\Models\UserSkillLevel;

class ComputeBlendedCefrLevel
{
    public function handle(User $user, Language $language): ?CefrLevel
    {
        $levels = $user->skillLevels()
            ->where('language_id', $language->id)
            ->get()
            ->map(fn (UserSkillLevel $skillLevel): CefrLevel => $skillLevel->cefr_level);

        if ($levels->isEmpty()) {
            return null;
        }

        return CefrLevel::lowest(...$levels);
    }
}
