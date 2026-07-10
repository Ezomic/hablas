<?php

namespace App\Actions\Placement;

use App\Enums\Skill;
use App\Models\PlacementTestAttempt;
use App\Models\UserSkillLevel;

class FinalizePlacementAttempt
{
    public function handle(PlacementTestAttempt $attempt): PlacementTestAttempt
    {
        $resultingLevels = [];

        foreach (Skill::cases() as $skill) {
            $tier = (new DeriveCurrentPlacementTier)->handle($attempt, $skill);

            $resultingLevels[$skill->value] = [
                'cefr_level' => $tier->parentLevel()->value,
                'sub_level' => $tier->value,
            ];

            UserSkillLevel::query()->updateOrCreate(
                ['user_id' => $attempt->user_id, 'language_id' => $attempt->language_id, 'skill' => $skill->value],
                ['cefr_level' => $tier->parentLevel()->value],
            );
        }

        $attempt->forceFill([
            'completed_at' => now(),
            'resulting_skill_levels' => $resultingLevels,
        ])->save();

        return $attempt;
    }
}
