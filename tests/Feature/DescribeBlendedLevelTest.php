<?php

declare(strict_types=1);

use App\Actions\DescribeBlendedLevel;
use App\Enums\CefrLevel;
use App\Enums\CefrSubLevel;
use App\Models\UserSkillLevel;
use Illuminate\Support\Collection;

function skillLevelAt(CefrLevel $level, ?CefrSubLevel $tier): UserSkillLevel
{
    return UserSkillLevel::factory()->make(['cefr_level' => $level, 'sub_level' => $tier]);
}

it('describes the lowest tier across the skills', function () {
    $levels = new Collection([
        skillLevelAt(CefrLevel::A2, CefrSubLevel::A2_2),
        skillLevelAt(CefrLevel::A1, CefrSubLevel::A1_3),
        skillLevelAt(CefrLevel::B1, CefrSubLevel::B1_1),
    ]);

    expect((new DescribeBlendedLevel)->handle($levels))->toBe('A1.3');
});

it('reads a row with no stored tier as the first tier of its level', function () {
    $levels = new Collection([
        skillLevelAt(CefrLevel::A2, null),
        skillLevelAt(CefrLevel::A2, CefrSubLevel::A2_2),
    ]);

    expect((new DescribeBlendedLevel)->handle($levels))->toBe('A2.1');
});

it('gives the level alone where a level has no tiers', function () {
    expect((new DescribeBlendedLevel)->handle(new Collection([skillLevelAt(CefrLevel::C1, null)])))->toBe('C1');
});

it('describes nothing without skill levels', function () {
    expect((new DescribeBlendedLevel)->handle(new Collection))->toBeNull();
});
