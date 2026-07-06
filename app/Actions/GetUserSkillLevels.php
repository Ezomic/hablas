<?php

namespace App\Actions;

use App\Models\Language;
use App\Models\User;
use App\Models\UserSkillLevel;
use Illuminate\Database\Eloquent\Collection;

class GetUserSkillLevels
{
    /** @return Collection<int, UserSkillLevel> */
    public function handle(User $user, Language $language): Collection
    {
        return $user->skillLevels()
            ->where('language_id', $language->id)
            ->get();
    }
}
