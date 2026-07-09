<?php

use App\Actions\Languages\EvaluatePortugueseActivationEligibility;
use App\Actions\Languages\UnlockLanguageForUser;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\User;
use App\Models\UserSkillLevel;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->portuguese = Language::query()->where('code', 'pt')->sole();
    $this->user = User::factory()->create();
});

it('is ineligible when the user has no Spanish skill levels at all', function () {
    expect((new EvaluatePortugueseActivationEligibility)->handle($this->user))->toBeFalse();
});

it('is ineligible when the Spanish blended level is below A2', function () {
    foreach (Skill::cases() as $skill) {
        UserSkillLevel::factory()->create([
            'user_id' => $this->user->id,
            'language_id' => $this->spanish->id,
            'skill' => $skill,
            'cefr_level' => CefrLevel::A1,
        ]);
    }

    expect((new EvaluatePortugueseActivationEligibility)->handle($this->user))->toBeFalse();
});

it('is eligible when the Spanish blended level is A2 or above', function () {
    foreach (Skill::cases() as $skill) {
        UserSkillLevel::factory()->create([
            'user_id' => $this->user->id,
            'language_id' => $this->spanish->id,
            'skill' => $skill,
            'cefr_level' => CefrLevel::A2,
        ]);
    }

    expect((new EvaluatePortugueseActivationEligibility)->handle($this->user))->toBeTrue();
});

it('is ineligible once this user already has Portuguese unlocked, even well above A2', function () {
    foreach (Skill::cases() as $skill) {
        UserSkillLevel::factory()->create([
            'user_id' => $this->user->id,
            'language_id' => $this->spanish->id,
            'skill' => $skill,
            'cefr_level' => CefrLevel::B2,
        ]);
    }
    (new UnlockLanguageForUser)->handle($this->user, $this->portuguese);

    expect((new EvaluatePortugueseActivationEligibility)->handle($this->user))->toBeFalse();
});

it('stays eligible for this user even if a different user already unlocked Portuguese', function () {
    $otherUser = User::factory()->create();
    (new UnlockLanguageForUser)->handle($otherUser, $this->portuguese);

    foreach (Skill::cases() as $skill) {
        UserSkillLevel::factory()->create([
            'user_id' => $this->user->id,
            'language_id' => $this->spanish->id,
            'skill' => $skill,
            'cefr_level' => CefrLevel::A2,
        ]);
    }

    expect((new EvaluatePortugueseActivationEligibility)->handle($this->user))->toBeTrue();
});
