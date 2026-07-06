<?php

namespace App\Actions;

use App\Enums\CefrLevel;
use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;
use App\Enums\UnitProgressStatus;
use App\Models\Language;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Collection;

class SelectNextUnit
{
    /**
     * How many of the user's most recently completed units to look at when
     * balancing skill exposure — a rolling window, not the full history.
     */
    private const ROTATION_WINDOW = 10;

    public function handle(User $user, Language $language): ?Unit
    {
        $blendedLevel = (new ComputeBlendedCefrLevel)->handle(
            (new GetUserSkillLevels)->handle($user, $language),
        ) ?? CefrLevel::A1;

        $eligibleLevels = collect(CefrLevel::cases())
            ->filter(fn (CefrLevel $level): bool => $level->sortOrder() <= $blendedLevel->sortOrder())
            ->map(fn (CefrLevel $level): string => $level->value);

        $completedUnitIds = $user->unitProgress()
            ->where('status', UnitProgressStatus::Completed)
            ->pluck('unit_id');

        $candidates = Unit::query()
            ->where('language_id', $language->id)
            ->whereIn('cefr_level', $eligibleLevels)
            ->whereNotIn('id', $completedUnitIds)
            ->with('interestTags')
            ->orderBy('sort_order')
            ->get();

        if ($candidates->isEmpty()) {
            return null;
        }

        $topPriorityTag = $candidates
            ->pluck('context_tag')
            ->unique()
            ->sortBy(fn (ContextTag $tag): int => $tag->sortOrder())
            ->first();

        $prioritized = $candidates->where('context_tag', $topPriorityTag);

        $recentSkillCounts = $this->recentSkillCounts($user);
        $preferredInterestTags = $user->interestPreferences()->get()
            ->pluck('interest_tag')
            ->map(fn (InterestTag $tag): string => $tag->value);

        return $prioritized
            ->sortBy(fn (Unit $unit): array => [
                $recentSkillCounts[$unit->primary_skill->value] ?? 0,
                $this->interestScore($unit, $preferredInterestTags),
                $unit->sort_order,
            ])
            ->first();
    }

    /**
     * 0 when the unit overlaps the user's interest preferences (sorts first),
     * 1 otherwise — a no-op tiebreaker when the user has no preferences set,
     * so existing skill-balance/sort-order behavior is unaffected. Compares
     * on the enum's string value since array_intersect (used under the hood
     * by Collection::intersect) casts elements to strings for comparison,
     * which fails on enum instances.
     *
     * @param  Collection<int, string>  $preferredInterestTags
     */
    private function interestScore(Unit $unit, Collection $preferredInterestTags): int
    {
        if ($preferredInterestTags->isEmpty()) {
            return 0;
        }

        $matches = $unit->interestTags
            ->pluck('interest_tag')
            ->map(fn (InterestTag $tag): string => $tag->value)
            ->intersect($preferredInterestTags)
            ->isNotEmpty();

        return $matches ? 0 : 1;
    }

    /** @return array<string, int> */
    private function recentSkillCounts(User $user): array
    {
        $counts = collect(Skill::cases())->mapWithKeys(fn (Skill $skill): array => [$skill->value => 0])->all();

        $recentUnits = $user->unitProgress()
            ->where('status', UnitProgressStatus::Completed)
            ->latest('completed_at')
            ->limit(self::ROTATION_WINDOW)
            ->with('unit')
            ->get()
            ->pluck('unit');

        foreach ($recentUnits as $unit) {
            $counts[$unit->primary_skill->value]++;
        }

        return $counts;
    }
}
