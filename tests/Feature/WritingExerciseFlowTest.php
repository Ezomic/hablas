<?php

use App\Enums\WritingExerciseType;
use App\Models\Language;
use App\Models\User;
use App\Models\WritingAttempt;
use App\Models\WritingExercise;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
});

it('renders the writing page with an exercise for the active language', function () {
    WritingExercise::factory()->create(['language_id' => $this->spanish->id, 'prompt' => 'Test prompt']);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('writing.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('writing/Index')
            ->where('exercise.prompt', 'Test prompt'),
        );
});

it('prefers an exercise the user has not already attempted', function () {
    $attempted = WritingExercise::factory()->create(['language_id' => $this->spanish->id]);
    $fresh = WritingExercise::factory()->create(['language_id' => $this->spanish->id]);
    $user = User::factory()->create();

    WritingAttempt::factory()->create([
        'user_id' => $user->id,
        'writing_exercise_id' => $attempted->id,
    ]);

    $this->actingAs($user)
        ->get(route('writing.index'))
        ->assertInertia(fn ($page) => $page->where('exercise.id', $fresh->id));
});

it('renders a graceful empty state when no exercises exist', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('writing.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('writing/Index')
            ->where('exercise', null),
        );
});

it('grades a submitted attempt and returns it as json', function () {
    $exercise = WritingExercise::factory()->create([
        'language_id' => $this->spanish->id,
        'type' => WritingExerciseType::FillInTemplate,
        'correct_answers' => ['soy'],
    ]);
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('writing.attempts.store', $exercise), ['response' => 'soy']);

    $response->assertOk()->assertJson(['is_correct' => true]);

    expect(WritingAttempt::query()->where('user_id', $user->id)->exists())->toBeTrue();
});

it('rejects an attempt with no response', function () {
    $exercise = WritingExercise::factory()->create(['language_id' => $this->spanish->id]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('writing.attempts.store', $exercise), [])
        ->assertUnprocessable();
});
