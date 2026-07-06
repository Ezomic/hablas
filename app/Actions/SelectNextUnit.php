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

    /**
     * Travel-weighted first, per the priority-goal decision in category 2
     * of the Feature Brainstorm doc.
     *
     * @var array<string, int>
     */
    private const CONTEXT_TAG_PRIORITY = [
        'travel' => 0,
        'everyday_social' => 1,
        'professional' => 2,
    ];

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
            ->sortBy(fn (ContextTag $tag): int => self::CONTEXT_TAG_PRIORITY[$tag->value])
            ->first();

        $prioritized = $candidates->where('context_tag', $topPriorityTag);

        $recentSkillCounts = $this->recentSkillCounts($user);

        return $prioritized
            ->sortBy('sort_order')
            ->sortBy(fn (Unit $unit): int => $recentSkillCounts[$unit->primary_skill->value] ?? 0)
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
