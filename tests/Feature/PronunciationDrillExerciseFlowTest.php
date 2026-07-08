<?php

use App\Models\Language;
use App\Models\PronunciationDrillAttempt;
use App\Models\PronunciationDrillExercise;
use App\Models\User;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->portuguese = Language::query()->where('code', 'pt')->sole();
    $this->portuguese->forceFill(['is_active' => true])->save();
});

it('renders the pronunciation-drills page with an exercise for the active language', function () {
    PronunciationDrillExercise::factory()->create([
        'language_id' => $this->portuguese->id,
        'word_a' => 'pão',
        'word_b' => 'pau',
        'target_word' => 'pão',
    ]);
    $user = User::factory()->create(['current_language_id' => $this->portuguese->id]);

    $this->actingAs($user)
        ->get(route('pronunciation-drills.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('pronunciation-drills/Index')
            ->where('exercise.target_word', 'pão'),
        );
});

it('prefers an exercise the user has not already attempted', function () {
    $attempted = PronunciationDrillExercise::factory()->create(['language_id' => $this->portuguese->id]);
    $fresh = PronunciationDrillExercise::factory()->create(['language_id' => $this->portuguese->id]);
    $user = User::factory()->create(['current_language_id' => $this->portuguese->id]);

    PronunciationDrillAttempt::factory()->create([
        'user_id' => $user->id,
        'pronunciation_drill_exercise_id' => $attempted->id,
    ]);

    $this->actingAs($user)
        ->get(route('pronunciation-drills.index'))
        ->assertInertia(fn ($page) => $page
            ->where('exercise.id', $fresh->id),
        );
});

it('renders a graceful empty state when no exercises exist', function () {
    $user = User::factory()->create(['current_language_id' => $this->portuguese->id]);

    $this->actingAs($user)
        ->get(route('pronunciation-drills.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('pronunciation-drills/Index')
            ->where('exercise', null),
        );
});

it('scores a submitted attempt and returns it as json', function () {
    $exercise = PronunciationDrillExercise::factory()->create([
        'language_id' => $this->portuguese->id,
        'word_a' => 'pão',
        'word_b' => 'pau',
        'target_word' => 'pão',
    ]);
    $user = User::factory()->create(['current_language_id' => $this->portuguese->id]);

    $response = $this->actingAs($user)
        ->postJson(route('pronunciation-drills.attempts.store', $exercise), ['transcript_guess' => 'pão']);

    $response->assertOk()->assertJson(['is_correct' => true, 'score' => 100.0]);

    expect(PronunciationDrillAttempt::query()->where('user_id', $user->id)->exists())->toBeTrue();
});

it('rejects an attempt with no transcript', function () {
    $exercise = PronunciationDrillExercise::factory()->create(['language_id' => $this->portuguese->id]);
    $user = User::factory()->create(['current_language_id' => $this->portuguese->id]);

    $this->actingAs($user)
        ->postJson(route('pronunciation-drills.attempts.store', $exercise), [])
        ->assertUnprocessable();
});
