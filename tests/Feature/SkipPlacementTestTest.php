<?php

use App\Actions\Placement\SkipPlacementTest;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestResponse;
use App\Models\User;
use App\Models\UserSkillLevel;

it('sets all four skills to A1 and marks the attempt completed', function () {
    $language = Language::factory()->create();
    $user = User::factory()->create();

    $attempt = (new SkipPlacementTest)->handle($user, $language);

    expect($attempt->completed_at)->not->toBeNull();

    foreach (Skill::cases() as $skill) {
        expect($attempt->resulting_skill_levels[$skill->value])->toBe(['cefr_level' => 'A1', 'sub_level' => 'A1.1']);

        $skillLevel = UserSkillLevel::query()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->where('skill', $skill)
            ->sole();

        expect($skillLevel->cefr_level)->toBe(CefrLevel::A1);
    }
});

it('finalizes an already in-progress attempt instead of creating a second one', function () {
    $language = Language::factory()->create();
    $user = User::factory()->create();
    $existing = PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'completed_at' => null,
    ]);
    PlacementTestResponse::factory()->create([
        'attempt_id' => $existing->id,
        'skill' => Skill::Reading,
        'is_correct' => true,
    ]);

    $attempt = (new SkipPlacementTest)->handle($user, $language);

    expect($attempt->id)->toBe($existing->id)
        ->and(PlacementTestAttempt::query()->where('user_id', $user->id)->count())->toBe(1);
});
