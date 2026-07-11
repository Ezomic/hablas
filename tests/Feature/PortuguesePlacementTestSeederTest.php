<?php

use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestItem;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\PortuguesePlacementTestSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
});

it('seeds twenty items per skill for Portuguese, spanning all eight sub-level tiers', function () {
    $this->seed(PortuguesePlacementTestSeeder::class);

    $portuguese = Language::query()->where('code', 'pt')->sole();

    foreach (Skill::cases() as $skill) {
        expect(PlacementTestItem::query()->where('language_id', $portuguese->id)->where('skill', $skill)->count())->toBe(20);
    }

    foreach (CefrSubLevel::cases() as $tier) {
        expect(PlacementTestItem::query()->where('language_id', $portuguese->id)->where('cefr_sublevel_tag', $tier)->exists())->toBeTrue();
    }
});

it('seeds exactly three items per skill for each of the five tiers beyond A1', function () {
    $this->seed(PortuguesePlacementTestSeeder::class);

    $portuguese = Language::query()->where('code', 'pt')->sole();

    foreach ([CefrSubLevel::A2_1, CefrSubLevel::A2_2, CefrSubLevel::B1_1, CefrSubLevel::B1_2, CefrSubLevel::B2] as $tier) {
        foreach (Skill::cases() as $skill) {
            expect(PlacementTestItem::query()->where('language_id', $portuguese->id)->where('cefr_sublevel_tag', $tier)->where('skill', $skill)->count())->toBe(3);
        }
    }
});

it('gives every item a correct answer that is one of its own options', function () {
    $this->seed(PortuguesePlacementTestSeeder::class);

    $portuguese = Language::query()->where('code', 'pt')->sole();

    PlacementTestItem::query()->where('language_id', $portuguese->id)->get()->each(function (PlacementTestItem $item) {
        expect($item->options)->toContain($item->correct_answer);
    });
});

it('is idempotent when run twice', function () {
    $this->seed(PortuguesePlacementTestSeeder::class);
    $countAfterFirstRun = PlacementTestItem::query()->count();

    $this->seed(PortuguesePlacementTestSeeder::class);

    expect(PlacementTestItem::query()->count())->toBe($countAfterFirstRun);
});
