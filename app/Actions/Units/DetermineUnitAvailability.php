<?php

declare(strict_types=1);

namespace App\Actions\Units;

use App\Actions\ComputeBlendedCefrLevel;
use App\Actions\GetUserSkillLevels;
use App\Actions\Srs\EvaluateSessionHealth;
use App\Enums\CefrLevel;
use App\Enums\UnitAvailability;
use App\Enums\UnitProgressStatus;
use App\Models\Language;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Collection;

final class DetermineUnitAvailability
{
    public function __construct(
        private readonly GetUserSkillLevels $getUserSkillLevels = new GetUserSkillLevels,
        private readonly ComputeBlendedCefrLevel $computeBlendedCefrLevel = new ComputeBlendedCefrLevel,
        private readonly EvaluateSessionHealth $evaluateSessionHealth = new EvaluateSessionHealth,
    ) {}

    /**
     * The first rule that applies wins:
     * - a completed unit stays open as a reference, even when a later
     *   placement puts the learner below its level;
     * - a unit with a lesson started stays open until it is finished, never
     *   locked or held back, whatever the review deck or a re-placement says;
     * - a unit above the blended level is locked, on the same ceiling
     *   SelectNextUnit picks from (A1 before any placement);
     * - while recent reviews need remediation, new units are held back, just
     *   as the dashboard holds back its next unit.
     *
     * @param  Collection<int, Unit>  $units
     * @return array<int, UnitAvailability> keyed by unit id
     */
    public function handle(User $user, Language $language, Collection $units): array
    {
        if ($units->isEmpty()) {
            return [];
        }

        $unlockedLevels = CefrLevel::upTo(
            $this->computeBlendedCefrLevel->handle($this->getUserSkillLevels->handle($user, $language)) ?? CefrLevel::A1,
        );

        $progress = $user->unitProgress()
            ->whereIn('status', [UnitProgressStatus::Completed, UnitProgressStatus::InProgress])
            ->whereIn('unit_id', $units->pluck('id'))
            ->get(['unit_id', 'status']);

        $completedUnitIds = $progress->where('status', UnitProgressStatus::Completed)->pluck('unit_id');
        $inProgressUnitIds = $progress->where('status', UnitProgressStatus::InProgress)->pluck('unit_id');

        $heldBack = $this->evaluateSessionHealth->handle($user, $language);

        return $units->mapWithKeys(fn (Unit $unit): array => [$unit->id => match (true) {
            $completedUnitIds->contains($unit->id) => UnitAvailability::Completed,
            $inProgressUnitIds->contains($unit->id) => UnitAvailability::InProgress,
            ! in_array($unit->cefr_level, $unlockedLevels, true) => UnitAvailability::Locked,
            $heldBack => UnitAvailability::HeldBack,
            default => UnitAvailability::Available,
        }])->all();
    }
}
