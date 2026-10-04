<?php

declare(strict_types=1);

namespace App\Actions\Languages;

use App\Actions\ComputeBlendedCefrLevel;
use App\Actions\GetUserSkillLevels;
use App\Enums\CefrLevel;
use App\Models\Language;
use App\Models\User;

final class EvaluateLanguageActivationEligibility
{
    public function __construct(
        private readonly ComputeBlendedCefrLevel $computeBlendedCefrLevel = new ComputeBlendedCefrLevel,
        private readonly GetUserSkillLevels $getUserSkillLevels = new GetUserSkillLevels,
    ) {}

    /**
     * A language is offered once its content is released (config/languages.php)
     * and the user has reached A2 in any language they already have.
     */
    public function handle(User $user, Language $language): bool
    {
        if (! in_array($language->code, config()->array('languages.activatable'), true)) {
            return false;
        }

        $unlocked = $user->unlockedLanguages()->get();

        if ($unlocked->contains('id', $language->id)) {
            return false;
        }

        return $unlocked->contains(function (Language $known) use ($user): bool {
            $blendedLevel = $this->computeBlendedCefrLevel->handle($this->getUserSkillLevels->handle($user, $known));

            return $blendedLevel !== null && $blendedLevel->sortOrder() >= CefrLevel::A2->sortOrder();
        });
    }
}
