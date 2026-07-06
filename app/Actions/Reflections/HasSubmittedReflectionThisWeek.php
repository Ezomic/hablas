<?php

namespace App\Actions\Reflections;

use App\Models\Language;
use App\Models\User;
use App\Models\WeeklyReflection;
use Carbon\CarbonImmutable;

class HasSubmittedReflectionThisWeek
{
    public function handle(User $user, Language $language): bool
    {
        return WeeklyReflection::query()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->where('week_start_date', CarbonImmutable::now()->startOfWeek())
            ->whereNotNull('submitted_at')
            ->exists();
    }
}
