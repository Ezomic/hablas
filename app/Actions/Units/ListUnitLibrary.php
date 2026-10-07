<?php

declare(strict_types=1);

namespace App\Actions\Units;

use App\Enums\LessonState;
use App\Models\Language;
use App\Models\Unit;
use App\Models\UnitItemMastery;
use App\Models\User;
use App\Services\LessonProgress;
use App\Services\UnitStars;
use App\Services\UnitStruggles;
use App\Services\WordProgress;

final class ListUnitLibrary
{
    public function __construct(
        private readonly DetermineUnitAvailability $determineUnitAvailability = new DetermineUnitAvailability,
        private readonly LessonProgress $lessonProgress = new LessonProgress,
        private readonly WordProgress $wordProgress = new WordProgress,
        private readonly UnitStars $unitStars = new UnitStars,
        private readonly UnitStruggles $unitStruggles = new UnitStruggles,
    ) {}

    /**
     * @return list<array{id: int, title: string, taskDescription: string, cefrLevel: string, primarySkill: string, availability: string, lessonCount: int, lessonsCompleted: int, masteredCount: int, struggles: int}>
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
        $states = [];

        foreach ($units as $unit) {
            $states[$unit->id] = array_values($this->lessonProgress->states($user, $unit));
        }

        $mastered = [];

        foreach (UnitItemMastery::query()->where('user_id', $user->id)->whereIn('unit_id', $units->pluck('id'))->get(['unit_id', 'masterable_type', 'masterable_id']) as $row) {
            $mastered[$row->unit_id][$row->masterable_type.':'.$row->masterable_id] = true;
        }

        $progress = $this->wordProgress->forUnits($user, $units);

        return array_values($units->map(fn (Unit $unit): array => [
            'id' => $unit->id,
            'title' => $unit->title,
            'taskDescription' => $unit->task_description,
            'cefrLevel' => $unit->cefr_level->value,
            'primarySkill' => $unit->primary_skill->value,
            'availability' => $availability[$unit->id]->value,
            'lessonCount' => count($states[$unit->id]),
            'lessonsCompleted' => count(array_filter($states[$unit->id], fn (LessonState $state): bool => $state === LessonState::Completed)),
            'masteredCount' => count($mastered[$unit->id] ?? []),
            'percent' => $progress[$unit->id]['percent'],
            'stars' => $this->unitStars->handle($user, $unit, $progress[$unit->id]['percent']),
            'struggles' => count($this->unitStruggles->handle($user, $unit)),
        ])->all());
    }
}
