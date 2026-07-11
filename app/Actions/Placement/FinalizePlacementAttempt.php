<?php

namespace App\Actions\Placement;

use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\PlacementTestAttempt;
use App\Models\UserSkillLevel;
use Closure;
use Illuminate\Support\Facades\DB;

class FinalizePlacementAttempt
{
    /**
     * @param  (Closure(Skill): CefrSubLevel)|null  $resolveTier  Defaults to replaying the attempt's response history via DeriveCurrentPlacementTier. SkipPlacementTest passes a resolver that always returns the A1 floor instead.
     */
    public function handle(PlacementTestAttempt $attempt, ?Closure $resolveTier = null): PlacementTestAttempt
    {
        $resolveTier ??= fn (Skill $skill): CefrSubLevel => (new DeriveCurrentPlacementTier)->handle($attempt, $skill);

        // Wrapped so a failure partway through never leaves some skills'
        // UserSkillLevel rows updated while others (and the attempt's own
        // completed_at) are not — either the whole finalization lands or
        // none of it does.
        return DB::transaction(function () use ($attempt, $resolveTier): PlacementTestAttempt {
            $resultingLevels = [];

            foreach (Skill::cases() as $skill) {
                $tier = $resolveTier($skill);

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
        });
    }
}
