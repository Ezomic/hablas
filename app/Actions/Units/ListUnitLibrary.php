<?php

declare(strict_types=1);

namespace App\Actions\Units;

use App\Models\Language;
use App\Models\Unit;
use App\Models\User;

final class ListUnitLibrary
{
    public function __construct(
        private readonly DetermineUnitAvailability $determineUnitAvailability = new DetermineUnitAvailability,
    ) {}

    /**
     * @return list<array{id: int, title: string, taskDescription: string, cefrLevel: string, primarySkill: string, availability: string}>
     */
    public function handle(User $user, Language $language): array
    {
        $units = Unit::query()
            ->where('language_id', $language->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->sortBy(fn (Unit $unit): int => $unit->cefr_level->sortOrder())
            ->values();

        $availability = $this->determineUnitAvailability->handle($user, $language, $units);

        return array_values($units->map(fn (Unit $unit): array => [
            'id' => $unit->id,
            'title' => $unit->title,
            'taskDescription' => $unit->task_description,
            'cefrLevel' => $unit->cefr_level->value,
            'primarySkill' => $unit->primary_skill->value,
            'availability' => $availability[$unit->id]->value,
        ])->all());
    }
}
