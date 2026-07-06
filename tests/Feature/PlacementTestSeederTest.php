<?php

use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestItem;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\PlacementTestSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
});

it('seeds five items per skill for Spanish', function () {
    $this->seed(PlacementTestSeeder::class);

    $spanish = Language::query()->where('code', 'es')->sole();

    foreach (Skill::cases() as $skill) {
        expect(PlacementTestItem::query()->where('language_id', $spanish->id)->where('skill', $skill)->count())->toBe(5);
    }
});

it('gives every item a correct answer that is one of its own options', function () {
    $this->seed(PlacementTestSeeder::class);

    PlacementTestItem::query()->get()->each(function (PlacementTestItem $item) {
        expect($item->options)->toContain($item->correct_answer);
    });
});

it('is idempotent when run twice', function () {
    $this->seed(PlacementTestSeeder::class);
    $countAfterFirstRun = PlacementTestItem::query()->count();

    $this->seed(PlacementTestSeeder::class);

    expect(PlacementTestItem::query()->count())->toBe($countAfterFirstRun);
});
