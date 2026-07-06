<?php

use App\Models\Language;
use App\Models\ScriptedPromptExercise;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\ScriptedPromptExerciseSeeder;
use Database\Seeders\SpanishA1Seeder;

it('seeds scripted prompt exercises scoped to Spanish', function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(SpanishA1Seeder::class);
    $this->seed(ScriptedPromptExerciseSeeder::class);

    $spanish = Language::query()->where('code', 'es')->sole();

    expect(ScriptedPromptExercise::query()->count())->toBeGreaterThan(0)
        ->and(ScriptedPromptExercise::query()->where('language_id', '!=', $spanish->id)->exists())->toBeFalse();
});

it('is idempotent when run twice', function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(SpanishA1Seeder::class);
    $this->seed(ScriptedPromptExerciseSeeder::class);
    $countAfterFirstRun = ScriptedPromptExercise::query()->count();

    $this->seed(ScriptedPromptExerciseSeeder::class);

    expect(ScriptedPromptExercise::query()->count())->toBe($countAfterFirstRun);
});
