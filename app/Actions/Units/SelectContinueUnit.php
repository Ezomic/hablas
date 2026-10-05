<?php

declare(strict_types=1);

namespace App\Actions\Units;

use App\Actions\SelectNextUnit;
use App\Actions\Srs\EvaluateSessionHealth;
use App\Enums\UnitProgressStatus;
use App\Models\Language;
use App\Models\Unit;
use App\Models\User;

final class SelectContinueUnit
{
    public function __construct(
        private readonly SelectNextUnit $selectNextUnit = new SelectNextUnit,
        private readonly EvaluateSessionHealth $evaluateSessionHealth = new EvaluateSessionHealth,
    ) {}

    /**
     * The unit the app opens in: the one to continue. Remediation only holds
     * back a new unit, so a unit the learner already started stays; with
     * nothing to continue, or a new unit held back, there is none.
     */
    public function handle(User $user, Language $language): ?Unit
    {
        $unit = $this->selectNextUnit->handle($user, $language);

        if ($unit === null) {
            return null;
        }

        $started = $user->unitProgress()->where('unit_id', $unit->id)->where('status', UnitProgressStatus::InProgress)->exists();

        return $this->evaluateSessionHealth->handle($user, $language) && ! $started ? null : $unit;
    }
}
