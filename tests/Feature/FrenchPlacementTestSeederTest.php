<?php

declare(strict_types=1);

use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestItem;
use Database\Seeders\FrenchPlacementTestSeeder;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
});

it('seeds twenty items per skill for French, spanning all eight sub-level tiers', function () {
    $this->seed(FrenchPlacementTestSeeder::class);

    $french = Language::query()->where('code', 'fr')->sole();

    foreach (Skill::cases() as $skill) {
        expect(PlacementTestItem::query()->where('language_id', $french->id)->where('skill', $skill)->count())->toBe(20);
    }

    foreach (CefrSubLevel::cases() as $tier) {
        expect(PlacementTestItem::query()->where('language_id', $french->id)->where('cefr_sublevel_tag', $tier)->exists())->toBeTrue();
    }
});

it('seeds exactly three items per skill for each of the five tiers beyond A1', function () {
    $this->seed(FrenchPlacementTestSeeder::class);

    $french = Language::query()->where('code', 'fr')->sole();

    foreach ([CefrSubLevel::A2_1, CefrSubLevel::A2_2, CefrSubLevel::B1_1, CefrSubLevel::B1_2, CefrSubLevel::B2] as $tier) {
        foreach (Skill::cases() as $skill) {
            expect(PlacementTestItem::query()->where('language_id', $french->id)->where('cefr_sublevel_tag', $tier)->where('skill', $skill)->count())->toBe(3);
        }
    }
});

it('gives every item a correct answer that is one of its own options', function () {
    $this->seed(FrenchPlacementTestSeeder::class);

    $french = Language::query()->where('code', 'fr')->sole();

    PlacementTestItem::query()->where('language_id', $french->id)->get()->each(function (PlacementTestItem $item) {
        expect($item->options)->toContain($item->correct_answer);
    });
});

it('is idempotent when run twice', function () {
    $this->seed(FrenchPlacementTestSeeder::class);
    $countAfterFirstRun = PlacementTestItem::query()->count();

    $this->seed(FrenchPlacementTestSeeder::class);

    expect(PlacementTestItem::query()->count())->toBe($countAfterFirstRun);
});
