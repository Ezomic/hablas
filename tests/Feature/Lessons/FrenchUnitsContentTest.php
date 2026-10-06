<?php

declare(strict_types=1);

use App\Lessons\ReviewGate;
use App\Models\Language;
use App\Models\Unit;
use App\Services\UnitContentRegistry;
use Database\Seeders\ContentSeeder;
use Database\Seeders\FrenchA1Seeder;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(FrenchA1Seeder::class);
    $this->french = Language::query()->where('code', 'fr')->sole();
});

it('seeds the same eight units as the first eight Spanish units, topic for topic', function () {
    $this->seed(ContentSeeder::class);

    $slugs = Unit::query()->where('language_id', $this->french->id)->orderBy('sort_order')->pluck('slug')->all();
    $spanishSlugs = Unit::query()->whereHas('language', fn ($query) => $query->where('code', 'es'))->orderBy('sort_order')->pluck('slug')->all();

    expect($slugs)->toHaveCount(8)->and($slugs)->toBe(array_slice($spanishSlugs, 0, 8));
});

it('gives every unit ten words and one grammar point', function () {
    $this->seed(ContentSeeder::class);

    foreach (Unit::query()->where('language_id', $this->french->id)->withCount(['vocabularyItems', 'grammarPoints'])->get() as $unit) {
        expect($unit->vocabulary_items_count)->toBe(10, $unit->slug)
            ->and($unit->grammar_points_count)->toBe(1, $unit->slug);
    }
});

it('has word data for exactly the seeded vocabulary of each unit', function () {
    $this->seed(ContentSeeder::class);

    $contents = collect(app(UnitContentRegistry::class)->all())->filter(fn ($content) => $content->languageCode() === 'fr');

    expect($contents)->toHaveCount(8);

    foreach ($contents as $content) {
        $unit = Unit::query()->where('language_id', $this->french->id)->where('slug', $content->unitSlug())->sole();
        $terms = collect($content->words())->map(fn ($word) => $word->term)->sort()->values()->all();

        expect($terms)->toBe($unit->vocabularyItems()->pluck('term')->sort()->values()->all(), $content->unitSlug());
    }
});

it('is released: the independent reviews and the owner approval are recorded', function () {
    $this->seed(ContentSeeder::class);

    foreach (app(UnitContentRegistry::class)->all() as $content) {
        if ($content->languageCode() !== 'fr') {
            continue;
        }

        expect(ReviewGate::wordsReleased($content))->toBeTrue($content->unitSlug())
            ->and(ReviewGate::lessonsReleased($content))->toBeTrue($content->unitSlug());
    }
});
