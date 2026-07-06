<?php

use App\Models\Language;
use App\Models\ShadowingExercise;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\ShadowingExerciseSeeder;
use Database\Seeders\SpanishA1Seeder;

it('seeds shadowing exercises scoped to Spanish', function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(SpanishA1Seeder::class);
    $this->seed(ShadowingExerciseSeeder::class);

    $spanish = Language::query()->where('code', 'es')->sole();

    expect(ShadowingExercise::query()->count())->toBeGreaterThan(0)
        ->and(ShadowingExercise::query()->where('language_id', '!=', $spanish->id)->exists())->toBeFalse();
});

it('is idempotent when run twice', function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(SpanishA1Seeder::class);
    $this->seed(ShadowingExerciseSeeder::class);
    $countAfterFirstRun = ShadowingExercise::query()->count();

    $this->seed(ShadowingExerciseSeeder::class);

    expect(ShadowingExercise::query()->count())->toBe($countAfterFirstRun);
});
