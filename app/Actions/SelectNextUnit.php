<?php

namespace App\Actions;

use App\Enums\CefrLevel;
use App\Enums\ContextTag;
use App\Enums\Skill;
use App\Enums\UnitProgressStatus;
use App\Models\Language;
use App\Models\Unit;
use App\Models\User;

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

        return $prioritized
            ->sortBy(fn (Unit $unit): array => [$recentSkillCounts[$unit->primary_skill->value] ?? 0, $unit->sort_order])
            ->first();
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
