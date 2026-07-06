<?php

namespace Database\Factories;

use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\User;
use App\Models\UserSkillLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserSkillLevel>
 */
class UserSkillLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'language_id' => Language::factory(),
            'skill' => $this->faker->randomElement(Skill::cases()),
            'cefr_level' => $this->faker->randomElement(CefrLevel::cases()),
        ];
    }
}
