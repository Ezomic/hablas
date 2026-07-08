<?php

use App\Enums\CefrLevel;
use App\Enums\ContextTag;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\Unit;
use App\Models\VocabularyItem;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\PortugueseA1Seeder;
use Database\Seeders\SpanishA1Seeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
});

it('seeds a representative slice of Portuguese A1 units with vocabulary and grammar', function () {
    $this->seed(PortugueseA1Seeder::class);

    expect(Unit::query()->count())->toBeGreaterThanOrEqual(6)
        ->and(VocabularyItem::query()->count())->toBeGreaterThan(0)
        ->and(GrammarPoint::query()->count())->toBeGreaterThan(0);
});

it('includes at least one travel-tagged A1 unit', function () {
    $this->seed(PortugueseA1Seeder::class);

    $portuguese = Language::query()->where('code', 'pt')->sole();

    $travelUnits = Unit::query()
        ->where('language_id', $portuguese->id)
        ->where('cefr_level', CefrLevel::A1)
        ->where('context_tag', ContextTag::Travel)
        ->count();

    expect($travelUnits)->toBeGreaterThan(0);
});

it('scopes every seeded unit, vocabulary item, and grammar point to Portuguese', function () {
    $this->seed(PortugueseA1Seeder::class);

    $portuguese = Language::query()->where('code', 'pt')->sole();

    expect(Unit::query()->where('language_id', '!=', $portuguese->id)->exists())->toBeFalse()
        ->and(VocabularyItem::query()->where('language_id', '!=', $portuguese->id)->exists())->toBeFalse()
        ->and(GrammarPoint::query()->where('language_id', '!=', $portuguese->id)->exists())->toBeFalse();
});

it('is idempotent when run twice', function () {
    $this->seed(PortugueseA1Seeder::class);
    $unitCountAfterFirstRun = Unit::query()->count();
    $vocabCountAfterFirstRun = VocabularyItem::query()->count();

    $this->seed(PortugueseA1Seeder::class);

    expect(Unit::query()->count())->toBe($unitCountAfterFirstRun)
        ->and(VocabularyItem::query()->count())->toBe($vocabCountAfterFirstRun);
});

it('updates an existing unit by slug rather than duplicating it when the title has drifted', function () {
    $portuguese = Language::query()->where('code', 'pt')->sole();

    $stale = Unit::factory()->create([
        'language_id' => $portuguese->id,
        'slug' => 'greetings-and-introductions',
        'title' => 'Old stale title from a previous edit',
    ]);

    $this->seed(PortugueseA1Seeder::class);

    expect(Unit::query()->where('language_id', $portuguese->id)->where('slug', 'greetings-and-introductions')->count())->toBe(1)
        ->and($stale->fresh()->title)->toBe('Greetings and introductions');
});

it('populates contrast_note for at least one Portuguese unit and vocabulary item, but leaves Spanish content untouched', function () {
    $this->seed(SpanishA1Seeder::class);
    $this->seed(PortugueseA1Seeder::class);

    $spanish = Language::query()->where('code', 'es')->sole();
    $portuguese = Language::query()->where('code', 'pt')->sole();

    expect(Unit::query()->where('language_id', $portuguese->id)->whereNotNull('contrast_note')->exists())->toBeTrue()
        ->and(VocabularyItem::query()->where('language_id', $portuguese->id)->whereNotNull('contrast_note')->exists())->toBeTrue()
        ->and(Unit::query()->where('language_id', $spanish->id)->whereNotNull('contrast_note')->exists())->toBeFalse()
        ->and(VocabularyItem::query()->where('language_id', $spanish->id)->whereNotNull('contrast_note')->exists())->toBeFalse();
});
