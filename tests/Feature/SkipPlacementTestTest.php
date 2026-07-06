<?php

use App\Actions\SkipPlacementTest;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\User;
use App\Models\UserSkillLevel;

it('sets all four skills to A1 and marks the attempt completed', function () {
    $language = Language::factory()->create();
    $user = User::factory()->create();

    $attempt = (new SkipPlacementTest)->handle($user, $language);

    expect($attempt->completed_at)->not->toBeNull();

    foreach (Skill::cases() as $skill) {
        expect($attempt->resulting_skill_levels[$skill->value])->toBe(CefrLevel::A1->value);

        $skillLevel = UserSkillLevel::query()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->where('skill', $skill)
            ->sole();

        expect($skillLevel->cefr_level)->toBe(CefrLevel::A1);
    }
});
