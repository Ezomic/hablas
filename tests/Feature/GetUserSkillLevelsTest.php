<?php

use App\Actions\GetUserSkillLevels;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\User;
use App\Models\UserSkillLevel;

it('returns the skill levels for the given user and language', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Reading,
        'cefr_level' => CefrLevel::B1,
    ]);

    $levels = (new GetUserSkillLevels)->handle($user, $language);

    expect($levels)->toHaveCount(1)
        ->and($levels->first()->skill)->toBe(Skill::Reading);
});

it('scopes skill levels to the given language', function () {
    $user = User::factory()->create();
    $spanish = Language::factory()->create();
    $portuguese = Language::factory()->create();

    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $spanish->id,
        'skill' => Skill::Reading,
        'cefr_level' => CefrLevel::B2,
    ]);
    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $portuguese->id,
        'skill' => Skill::Reading,
        'cefr_level' => CefrLevel::A1,
    ]);

    $levels = (new GetUserSkillLevels)->handle($user, $spanish);

    expect($levels)->toHaveCount(1)
        ->and($levels->first()->cefr_level)->toBe(CefrLevel::B2);
});

it('returns an empty collection when the user has no skill levels for the language', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    $levels = (new GetUserSkillLevels)->handle($user, $language);

    expect($levels)->toBeEmpty();
});
