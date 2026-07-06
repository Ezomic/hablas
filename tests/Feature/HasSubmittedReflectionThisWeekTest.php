<?php

use App\Actions\Reflections\HasSubmittedReflectionThisWeek;
use App\Models\Language;
use App\Models\User;
use App\Models\WeeklyReflection;
use Carbon\CarbonImmutable;

it('returns true when the user has a submitted reflection for the current week', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    WeeklyReflection::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'week_start_date' => CarbonImmutable::now()->startOfWeek(),
        'submitted_at' => now(),
    ]);

    expect((new HasSubmittedReflectionThisWeek)->handle($user, $language))->toBeTrue();
});

it('returns false when the reflection for this week has not been submitted yet', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    WeeklyReflection::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'week_start_date' => CarbonImmutable::now()->startOfWeek(),
        'submitted_at' => null,
    ]);

    expect((new HasSubmittedReflectionThisWeek)->handle($user, $language))->toBeFalse();
});

it('returns false when the submitted reflection is from a previous week', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    WeeklyReflection::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'week_start_date' => CarbonImmutable::now()->startOfWeek()->subWeek(),
        'submitted_at' => now()->subWeek(),
    ]);

    expect((new HasSubmittedReflectionThisWeek)->handle($user, $language))->toBeFalse();
});
