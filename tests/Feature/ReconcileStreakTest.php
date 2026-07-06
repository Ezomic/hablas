<?php

use App\Actions\Streaks\ReconcileStreak;
use App\Models\Streak;
use App\Models\User;
use Carbon\CarbonImmutable;

it('creates a streak on first reconciliation for a new user', function () {
    $user = User::factory()->create();

    $streak = (new ReconcileStreak)->handle($user);

    expect($streak)->toBeInstanceOf(Streak::class)
        ->and($streak->current_length)->toBe(0);
});

it('leaves an up-to-date streak untouched', function () {
    $user = User::factory()->create();
    Streak::factory()->create([
        'user_id' => $user->id,
        'current_length' => 4,
        'last_activity_date' => CarbonImmutable::yesterday(),
    ]);

    $streak = (new ReconcileStreak)->handle($user);

    expect($streak->current_length)->toBe(4)
        ->and($streak->last_activity_date->isYesterday())->toBeTrue();
});

it('consumes a freeze day and preserves the streak when a single day was missed', function () {
    $user = User::factory()->create();
    Streak::factory()->create([
        'user_id' => $user->id,
        'current_length' => 4,
        'freeze_days_remaining' => 1,
        'last_activity_date' => CarbonImmutable::today()->subDays(2),
    ]);

    $streak = (new ReconcileStreak)->handle($user);

    expect($streak->current_length)->toBe(4)
        ->and($streak->freeze_days_remaining)->toBe(0)
        ->and($streak->last_activity_date->isYesterday())->toBeTrue();
});

it('breaks the streak when a day was missed and no freeze days remain', function () {
    $user = User::factory()->create();
    Streak::factory()->create([
        'user_id' => $user->id,
        'current_length' => 4,
        'freeze_days_remaining' => 0,
        'last_activity_date' => CarbonImmutable::today()->subDays(2),
    ]);

    $streak = (new ReconcileStreak)->handle($user);

    expect($streak->current_length)->toBe(0);
});

it('is idempotent when called multiple times without new activity', function () {
    $user = User::factory()->create();
    Streak::factory()->create([
        'user_id' => $user->id,
        'current_length' => 4,
        'freeze_days_remaining' => 1,
        'last_activity_date' => CarbonImmutable::today()->subDays(2),
    ]);
    $action = new ReconcileStreak;

    $action->handle($user);
    $streak = $action->handle($user);

    expect($streak->current_length)->toBe(4)
        ->and($streak->freeze_days_remaining)->toBe(0);
});
