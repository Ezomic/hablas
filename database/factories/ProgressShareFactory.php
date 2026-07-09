<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\ProgressShare;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ProgressShare>
 */
class ProgressShareFactory extends Factory
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
            'token' => Str::random(48),
            'revoked_at' => null,
        ];
    }
}
