<?php

namespace Database\Factories;

use App\Enums\SrsRating;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SrsReview>
 */
class SrsReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'srs_card_id' => SrsCard::factory(),
            'user_id' => User::factory(),
            'rating' => $this->faker->randomElement(SrsRating::cases()),
            'error_tag_category' => null,
            'reviewed_at' => now(),
        ];
    }
}
