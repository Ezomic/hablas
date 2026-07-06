<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\ShadowingExercise;
use App\Models\Unit;
use Illuminate\Database\Seeder;

/**
 * Tier-1 shadowing exercises: one phrase per seeded Spanish A1 unit, reusing
 * vocabulary already introduced there. AI-drafted; needs the same human
 * review pass as the other content seeders before being authoritative.
 */
class ShadowingExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spanish = Language::query()->where('code', 'es')->firstOrFail();

        foreach ($this->exercises() as $unitSlug => $targetTranscript) {
            $unit = Unit::query()->where('language_id', $spanish->id)->where('slug', $unitSlug)->first();

            ShadowingExercise::query()->updateOrCreate(
                ['language_id' => $spanish->id, 'unit_id' => $unit?->id, 'target_transcript' => $targetTranscript],
                [],
            );
        }
    }

    /** @return array<string, string> */
    private function exercises(): array
    {
        return [
            'greetings-and-introductions' => 'Hola, me llamo Ana. Mucho gusto.',
            'at-the-airport' => 'El vuelo está retrasado.',
            'checking-into-a-hotel' => '¿Hay una habitación disponible para dos noches?',
            'ordering-food-at-a-restaurant' => 'Quisiera agua y la cuenta, por favor.',
            'asking-for-directions' => '¿Dónde está la plaza? Gire a la derecha.',
            'shopping-for-clothes' => '¿Cuánto cuesta esta camisa roja?',
            'talking-about-your-family' => 'Mi hermana es mayor que yo.',
            'describing-your-daily-routine' => 'Normalmente me levanto temprano todos los días.',
        ];
    }
}
