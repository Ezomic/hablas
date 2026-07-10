<?php

use App\Actions\Placement\DeriveCurrentPlacementTier;
use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestResponse;

it('starts at A1.3 with no responses', function () {
    $attempt = PlacementTestAttempt::factory()->create();

    $tier = (new DeriveCurrentPlacementTier)->handle($attempt, Skill::Reading);

    expect($tier)->toBe(CefrSubLevel::A1_3);
});

it('steps up a tier on a correct response', function () {
    $attempt = PlacementTestAttempt::factory()->create();
    PlacementTestResponse::factory()->create([
        'attempt_id' => $attempt->id,
        'skill' => Skill::Reading,
        'is_correct' => true,
    ]);

    $tier = (new DeriveCurrentPlacementTier)->handle($attempt, Skill::Reading);

    expect($tier)->toBe(CefrSubLevel::A2_1);
});

it('steps down a tier on an incorrect response', function () {
    $attempt = PlacementTestAttempt::factory()->create();
    PlacementTestResponse::factory()->create([
        'attempt_id' => $attempt->id,
        'skill' => Skill::Reading,
        'is_correct' => false,
    ]);

    $tier = (new DeriveCurrentPlacementTier)->handle($attempt, Skill::Reading);

    expect($tier)->toBe(CefrSubLevel::A1_2);
});

it('clamps at the B2 ceiling', function () {
    $attempt = PlacementTestAttempt::factory()->create();
    for ($i = 0; $i < 10; $i++) {
        PlacementTestResponse::factory()->create([
            'attempt_id' => $attempt->id,
            'skill' => Skill::Reading,
            'is_correct' => true,
        ]);
    }

    $tier = (new DeriveCurrentPlacementTier)->handle($attempt, Skill::Reading);

    expect($tier)->toBe(CefrSubLevel::B2);
});

it('clamps at the A1.1 floor', function () {
    $attempt = PlacementTestAttempt::factory()->create();
    for ($i = 0; $i < 10; $i++) {
        PlacementTestResponse::factory()->create([
            'attempt_id' => $attempt->id,
            'skill' => Skill::Reading,
            'is_correct' => false,
        ]);
    }

    $tier = (new DeriveCurrentPlacementTier)->handle($attempt, Skill::Reading);

    expect($tier)->toBe(CefrSubLevel::A1_1);
});

it('only considers responses for the given skill', function () {
    $attempt = PlacementTestAttempt::factory()->create();
    PlacementTestResponse::factory()->create([
        'attempt_id' => $attempt->id,
        'skill' => Skill::Listening,
        'is_correct' => true,
    ]);

    $tier = (new DeriveCurrentPlacementTier)->handle($attempt, Skill::Reading);

    expect($tier)->toBe(CefrSubLevel::A1_3);
});

it('replays responses in id order regardless of answered_at', function () {
    $attempt = PlacementTestAttempt::factory()->create();
    // answered_at is deliberately out of order with insertion order — id
    // order (insertion order) must be what's replayed, not answered_at.
    PlacementTestResponse::factory()->create([
        'attempt_id' => $attempt->id,
        'skill' => Skill::Reading,
        'is_correct' => true,
        'answered_at' => now()->addMinute(),
    ]);
    PlacementTestResponse::factory()->create([
        'attempt_id' => $attempt->id,
        'skill' => Skill::Reading,
        'is_correct' => false,
        'answered_at' => now(),
    ]);

    $tier = (new DeriveCurrentPlacementTier)->handle($attempt, Skill::Reading);

    // Correct (A1.3 -> A2.1) then incorrect (A2.1 -> A1.3), per id order.
    expect($tier)->toBe(CefrSubLevel::A1_3);
});
