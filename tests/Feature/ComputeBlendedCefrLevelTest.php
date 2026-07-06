<?php

use App\Actions\ComputeBlendedCefrLevel;
use App\Enums\CefrLevel;
use App\Models\UserSkillLevel;

it('returns null when given no skill levels', function () {
    $blended = (new ComputeBlendedCefrLevel)->handle(collect());

    expect($blended)->toBeNull();
});

it('returns the lowest of the per-skill levels as the blended headline level', function () {
    $skillLevels = collect([
        UserSkillLevel::factory()->make(['cefr_level' => CefrLevel::B1]),
        UserSkillLevel::factory()->make(['cefr_level' => CefrLevel::B1]),
        UserSkillLevel::factory()->make(['cefr_level' => CefrLevel::A2]),
        UserSkillLevel::factory()->make(['cefr_level' => CefrLevel::B2]),
    ]);

    $blended = (new ComputeBlendedCefrLevel)->handle($skillLevels);

    expect($blended)->toBe(CefrLevel::A2);
});
