<?php

use App\Actions\SelectExerciseForUser;
use App\Models\Language;
use App\Models\ShadowingAttempt;
use App\Models\ShadowingExercise;
use App\Models\User;

it('prefers an exercise the user has not already attempted', function () {
    $language = Language::factory()->create();
    $attempted = ShadowingExercise::factory()->create(['language_id' => $language->id]);
    $fresh = ShadowingExercise::factory()->create(['language_id' => $language->id]);
    $user = User::factory()->create();

    ShadowingAttempt::factory()->create([
        'user_id' => $user->id,
        'shadowing_exercise_id' => $attempted->id,
    ]);

    $query = ShadowingExercise::query()->where('language_id', $language->id);
    $exercise = (new SelectExerciseForUser)->handle($query, $user);

    expect($exercise?->id)->toBe($fresh->id);
});

it('falls back to an already-attempted exercise once everything is attempted', function () {
    $language = Language::factory()->create();
    $exercise = ShadowingExercise::factory()->create(['language_id' => $language->id]);
    $user = User::factory()->create();

    ShadowingAttempt::factory()->create([
        'user_id' => $user->id,
        'shadowing_exercise_id' => $exercise->id,
    ]);

    $query = ShadowingExercise::query()->where('language_id', $language->id);
    $selected = (new SelectExerciseForUser)->handle($query, $user);

    expect($selected?->id)->toBe($exercise->id);
});

it('returns null when there are no exercises for the language', function () {
    $language = Language::factory()->create();
    $user = User::factory()->create();

    $query = ShadowingExercise::query()->where('language_id', $language->id);

    expect((new SelectExerciseForUser)->handle($query, $user))->toBeNull();
});

it('never selects an exercise from a different language', function () {
    $language = Language::factory()->create();
    $otherLanguage = Language::factory()->create();
    ShadowingExercise::factory()->create(['language_id' => $otherLanguage->id]);
    $user = User::factory()->create();

    $query = ShadowingExercise::query()->where('language_id', $language->id);

    expect((new SelectExerciseForUser)->handle($query, $user))->toBeNull();
});
