<?php

use App\Enums\CefrLevel;
use App\Enums\ContextTag;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\Unit;
use App\Models\VocabularyItem;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\SpanishA1Seeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
});

it('seeds a representative slice of Spanish A1 units with vocabulary and grammar', function () {
    $this->seed(SpanishA1Seeder::class);

    expect(Unit::query()->count())->toBeGreaterThanOrEqual(6)
        ->and(VocabularyItem::query()->count())->toBeGreaterThan(0)
        ->and(GrammarPoint::query()->count())->toBeGreaterThan(0);
});

it('includes at least one travel-tagged A1 unit', function () {
    $this->seed(SpanishA1Seeder::class);

    $spanish = Language::query()->where('code', 'es')->sole();

    $travelUnits = Unit::query()
        ->where('language_id', $spanish->id)
        ->where('cefr_level', CefrLevel::A1)
        ->where('context_tag', ContextTag::Travel)
        ->count();

    expect($travelUnits)->toBeGreaterThan(0);
});

it('scopes every seeded unit, vocabulary item, and grammar point to Spanish', function () {
    $this->seed(SpanishA1Seeder::class);

    $spanish = Language::query()->where('code', 'es')->sole();

    expect(Unit::query()->where('language_id', '!=', $spanish->id)->exists())->toBeFalse()
        ->and(VocabularyItem::query()->where('language_id', '!=', $spanish->id)->exists())->toBeFalse()
        ->and(GrammarPoint::query()->where('language_id', '!=', $spanish->id)->exists())->toBeFalse();
});

it('is idempotent when run twice', function () {
    $this->seed(SpanishA1Seeder::class);
    $unitCountAfterFirstRun = Unit::query()->count();
    $vocabCountAfterFirstRun = VocabularyItem::query()->count();

    $this->seed(SpanishA1Seeder::class);

    expect(Unit::query()->count())->toBe($unitCountAfterFirstRun)
        ->and(VocabularyItem::query()->count())->toBe($vocabCountAfterFirstRun);
});
