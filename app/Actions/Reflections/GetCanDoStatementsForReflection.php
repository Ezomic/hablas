<?php

namespace App\Actions\Reflections;

use App\Actions\GetUserSkillLevels;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\CefrCanDoStatement;
use App\Models\Language;
use App\Models\User;
use App\Models\UserSkillLevel;
use Illuminate\Database\Eloquent\Collection;

class GetCanDoStatementsForReflection
{
    /**
     * Surfaces the can-do statements for each skill at the user's current
     * per-skill level, defaulting to A1 for skills without a recorded level
     * yet (e.g. before a placement test).
     *
     * @return Collection<int, CefrCanDoStatement>
     */
    public function handle(User $user, Language $language): Collection
    {
        $skillLevels = (new GetUserSkillLevels)->handle($user, $language)
            ->mapWithKeys(fn (UserSkillLevel $skillLevel): array => [$skillLevel->skill->value => $skillLevel->cefr_level]);

        $query = CefrCanDoStatement::query();

        foreach (Skill::cases() as $skill) {
            $level = $skillLevels[$skill->value] ?? CefrLevel::A1;

            $query->orWhere(fn ($skillQuery) => $skillQuery->where('skill', $skill)->where('cefr_level', $level));
        }

        return $query->get();
    }
}
