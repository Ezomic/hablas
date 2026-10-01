<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Skill;
use App\Models\Language;
use App\Models\LessonRun;
use App\Models\LessonSkillScore;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LessonSkillScore>
 */
class LessonSkillScoreFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'language_id' => Language::factory(),
            'lesson_run_id' => LessonRun::factory(),
            'skill' => Skill::Writing,
            'score' => 100.0,
            'graded_count' => 5,
            'counts_toward_level' => true,
            'scored_at' => now(),
        ];
    }
}
