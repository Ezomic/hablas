<?php

use App\Actions\ComputeBlendedCefrLevel;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\User;
use App\Models\UserSkillLevel;

it('returns null when the user has no skill levels for the language', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    $blended = (new ComputeBlendedCefrLevel)->handle($user, $language);

    expect($blended)->toBeNull();
});

it('returns the lowest of the per-skill levels as the blended headline level', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Reading,
        'cefr_level' => CefrLevel::B1,
    ]);
    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Listening,
        'cefr_level' => CefrLevel::B1,
    ]);
    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Speaking,
        'cefr_level' => CefrLevel::A2,
    ]);
    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::B2,
    ]);

    $blended = (new ComputeBlendedCefrLevel)->handle($user, $language);

    expect($blended)->toBe(CefrLevel::A2);
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

    $blended = (new ComputeBlendedCefrLevel)->handle($user, $spanish);

    expect($blended)->toBe(CefrLevel::B2);
});
