<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\LessonStage;
use App\Models\Lesson;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lesson>
 */
class LessonFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'stage' => LessonStage::Meet,
            'position' => 1,
            'title' => LessonStage::Meet->title(),
        ];
    }

    public function stage(LessonStage $stage): static
    {
        return $this->state(fn (): array => [
            'stage' => $stage,
            'position' => $stage->position(),
            'title' => $stage->title(),
        ]);
    }
}
