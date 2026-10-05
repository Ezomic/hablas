<?php

declare(strict_types=1);

namespace App\Actions\Units;

use App\Models\Language;
use App\Models\Unit;
use App\Models\User;
use App\Services\UnitStars;
use App\Services\WordProgress;

final class GetUnitProgress
{
    public function __construct(
        private readonly WordProgress $wordProgress = new WordProgress,
        private readonly UnitStars $unitStars = new UnitStars,
    ) {}

    /**
     * What the unit page shows about progress: the share of its words known,
     * its stars, a state for each word, and the same share across the level.
     *
     * @return array{percent: int, known: int, total: int, stars: int, words: list<array{id: int, state: string}>, level: array{code: string, known: int, total: int, percent: int}}
     */
    public function handle(User $user, Language $language, Unit $unit): array
    {
        $summary = $this->wordProgress->forUnits($user, collect([$unit]))[$unit->id];

        return [
            ...$summary,
            'stars' => $this->unitStars->handle($user, $unit, $summary['percent']),
            'level' => ['code' => $unit->cefr_level->value, ...$this->wordProgress->forLevel($user, $language, $unit->cefr_level)],
        ];
    }
}
