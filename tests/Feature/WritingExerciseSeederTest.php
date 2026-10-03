<?php

declare(strict_types=1);

use App\Enums\WritingExerciseType;
use App\Models\Language;
use App\Models\WritingExercise;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\SpanishA1Seeder;
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

it('links the exercises that its unit lessons copy to that unit, and leaves the others unlinked', function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(SpanishA1Seeder::class);
    $this->seed(WritingExerciseSeeder::class);

    $units = WritingExercise::query()->get()->mapWithKeys(fn (WritingExercise $exercise): array => [(string) ($exercise->template['text'] ?? $exercise->prompt) => $exercise->unit?->slug]);

    expect($units['Yo ___ estudiante.'])->toBe('greetings-and-introductions')
        ->and($units['la camisa ___'])->toBe('shopping-for-clothes')
        ->and($units['Él come a las dos.'])->toBe('asking-for-directions')
        ->and($units['Describe tu rutina diaria. Usa las palabras: levantarse, desayunar, trabajar.'])->toBe('describing-your-daily-routine')
        ->and($units['El hotel ___ cerca del aeropuerto.'])->toBeNull()
        ->and($units['Quiero café.'])->toBeNull();
});

it('links rows that an earlier seeding left without a unit', function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(WritingExerciseSeeder::class);

    expect(WritingExercise::query()->whereNotNull('unit_id')->count())->toBe(0);

    $this->seed(SpanishA1Seeder::class);
    $this->seed(WritingExerciseSeeder::class);

    expect(WritingExercise::query()->whereNotNull('unit_id')->count())->toBe(4)
        ->and(WritingExercise::query()->count())->toBe(6);
});
