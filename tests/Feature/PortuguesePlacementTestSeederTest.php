<?php

use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestItem;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\PortuguesePlacementTestSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
});

it('seeds five items per skill for Portuguese', function () {
    $this->seed(PortuguesePlacementTestSeeder::class);

    $portuguese = Language::query()->where('code', 'pt')->sole();

    foreach (Skill::cases() as $skill) {
        expect(PlacementTestItem::query()->where('language_id', $portuguese->id)->where('skill', $skill)->count())->toBe(5);
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
