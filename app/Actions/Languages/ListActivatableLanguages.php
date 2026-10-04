<?php

declare(strict_types=1);

namespace App\Actions\Languages;

use App\Actions\ComputeBlendedCefrLevel;
use App\Actions\GetUserSkillLevels;
use App\Enums\CefrLevel;
use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Collection;

final class ListActivatableLanguages
{
    public function __construct(
        private readonly ComputeBlendedCefrLevel $computeBlendedCefrLevel = new ComputeBlendedCefrLevel,
        private readonly GetUserSkillLevels $getUserSkillLevels = new GetUserSkillLevels,
    ) {}

    /**
     * A language is offered once its content is released (config/languages.php)
     * and the user has reached A2 in any language they already have.
     *
     * @return Collection<int, Language>
     */
    public function handle(User $user): Collection
    {
        $unlocked = $user->unlockedLanguages()->get();

        if (! $this->hasReachedA2($user, $unlocked)) {
            return new Collection;
        }

        return Language::query()
            ->whereIn('code', config()->array('languages.activatable'))
            ->whereNotIn('id', $unlocked->modelKeys())
            ->orderBy('name')
            ->get();
    }

    /**
     * @param  Collection<int, Language>  $unlocked
     */
    private function hasReachedA2(User $user, Collection $unlocked): bool
    {
        return $unlocked->contains(function (Language $language) use ($user): bool {
            $blendedLevel = $this->computeBlendedCefrLevel->handle($this->getUserSkillLevels->handle($user, $language));

            return $blendedLevel !== null && $blendedLevel->sortOrder() >= CefrLevel::A2->sortOrder();
        });
    }
}
