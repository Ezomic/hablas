<?php

use App\Actions\GradeListeningAttempt;
use App\Actions\Languages\UnlockLanguageForUser;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Http\Controllers\ListeningExerciseController;
use App\Models\Language;
use App\Models\ListeningAttempt;
use App\Models\ListeningExercise;
use App\Models\User;
use App\Models\UserSkillLevel;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->user = User::factory()->create(['current_language_id' => $this->spanish->id]);
    (new UnlockLanguageForUser)->handle($this->user, $this->spanish);
});

function listeningClip(Language $language, CefrLevel $level = CefrLevel::A1): ListeningExercise
{
    return ListeningExercise::factory()->create([
        'language_id' => $language->id,
        'cefr_level' => $level,
        'transcript' => 'Hola, soy Carmen.',
        'questions' => [
            ['prompt' => 'Q1', 'options' => ['a', 'b'], 'correct_answer' => 'a'],
            ['prompt' => 'Q2', 'options' => ['c', 'd'], 'correct_answer' => 'c'],
            ['prompt' => 'Q3', 'options' => ['e', 'f'], 'correct_answer' => 'e'],
            ['prompt' => 'Q4', 'options' => ['g', 'h'], 'correct_answer' => 'g'],
        ],
    ]);
}

function setListeningLevel(User $user, Language $language, CefrLevel $level): void
{
    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Listening,
        'cefr_level' => $level,
    ]);
}

it('scores every question right at 100', function () {
    expect((new GradeListeningAttempt)->handle(listeningClip($this->spanish), ['a', 'c', 'e', 'g']))->toBe(100.0);
});

it('gives partial credit', function () {
    expect((new GradeListeningAttempt)->handle(listeningClip($this->spanish), ['a', 'c', 'x', 'x']))->toBe(50.0);
});

it('counts a skipped question wrong', function () {
    expect((new GradeListeningAttempt)->handle(listeningClip($this->spanish), ['a']))->toBe(25.0);
});

it('serves the transcript to be spoken but never the answer key', function () {
    listeningClip($this->spanish);

    $this->actingAs($this->user)
        ->get(route('listening.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('listening/Index')
            ->where('exercise.transcript', 'Hola, soy Carmen.')
            ->where('speechLocale', 'es-ES')
            ->where('maxReplays', ListeningExerciseController::MAX_REPLAYS)
            ->has('exercise.questions', 4)
            ->missing('exercise.questions.0.correct_answer'),
        );
});

it('only offers clips at or below the listening level', function () {
    setListeningLevel($this->user, $this->spanish, CefrLevel::A1);
    listeningClip($this->spanish, CefrLevel::C1);

    $this->actingAs($this->user)
        ->get(route('listening.index'))
        ->assertInertia(fn ($page) => $page->where('exercise', null));
});

it('offers a clip once the listening level reaches it', function () {
    setListeningLevel($this->user, $this->spanish, CefrLevel::C1);
    $clip = listeningClip($this->spanish, CefrLevel::C1);

    $this->actingAs($this->user)
        ->get(route('listening.index'))
        ->assertInertia(fn ($page) => $page->where('exercise.id', $clip->id));
});

it('never serves the other language deck', function () {
    $portuguese = Language::query()->where('code', 'pt')->sole();
    listeningClip($portuguese);

    $this->actingAs($this->user)
        ->get(route('listening.index'))
        ->assertInertia(fn ($page) => $page->where('exercise', null));
});

it('records a graded attempt with the replays used', function () {
    $clip = listeningClip($this->spanish);

    $this->actingAs($this->user)
        ->postJson(route('listening.attempts.store', $clip), [
            'answers' => ['a', 'c', 'e', 'x'],
            'replays_used' => 1,
        ])
        ->assertOk()
        ->assertJsonPath('score', 75);

    $attempt = ListeningAttempt::query()->sole();

    expect($attempt->score)->toBe(75.0)
        ->and($attempt->replays_used)->toBe(1);
});

it('clamps a replay count the client inflated', function () {
    $clip = listeningClip($this->spanish);

    $this->actingAs($this->user)
        ->postJson(route('listening.attempts.store', $clip), [
            'answers' => ['a', 'c', 'e', 'g'],
            'replays_used' => 99,
        ])
        ->assertOk();

    expect(ListeningAttempt::query()->sole()->replays_used)
        ->toBe(ListeningExerciseController::MAX_REPLAYS);
});

it('requires a replay count', function () {
    $clip = listeningClip($this->spanish);

    $this->actingAs($this->user)
        ->postJson(route('listening.attempts.store', $clip), ['answers' => ['a']])
        ->assertJsonValidationErrorFor('replays_used');
});

it('moves the listening level once the window is full of passes', function () {
    setListeningLevel($this->user, $this->spanish, CefrLevel::A1);
    $clip = listeningClip($this->spanish);

    ListeningAttempt::factory()->count(19)->create([
        'user_id' => $this->user->id,
        'listening_exercise_id' => $clip->id,
        'score' => 100,
    ]);

    $this->actingAs($this->user)
        ->postJson(route('listening.attempts.store', $clip), [
            'answers' => ['a', 'c', 'e', 'g'],
            'replays_used' => 0,
        ])
        ->assertOk();

    expect($this->user->skillLevels()->where('skill', Skill::Listening)->sole()->cefr_level)
        ->toBe(CefrLevel::A2);
});

it('leaves the listening level alone when the window is full of misses', function () {
    setListeningLevel($this->user, $this->spanish, CefrLevel::A1);
    $clip = listeningClip($this->spanish);

    ListeningAttempt::factory()->count(19)->create([
        'user_id' => $this->user->id,
        'listening_exercise_id' => $clip->id,
        'score' => 10,
    ]);

    $this->actingAs($this->user)
        ->postJson(route('listening.attempts.store', $clip), [
            'answers' => ['x', 'x', 'x', 'x'],
            'replays_used' => 0,
        ])
        ->assertOk();

    expect($this->user->skillLevels()->where('skill', Skill::Listening)->sole()->cefr_level)
        ->toBe(CefrLevel::A1);
});

it('lifts the blended level once listening catches up with the others', function () {
    foreach ([Skill::Reading, Skill::Speaking, Skill::Writing] as $skill) {
        UserSkillLevel::factory()->create([
            'user_id' => $this->user->id,
            'language_id' => $this->spanish->id,
            'skill' => $skill,
            'cefr_level' => CefrLevel::A2,
        ]);
    }

    setListeningLevel($this->user, $this->spanish, CefrLevel::A1);
    $clip = listeningClip($this->spanish);

    ListeningAttempt::factory()->count(19)->create([
        'user_id' => $this->user->id,
        'listening_exercise_id' => $clip->id,
        'score' => 100,
    ]);

    $this->actingAs($this->user)
        ->postJson(route('listening.attempts.store', $clip), [
            'answers' => ['a', 'c', 'e', 'g'],
            'replays_used' => 0,
        ])
        ->assertOk()
        ->assertJsonPath('milestone.message', "You've reached A2 in Spanish!");
});

it('requires authentication', function () {
    $this->get(route('listening.index'))->assertRedirect(route('login'));
});
