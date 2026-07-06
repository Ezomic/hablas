<?php

use App\Actions\NotifyOnBlendedLevelIncrease;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\User;
use App\Models\UserSkillLevel;
use Inertia\Support\SessionKey;

it('flashes a milestone toast when the blended level increases', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create(['name' => 'Spanish']);
    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::A2,
    ]);

    (new NotifyOnBlendedLevelIncrease)->handle($user, $language, CefrLevel::A1);

    expect(session(SessionKey::FLASH_DATA, [])['toast'] ?? null)->toBe([
        'type' => 'milestone',
        'message' => "You've reached A2 in Spanish!",
    ]);
});

it('flashes a milestone toast when the user had no prior blended level', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create(['name' => 'Spanish']);
    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::A1,
    ]);

    (new NotifyOnBlendedLevelIncrease)->handle($user, $language, null);

    expect((session(SessionKey::FLASH_DATA, [])['toast'] ?? null)['type'])->toBe('milestone');
});

it('does not flash a toast when the blended level is unchanged', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::A1,
    ]);

    (new NotifyOnBlendedLevelIncrease)->handle($user, $language, CefrLevel::A1);

    expect(session(SessionKey::FLASH_DATA, [])['toast'] ?? null)->toBeNull();
});

it('does not flash a toast when the level drops', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::A1,
    ]);

    (new NotifyOnBlendedLevelIncrease)->handle($user, $language, CefrLevel::B1);

    expect(session(SessionKey::FLASH_DATA, [])['toast'] ?? null)->toBeNull();
});

it('does not flash a toast when the user still has no skill levels at all', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    (new NotifyOnBlendedLevelIncrease)->handle($user, $language, null);

    expect(session(SessionKey::FLASH_DATA, [])['toast'] ?? null)->toBeNull();
});
