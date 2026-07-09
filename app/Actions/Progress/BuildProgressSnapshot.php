<?php

namespace App\Actions\Progress;

use App\Actions\ComputeBlendedCefrLevel;
use App\Actions\GetUserSkillLevels;
use App\Enums\UnitProgressStatus;
use App\Models\Language;
use App\Models\Streak;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserSkillLevel;

class BuildProgressSnapshot
{
    /**
     * @return array{
     *     language: array{code: string, name: string},
     *     blendedLevel: string|null,
     *     skillLevels: array<string, string>,
     *     streak: array{currentLength: int, longestLength: int},
     *     unitCompletionPercentage: int,
     *     topErrorTags: list<array{category: string, count: int}>,
     * }
     */
    public function handle(User $user, Language $language): array
    {
        $skillLevels = (new GetUserSkillLevels)->handle($user, $language);
        // A plain read, not ReconcileStreak — this action is called from the
        // public, unauthenticated share page as well as the owner's own
        // preview, and reconciling persists writes (streak resets, freeze-day
        // consumption). A stranger merely viewing a shared link must never
        // mutate the link owner's data. The owner's own dashboard already
        // reconciles on every visit, so the streak shown here is stale only
        // in the rare case they haven't opened the app in over a day.
        $streak = Streak::query()->where('user_id', $user->id)->first();

        $totalUnits = Unit::query()->where('language_id', $language->id)->count();
        $completedUnits = $user->unitProgress()
            ->whereHas('unit', fn ($query) => $query->where('language_id', $language->id))
            ->where('status', UnitProgressStatus::Completed)
            ->count();

        return [
            'language' => ['code' => $language->code, 'name' => $language->name],
            'blendedLevel' => (new ComputeBlendedCefrLevel)->handle($skillLevels)?->value,
            'skillLevels' => $skillLevels->mapWithKeys(fn (UserSkillLevel $skillLevel): array => [
                $skillLevel->skill->value => $skillLevel->cefr_level->value,
            ])->all(),
            'streak' => [
                'currentLength' => $streak === null ? 0 : $streak->current_length,
                'longestLength' => $streak === null ? 0 : $streak->longest_length,
            ],
            'unitCompletionPercentage' => $totalUnits > 0
                ? (int) round(($completedUnits / $totalUnits) * 100)
                : 0,
            'topErrorTags' => array_values((new GetMostFrequentErrorTags)->handle($user, $language)
                ->map(fn (array $row): array => [
                    'category' => $row['error_tag_category']->value,
                    'count' => $row['count'],
                ])->all()),
        ];
    }
}
