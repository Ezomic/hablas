<?php

declare(strict_types=1);

use App\Actions\Placement\FinalizePlacementAttempt;
use App\Enums\CefrLevel;
use App\Enums\CefrSubLevel;
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

it('records when it set each skill level, including a level it left unchanged', function () {
    $attempt = PlacementTestAttempt::factory()->create();
    $unchanged = UserSkillLevel::factory()->create([
        'user_id' => $attempt->user_id,
        'language_id' => $attempt->language_id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::A1,
        'level_set_at' => now()->subWeek(),
    ]);

    $this->travel(1)->minutes();

    (new FinalizePlacementAttempt)->handle($attempt);

    $setAt = UserSkillLevel::query()
        ->where('user_id', $attempt->user_id)
        ->where('language_id', $attempt->language_id)
        ->get()
        ->map(fn (UserSkillLevel $level): ?string => $level->level_set_at?->toDateTimeString());

    expect($setAt)->toHaveCount(4)
        ->each->toBe(now()->toDateTimeString())
        ->and($unchanged->fresh()?->cefr_level)->toBe(CefrLevel::A1);
});

it('writes only the skill of a one-skill attempt, and records only when that one was set', function () {
    $attempt = PlacementTestAttempt::factory()->create(['skill' => Skill::Listening]);
    $reading = UserSkillLevel::factory()->create([
        'user_id' => $attempt->user_id,
        'language_id' => $attempt->language_id,
        'skill' => Skill::Reading,
        'cefr_level' => CefrLevel::B1,
        'level_set_at' => '2026-09-01 10:00:00',
    ]);
    PlacementTestResponse::factory()->count(2)->create([
        'attempt_id' => $attempt->id,
        'skill' => Skill::Listening,
        'is_correct' => true,
    ]);

    $finalized = (new FinalizePlacementAttempt)->handle($attempt);

    $levels = UserSkillLevel::query()->where('user_id', $attempt->user_id)->where('language_id', $attempt->language_id)->get();

    expect($finalized->resulting_skill_levels)->toBe(['listening' => ['cefr_level' => 'A2', 'sub_level' => 'A2.2']])
        ->and($levels)->toHaveCount(2)
        ->and($levels->firstWhere('skill', Skill::Listening)?->cefr_level)->toBe(CefrLevel::A2)
        ->and($reading->fresh()?->cefr_level)->toBe(CefrLevel::B1)
        ->and($reading->fresh()?->level_set_at?->toDateTimeString())->toBe('2026-09-01 10:00:00');
});

it('stores the placed tier next to the level for every skill it writes', function () {
    $attempt = PlacementTestAttempt::factory()->create();
    PlacementTestResponse::factory()->count(4)->create([
        'attempt_id' => $attempt->id,
        'skill' => Skill::Reading,
        'is_correct' => true,
    ]);

    (new FinalizePlacementAttempt)->handle($attempt);

    $levels = UserSkillLevel::query()->where('user_id', $attempt->user_id)->get()->keyBy(fn (UserSkillLevel $level): string => $level->skill->value);

    expect($levels[Skill::Reading->value]->sub_level)->toBe(CefrSubLevel::B1_2)
        ->and($levels[Skill::Writing->value]->sub_level)->toBe(CefrSubLevel::A1_3);
});

it('replaces the tier of an existing row, and writes only the tier of a one-skill attempt', function () {
    $attempt = PlacementTestAttempt::factory()->create(['skill' => Skill::Listening]);
    $listening = UserSkillLevel::factory()->create([
        'user_id' => $attempt->user_id,
        'language_id' => $attempt->language_id,
        'skill' => Skill::Listening,
        'cefr_level' => CefrLevel::B1,
        'sub_level' => CefrSubLevel::B1_2,
    ]);
    $reading = UserSkillLevel::factory()->create([
        'user_id' => $attempt->user_id,
        'language_id' => $attempt->language_id,
        'skill' => Skill::Reading,
        'cefr_level' => CefrLevel::B1,
        'sub_level' => CefrSubLevel::B1_2,
    ]);
    PlacementTestResponse::factory()->count(2)->create([
        'attempt_id' => $attempt->id,
        'skill' => Skill::Listening,
        'is_correct' => true,
    ]);

    (new FinalizePlacementAttempt)->handle($attempt);

    expect($listening->fresh()?->sub_level)->toBe(CefrSubLevel::A2_2)
        ->and($listening->fresh()?->cefr_level)->toBe(CefrLevel::A2)
        ->and($reading->fresh()?->sub_level)->toBe(CefrSubLevel::B1_2);
});
