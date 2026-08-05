<?php

use App\Actions\NotifyOnBlendedLevelIncrease;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\User;
use App\Models\UserSkillLevel;

it('returns a milestone payload when the blended level increases', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create(['name' => 'Spanish']);
    $skillLevel = UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::A1,
    ]);

    $milestone = (new NotifyOnBlendedLevelIncrease)->handle(
        $user,
        $language,
        fn () => $skillLevel->forceFill(['cefr_level' => CefrLevel::A2])->save(),
    );

    expect($milestone)->toBe([
        'type' => 'milestone',
        'message' => "You've reached A2 in Spanish!",
    ]);
});

it('returns a milestone payload when the user had no prior blended level', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create(['name' => 'Spanish']);

    $milestone = (new NotifyOnBlendedLevelIncrease)->handle(
        $user,
        $language,
        fn () => UserSkillLevel::factory()->create([
            'user_id' => $user->id,
            'language_id' => $language->id,
            'skill' => Skill::Writing,
            'cefr_level' => CefrLevel::A1,
        ]),
    );

    expect($milestone['type'] ?? null)->toBe('milestone');
});

it('returns nothing when the blended level is unchanged', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::A1,
    ]);

    expect((new NotifyOnBlendedLevelIncrease)->handle($user, $language, fn () => null))->toBeNull();
});

it('returns nothing when the level drops', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $skillLevel = UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::B1,
    ]);

    $milestone = (new NotifyOnBlendedLevelIncrease)->handle(
        $user,
        $language,
        fn () => $skillLevel->forceFill(['cefr_level' => CefrLevel::A1])->save(),
    );

    expect($milestone)->toBeNull();
});

it('returns nothing when the user still has no skill levels at all', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    expect((new NotifyOnBlendedLevelIncrease)->handle($user, $language, fn () => null))->toBeNull();
});
