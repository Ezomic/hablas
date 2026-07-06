<?php

namespace App\Actions\Reflections;

use App\Models\Language;
use App\Models\User;
use App\Models\WeeklyReflection;
use Carbon\CarbonImmutable;

class SubmitWeeklyReflection
{
    /**
     * @param  array<int, int>  $statementIds  every statement id presented to the user this week
     * @param  array<int, int>  $canDoIds  the subset the user marked as "I can do this"
     */
    public function handle(User $user, Language $language, array $statementIds, array $canDoIds): WeeklyReflection
    {
        return WeeklyReflection::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'language_id' => $language->id,
                'week_start_date' => CarbonImmutable::now()->startOfWeek(),
            ],
            [
                'responses' => [
                    'statement_ids' => $statementIds,
                    'can_do_ids' => $canDoIds,
                ],
                'submitted_at' => now(),
            ],
        );
    }
}
