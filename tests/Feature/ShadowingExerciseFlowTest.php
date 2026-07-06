<?php

use App\Models\Language;
use App\Models\ShadowingAttempt;
use App\Models\ShadowingExercise;
use App\Models\User;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
});

it('renders the shadowing page with an exercise for the active language', function () {
    ShadowingExercise::factory()->create(['language_id' => $this->spanish->id, 'target_transcript' => 'Hola']);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('shadowing.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('shadowing/Index')
            ->where('exercise.target_transcript', 'Hola'),
        );
});

it('prefers an exercise the user has not already attempted', function () {
    $attempted = ShadowingExercise::factory()->create(['language_id' => $this->spanish->id]);
    $fresh = ShadowingExercise::factory()->create(['language_id' => $this->spanish->id]);
    $user = User::factory()->create();

    ShadowingAttempt::factory()->create([
        'user_id' => $user->id,
        'shadowing_exercise_id' => $attempted->id,
    ]);

    $this->actingAs($user)
        ->get(route('shadowing.index'))
        ->assertInertia(fn ($page) => $page
            ->where('exercise.id', $fresh->id),
        );
});

it('renders a graceful empty state when no exercises exist', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('shadowing.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('shadowing/Index')
            ->where('exercise', null),
        );
});

it('scores a submitted attempt and returns it as json', function () {
    $exercise = ShadowingExercise::factory()->create(['language_id' => $this->spanish->id, 'target_transcript' => 'Hola']);
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('shadowing.attempts.store', $exercise), ['transcript_guess' => 'Hola']);

    $response->assertOk()->assertJson(['score' => 100.0]);

    expect(ShadowingAttempt::query()->where('user_id', $user->id)->exists())->toBeTrue();
});

it('rejects an attempt with no transcript', function () {
    $exercise = ShadowingExercise::factory()->create(['language_id' => $this->spanish->id]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('shadowing.attempts.store', $exercise), [])
        ->assertUnprocessable();
});
