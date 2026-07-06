<?php

namespace Database\Factories;

use App\Enums\SrsCardState;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use App\Models\VocabularyItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SrsCard>
 */
class SrsCardFactory extends Factory
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
            'cardable_type' => VocabularyItem::class,
            'cardable_id' => VocabularyItem::factory(),
            'state' => SrsCardState::New,
            'stability' => 0,
            'difficulty' => 0,
            'reps' => 0,
            'lapses' => 0,
            'consecutive_lapses' => 0,
            'is_weak_spot' => false,
            'due_at' => now(),
            'last_reviewed_at' => null,
        ];
    }
}
