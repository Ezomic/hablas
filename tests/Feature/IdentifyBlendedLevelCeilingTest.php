<?php

use App\Actions\IdentifyBlendedLevelCeiling;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\UserSkillLevel;
use Illuminate\Support\Collection;

/** @param array<string, CefrLevel> $levels */
function skillLevelSet(array $levels): Collection
{
    return collect($levels)->map(fn (CefrLevel $level, string $skill): UserSkillLevel => UserSkillLevel::factory()->make([
        'skill' => Skill::from($skill),
        'cefr_level' => $level,
    ]))->values();
}

it('names a placement-only skill that pins the blended level below the others', function () {
    $ceiling = (new IdentifyBlendedLevelCeiling)->handle(skillLevelSet([
        'reading' => CefrLevel::A1,
        'listening' => CefrLevel::B1,
        'speaking' => CefrLevel::B1,
        'writing' => CefrLevel::B2,
    ]));

    expect($ceiling->all())->toBe([Skill::Reading]);
});

it('names every placement-only skill sharing the floor', function () {
    $ceiling = (new IdentifyBlendedLevelCeiling)->handle(skillLevelSet([
        'reading' => CefrLevel::A1,
        'listening' => CefrLevel::A1,
        'speaking' => CefrLevel::A2,
        'writing' => CefrLevel::B1,
    ]));

    expect($ceiling->all())->toBe([Skill::Reading, Skill::Listening]);
});

it('reports no ceiling when every skill is level', function () {
    $ceiling = (new IdentifyBlendedLevelCeiling)->handle(skillLevelSet([
        'reading' => CefrLevel::A2,
        'listening' => CefrLevel::A2,
        'speaking' => CefrLevel::A2,
        'writing' => CefrLevel::A2,
    ]));

    expect($ceiling)->toBeEmpty();
});

it('reports no ceiling when the floor is a skill practice can still move', function () {
    $ceiling = (new IdentifyBlendedLevelCeiling)->handle(skillLevelSet([
        'reading' => CefrLevel::B1,
        'listening' => CefrLevel::B1,
        'speaking' => CefrLevel::A1,
        'writing' => CefrLevel::B1,
    ]));

    expect($ceiling)->toBeEmpty();
});

it('reports no ceiling for an empty skill set', function () {
    expect((new IdentifyBlendedLevelCeiling)->handle(collect()))->toBeEmpty();
});
