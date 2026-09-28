<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * The app's reference and course content: languages, units, vocabulary,
 * placement items, exercises and CEFR can-do statements.
 *
 * Deliberately contains NO user fixtures, so it is safe to run against
 * production, unlike DatabaseSeeder, which also creates a Test User. Every
 * successful `artisan migrate` runs this (SeedCourseContentAfterMigrating), and
 * that is how each release brings production up to date. Every seeder it calls
 * uses updateOrCreate, so it is idempotent.
 */
class ContentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(LanguageSeeder::class);
        $this->call(SpanishA1Seeder::class);
        $this->call(PortugueseA1Seeder::class);
        $this->call(PlacementTestSeeder::class);
        $this->call(PortuguesePlacementTestSeeder::class);
        $this->call(ReadingPassageSeeder::class);
        $this->call(ListeningExerciseSeeder::class);
        $this->call(ShadowingExerciseSeeder::class);
        $this->call(PronunciationDrillExerciseSeeder::class);
        $this->call(ScriptedPromptExerciseSeeder::class);
        $this->call(WritingExerciseSeeder::class);
        $this->call(CefrCanDoStatementSeeder::class);
    }
}
