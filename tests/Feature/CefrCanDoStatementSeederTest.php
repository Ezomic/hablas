<?php

use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\CefrCanDoStatement;
use Database\Seeders\CefrCanDoStatementSeeder;

it('seeds a statement for every cefr level and skill combination', function () {
    $this->seed(CefrCanDoStatementSeeder::class);

    foreach (CefrLevel::cases() as $level) {
        foreach (Skill::cases() as $skill) {
            expect(CefrCanDoStatement::query()->where('cefr_level', $level)->where('skill', $skill)->exists())->toBeTrue();
        }
    }
});

it('is idempotent when run twice', function () {
    $this->seed(CefrCanDoStatementSeeder::class);
    $countAfterFirstRun = CefrCanDoStatement::query()->count();

    $this->seed(CefrCanDoStatementSeeder::class);

    expect(CefrCanDoStatement::query()->count())->toBe($countAfterFirstRun);
});
