<?php

use App\Actions\Languages\UnlockLanguageForUser;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\ShadowingAttempt;
use App\Models\ShadowingExercise;
use App\Models\User;
use App\Models\UserSkillLevel;
use App\Models\WritingAttempt;
use App\Models\WritingExercise;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->user = User::factory()->create(['current_language_id' => $this->spanish->id]);
    (new UnlockLanguageForUser)->handle($this->user, $this->spanish);
});

// ReassessSkillLevel bumps on a 20-attempt window at 80% success, so a level-up
// only happens on the attempt that completes the window.
function primeSpeakingLevelUp(User $user, Language $language): ShadowingExercise
{
    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Speaking,
        'cefr_level' => CefrLevel::A1,
    ]);

    $exercise = ShadowingExercise::factory()->create([
        'language_id' => $language->id,
        'target_transcript' => 'hola',
    ]);

    ShadowingAttempt::factory()->count(19)->create([
        'user_id' => $user->id,
        'shadowing_exercise_id' => $exercise->id,
        'score' => 100,
    ]);

    return $exercise;
}

it('carries a level-up back in the shadowing response body', function () {
    $exercise = primeSpeakingLevelUp($this->user, $this->spanish);

    $this->actingAs($this->user)
        ->postJson(route('shadowing.attempts.store', $exercise), ['transcript_guess' => 'hola'])
        ->assertOk()
        ->assertJsonPath('milestone.type', 'milestone')
        ->assertJsonPath('milestone.message', "You've reached A2 in Spanish!");
});

it('omits the milestone when the level does not move', function () {
    UserSkillLevel::factory()->create([
        'user_id' => $this->user->id,
        'language_id' => $this->spanish->id,
        'skill' => Skill::Speaking,
        'cefr_level' => CefrLevel::A1,
    ]);

    $exercise = ShadowingExercise::factory()->create([
        'language_id' => $this->spanish->id,
        'target_transcript' => 'hola',
    ]);

    $this->actingAs($this->user)
        ->postJson(route('shadowing.attempts.store', $exercise), ['transcript_guess' => 'hola'])
        ->assertOk()
        ->assertJsonPath('milestone', null);
});

it('carries a level-up back in the writing response body', function () {
    UserSkillLevel::factory()->create([
        'user_id' => $this->user->id,
        'language_id' => $this->spanish->id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::A1,
    ]);

    $exercise = WritingExercise::factory()->create([
        'language_id' => $this->spanish->id,
        'correct_answers' => ['soy ana'],
    ]);

    WritingAttempt::factory()->count(19)->create([
        'user_id' => $this->user->id,
        'writing_exercise_id' => $exercise->id,
        'is_correct' => true,
    ]);

    $this->actingAs($this->user)
        ->postJson(route('writing.attempts.store', $exercise), ['response' => 'soy ana'])
        ->assertOk()
        ->assertJsonPath('milestone.type', 'milestone')
        ->assertJsonPath('milestone.message', "You've reached A2 in Spanish!");
});
