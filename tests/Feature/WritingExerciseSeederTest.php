<?php

use App\Enums\WritingExerciseType;
use App\Models\Language;
use App\Models\WritingExercise;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\WritingExerciseSeeder;

it('seeds all three exercise types, scoped to Spanish', function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(WritingExerciseSeeder::class);

    $spanish = Language::query()->where('code', 'es')->sole();

    foreach (WritingExerciseType::cases() as $type) {
        expect(WritingExercise::query()->where('language_id', $spanish->id)->where('type', $type)->exists())->toBeTrue();
    }

    expect(WritingExercise::query()->where('language_id', '!=', $spanish->id)->exists())->toBeFalse();
});

it('is idempotent when run twice', function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(WritingExerciseSeeder::class);
    $countAfterFirstRun = WritingExercise::query()->count();

    $this->seed(WritingExerciseSeeder::class);

    expect(WritingExercise::query()->count())->toBe($countAfterFirstRun);
});
