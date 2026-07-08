<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\PronunciationDrillExercise;
use Illuminate\Database\Seeder;

/**
 * Nasal-vowel minimal-pair drills for Portuguese. Each pair is seeded twice
 * — once per target direction — so both words of the pair get drilled. Not
 * tied to a specific A1 unit: pronunciation is a cross-cutting skill, not a
 * topic. AI-drafted; ship with only pairs that are genuinely verified
 * minimal (or near-minimal) pairs on the nasal feature rather than padding
 * to a round number — needs a native/near-native Portuguese speaker's
 * review pass before being treated as authoritative, same caveat as the
 * other Portuguese content seeders.
 */
class PronunciationDrillExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portuguese = Language::query()->where('code', 'pt')->firstOrFail();

        foreach ($this->pairs() as $pair) {
            foreach ([$pair['word_a'], $pair['word_b']] as $targetWord) {
                PronunciationDrillExercise::query()->updateOrCreate(
                    [
                        'language_id' => $portuguese->id,
                        'unit_id' => null,
                        'word_a' => $pair['word_a'],
                        'word_b' => $pair['word_b'],
                        'target_word' => $targetWord,
                    ],
                    [
                        'word_a_translation_en' => $pair['word_a_translation_en'],
                        'word_b_translation_en' => $pair['word_b_translation_en'],
                    ],
                );
            }
        }
    }

    /**
     * @return array<int, array{
     *     word_a: string,
     *     word_a_translation_en: string,
     *     word_b: string,
     *     word_b_translation_en: string,
     * }>
     */
    private function pairs(): array
    {
        return [
            ['word_a' => 'pão', 'word_a_translation_en' => 'bread', 'word_b' => 'pau', 'word_b_translation_en' => 'stick'],
            ['word_a' => 'mão', 'word_a_translation_en' => 'hand', 'word_b' => 'mau', 'word_b_translation_en' => 'bad'],
            ['word_a' => 'lã', 'word_a_translation_en' => 'wool', 'word_b' => 'lá', 'word_b_translation_en' => 'there'],
            ['word_a' => 'maçã', 'word_a_translation_en' => 'apple', 'word_b' => 'massa', 'word_b_translation_en' => 'pasta / dough'],
            ['word_a' => 'irmã', 'word_a_translation_en' => 'sister', 'word_b' => 'irmão', 'word_b_translation_en' => 'brother'],
        ];
    }
}
