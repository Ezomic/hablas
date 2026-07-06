<?php

use App\Actions\Streaks\RecordStreakActivity;
use App\Models\Streak;
use App\Models\User;
use Carbon\CarbonImmutable;

it('starts a streak at 1 on the first-ever activity', function () {
    $user = User::factory()->create();

    $streak = (new RecordStreakActivity)->handle($user);

    expect($streak->current_length)->toBe(1)
        ->and($streak->longest_length)->toBe(1)
        ->and($streak->last_activity_date->isToday())->toBeTrue();
});

it('does not double-count multiple activities on the same day', function () {
    $user = User::factory()->create();
    $action = new RecordStreakActivity;

    $action->handle($user);
    $streak = $action->handle($user);

    expect($streak->current_length)->toBe(1);
});

it('increments the streak for consecutive-day activity', function () {
    $user = User::factory()->create();
    Streak::factory()->create([
        'user_id' => $user->id,
        'current_length' => 3,
        'longest_length' => 3,
        'last_activity_date' => CarbonImmutable::yesterday(),
    ]);

    $streak = (new RecordStreakActivity)->handle($user);

    expect($streak->current_length)->toBe(4)
        ->and($streak->longest_length)->toBe(4);
});

it('preserves the streak across a missed day when a freeze day is available', function () {
    $user = User::factory()->create();
    Streak::factory()->create([
        'user_id' => $user->id,
        'current_length' => 5,
        'longest_length' => 5,
        'freeze_days_remaining' => 2,
        'last_activity_date' => CarbonImmutable::today()->subDays(2),
    ]);

    $streak = (new RecordStreakActivity)->handle($user);

    expect($streak->current_length)->toBe(6)
        ->and($streak->freeze_days_remaining)->toBe(1)
        ->and($streak->last_activity_date->isToday())->toBeTrue();
});

it('breaks the streak across a missed day when no freeze days remain', function () {
    $user = User::factory()->create();
    Streak::factory()->create([
        'user_id' => $user->id,
        'current_length' => 5,
        'longest_length' => 5,
        'freeze_days_remaining' => 0,
        'last_activity_date' => CarbonImmutable::today()->subDays(2),
    ]);

    $streak = (new RecordStreakActivity)->handle($user);

    expect($streak->current_length)->toBe(1)
        ->and($streak->longest_length)->toBe(5)
        ->and($streak->last_activity_date->isToday())->toBeTrue();
});

it('breaks the streak when too many days are missed for the available freeze days', function () {
    $user = User::factory()->create();
    Streak::factory()->create([
        'user_id' => $user->id,
        'current_length' => 5,
        'longest_length' => 5,
        'freeze_days_remaining' => 1,
        'last_activity_date' => CarbonImmutable::today()->subDays(3),
    ]);

    $streak = (new RecordStreakActivity)->handle($user);

    expect($streak->current_length)->toBe(1)
        ->and($streak->freeze_days_remaining)->toBe(1);
});
