<?php

namespace App\Actions\Placement;

use App\Enums\CefrLevel;
use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;
use App\Models\UserSkillLevel;
use Illuminate\Support\Facades\DB;

class SkipPlacementTest
{
    /**
     * Finalizes the user's in-progress attempt (creating one first if they
     * had never started) at the A1 floor for every skill — reuses
     * GetOrCreateInProgressPlacementAttempt rather than always inserting a
     * fresh attempt row, so skipping mid-test doesn't leave the
     * already-in-progress attempt dangling as an orphaned resumable row.
     */
    public function handle(User $user, Language $language): PlacementTestAttempt
    {
        return DB::transaction(function () use ($user, $language): PlacementTestAttempt {
            $attempt = (new GetOrCreateInProgressPlacementAttempt)->handle($user, $language);

            $resultingLevels = [];

            foreach (Skill::cases() as $skill) {
                $resultingLevels[$skill->value] = [
                    'cefr_level' => CefrLevel::A1->value,
                    'sub_level' => CefrSubLevel::A1_1->value,
                ];

                UserSkillLevel::query()->updateOrCreate(
                    ['user_id' => $user->id, 'language_id' => $language->id, 'skill' => $skill->value],
                    ['cefr_level' => CefrLevel::A1->value],
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
