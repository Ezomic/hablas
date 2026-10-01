<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\LessonStage;
use App\Enums\MasteryScope;
use App\Lessons\TargetRef;
use App\Lessons\WordExercises;
use App\Models\Lesson;
use App\Models\LessonExercise;
use App\Models\Unit;
use App\Models\UnitItemMastery;
use App\Models\User;

/**
 * What a learner has mastered in a unit and what is still missing. A unit
 * whose check holds only typed word recall (its sentence and grammar lessons
 * are not yet released) can only prove its words; only the full check can
 * prove the grammar point and complete the unit.
 */
final class UnitMasteryReader
{
    public function scope(Unit $unit): MasteryScope
    {
        $check = Lesson::query()->where('unit_id', $unit->id)->where('stage', LessonStage::Check)->first();

        if ($check === null) {
            return MasteryScope::Words;
        }

        $authored = LessonExercise::query()
            ->where('lesson_id', $check->id)
            ->whereNull('retired_at')
            ->whereNull('substitute_for_id')
            ->where('block', '!=', WordExercises::CHECK_RECALL_BLOCK)
            ->exists();

        return $authored ? MasteryScope::Full : MasteryScope::Words;
    }

    /**
     * Every item the unit's check can currently prove.
     *
     * @return list<TargetRef>
     */
    public function items(Unit $unit): array
    {
        $items = [];

        foreach ($unit->vocabularyItems()->orderBy('id')->get(['id']) as $item) {
            $items[] = TargetRef::vocabulary($item->id);
        }

        if ($this->scope($unit) === MasteryScope::Full) {
            foreach ($unit->grammarPoints()->orderBy('id')->get(['id']) as $point) {
                $items[] = TargetRef::grammar($point->id);
            }
        }

        return $items;
    }

    /** @return list<TargetRef> */
    public function missing(User $user, Unit $unit): array
    {
        $scope = $this->scope($unit);
        $mastered = UnitItemMastery::query()
            ->where('user_id', $user->id)
            ->where('unit_id', $unit->id)
            ->when($scope === MasteryScope::Full, fn ($query) => $query->where('scope', MasteryScope::Full))
            ->get(['masterable_type', 'masterable_id'])
            ->mapWithKeys(fn (UnitItemMastery $row): array => [TargetRef::keyFor($row->masterable_type, $row->masterable_id) => true])
            ->all();

        return array_values(array_filter(
            $this->items($unit),
            fn (TargetRef $ref): bool => ! isset($mastered[$ref->key()]),
        ));
    }
}
