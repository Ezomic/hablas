<?php

namespace App\Actions;

use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;
use App\Models\UserSkillLevel;

class SkipPlacementTest
{
    public function handle(User $user, Language $language): PlacementTestAttempt
    {
        foreach (Skill::cases() as $skill) {
            UserSkillLevel::query()->updateOrCreate(
                ['user_id' => $user->id, 'language_id' => $language->id, 'skill' => $skill->value],
                ['cefr_level' => CefrLevel::A1->value],
            );
        }

        return PlacementTestAttempt::create([
            'user_id' => $user->id,
            'language_id' => $language->id,
            'started_at' => now(),
            'completed_at' => now(),
            'resulting_skill_levels' => collect(Skill::cases())
                ->mapWithKeys(fn (Skill $skill): array => [$skill->value => CefrLevel::A1->value])
                ->all(),
        ]);
    }
}
