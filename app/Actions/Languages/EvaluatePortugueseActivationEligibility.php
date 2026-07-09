<?php

namespace App\Actions\Languages;

use App\Actions\ComputeBlendedCefrLevel;
use App\Actions\GetUserSkillLevels;
use App\Enums\CefrLevel;
use App\Models\Language;
use App\Models\User;

class EvaluatePortugueseActivationEligibility
{
    /**
     * Hardcodes the Spanish→Portuguese unlock specifically (matching the
     * existing convention of hardcoding 'es'/'pt' in the content seeders)
     * rather than generalizing to an N-language gate this app doesn't need
     * yet.
     */
    public function handle(User $user): bool
    {
        $portuguese = Language::query()->where('code', 'pt')->first();

        if ($portuguese === null || $user->unlockedLanguages()->where('languages.id', $portuguese->id)->exists()) {
            return false;
        }

        $spanish = Language::query()->where('code', 'es')->first();

        if ($spanish === null) {
            return false;
        }

        $blendedLevel = (new ComputeBlendedCefrLevel)->handle(
            (new GetUserSkillLevels)->handle($user, $spanish),
        );

        return $blendedLevel !== null && $blendedLevel->sortOrder() >= CefrLevel::A2->sortOrder();
    }
}
