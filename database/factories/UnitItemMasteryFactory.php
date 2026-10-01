<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MasteryScope;
use App\Models\LessonRun;
use App\Models\Unit;
use App\Models\UnitItemMastery;
use App\Models\User;
use App\Models\VocabularyItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UnitItemMastery>
 */
class UnitItemMasteryFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'unit_id' => Unit::factory(),
            'masterable_type' => (new VocabularyItem)->getMorphClass(),
            'masterable_id' => VocabularyItem::factory(),
            'scope' => MasteryScope::Full,
            'lesson_run_id' => LessonRun::factory(),
            'mastered_at' => now(),
        ];
    }
}
