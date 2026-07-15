<?php

namespace Database\Factories;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Language>
 */
class LanguageFactory extends Factory
{
    /**
     * Process-lifetime counter for synthetic language codes. Laravel evaluates
     * the full definition() on every create — even when the caller overrides
     * 'code' — so faker's unique()->languageCode() drew from its ~184-value ISO
     * pool on every row, risking both a collision with the 'es'/'pt' codes the
     * suite hardcodes and outright pool exhaustion across a full run. A
     * monotonic sequence sidesteps the bounded pool entirely.
     */
    private static int $sequence = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sequence = ++self::$sequence;

        return [
            'code' => 'lng-'.$sequence,
            'name' => 'Language '.$sequence,
        ];
    }
}
