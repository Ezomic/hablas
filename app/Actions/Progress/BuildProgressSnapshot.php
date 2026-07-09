<?php

namespace App\Actions\Progress;

use App\Actions\ComputeBlendedCefrLevel;
use App\Actions\GetUserSkillLevels;
use App\Actions\Streaks\ReconcileStreak;
use App\Enums\UnitProgressStatus;
use App\Models\Language;
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
        $streak = (new ReconcileStreak)->handle($user);

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
                'currentLength' => $streak->current_length,
                'longestLength' => $streak->longest_length,
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
