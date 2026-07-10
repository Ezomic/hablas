<?php

use App\Actions\Placement\FinalizePlacementAttempt;
use App\Enums\Skill;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestResponse;
use App\Models\UserSkillLevel;

it('finalizes an attempt with no responses at the A1.3 starting tier', function () {
    $attempt = PlacementTestAttempt::factory()->create();

    $finalized = (new FinalizePlacementAttempt)->handle($attempt);

    expect($finalized->completed_at)->not->toBeNull()
        ->and($finalized->resulting_skill_levels[Skill::Reading->value])->toBe(['cefr_level' => 'A1', 'sub_level' => 'A1.3']);
});

it('settling at B1.2 produces a B1 UserSkillLevel and records the sub-level reached', function () {
    $attempt = PlacementTestAttempt::factory()->create();
    // A1.3 -> A2.1 -> A2.2 -> B1.1 -> B1.2 (4 correct answers).
    PlacementTestResponse::factory()->count(4)->create([
        'attempt_id' => $attempt->id,
        'skill' => Skill::Reading,
        'is_correct' => true,
    ]);

    $finalized = (new FinalizePlacementAttempt)->handle($attempt);

    expect($finalized->resulting_skill_levels[Skill::Reading->value])->toBe(['cefr_level' => 'B1', 'sub_level' => 'B1.2'])
        ->and(UserSkillLevel::query()->where('user_id', $attempt->user_id)->where('language_id', $attempt->language_id)->where('skill', Skill::Reading->value)->sole()->cefr_level->value)->toBe('B1');
});

it('writes a UserSkillLevel row for all four skills independently', function () {
    $attempt = PlacementTestAttempt::factory()->create();
    PlacementTestResponse::factory()->count(2)->create([
        'attempt_id' => $attempt->id,
        'skill' => Skill::Speaking,
        'is_correct' => true,
    ]);

    (new FinalizePlacementAttempt)->handle($attempt);

    $levels = UserSkillLevel::query()->where('user_id', $attempt->user_id)->where('language_id', $attempt->language_id)->get()->keyBy(fn ($level) => $level->skill->value);

    expect($levels)->toHaveCount(4)
        ->and($levels[Skill::Speaking->value]->cefr_level->value)->toBe('A2')
        ->and($levels[Skill::Reading->value]->cefr_level->value)->toBe('A1');
});
