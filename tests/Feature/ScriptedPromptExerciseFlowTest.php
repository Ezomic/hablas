<?php

use App\Models\Language;
use App\Models\ScriptedPromptAttempt;
use App\Models\ScriptedPromptExercise;
use App\Models\User;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
});

it('renders the scripted prompts page with an exercise for the active language', function () {
    ScriptedPromptExercise::factory()->create(['language_id' => $this->spanish->id, 'prompt_text' => '¿Cómo te llamas?']);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('scripted-prompts.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('scripted-prompts/Index')
            ->where('exercise.prompt_text', '¿Cómo te llamas?'),
        );
});

it('prefers an exercise the user has not already attempted', function () {
    $attempted = ScriptedPromptExercise::factory()->create(['language_id' => $this->spanish->id]);
    $fresh = ScriptedPromptExercise::factory()->create(['language_id' => $this->spanish->id]);
    $user = User::factory()->create();

    ScriptedPromptAttempt::factory()->create([
        'user_id' => $user->id,
        'scripted_prompt_exercise_id' => $attempted->id,
    ]);

    $this->actingAs($user)
        ->get(route('scripted-prompts.index'))
        ->assertInertia(fn ($page) => $page
            ->where('exercise.id', $fresh->id),
        );
});

it('renders a graceful empty state when no exercises exist', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('scripted-prompts.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('scripted-prompts/Index')
            ->where('exercise', null),
        );
});

it('scores a submitted attempt and returns it as json', function () {
    $exercise = ScriptedPromptExercise::factory()->create([
        'language_id' => $this->spanish->id,
        'expected_keywords' => ['llamo'],
    ]);
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('scripted-prompts.attempts.store', $exercise), ['transcript_guess' => 'Me llamo Ana.']);

    $response->assertOk()->assertJson(['score' => 100.0]);

    expect(ScriptedPromptAttempt::query()->where('user_id', $user->id)->exists())->toBeTrue();
});

it('rejects an attempt with no transcript', function () {
    $exercise = ScriptedPromptExercise::factory()->create(['language_id' => $this->spanish->id]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('scripted-prompts.attempts.store', $exercise), [])
        ->assertUnprocessable();
});
