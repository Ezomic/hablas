<?php

use App\Actions\GradeReadingAttempt;
use App\Actions\Languages\UnlockLanguageForUser;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\ReadingAttempt;
use App\Models\ReadingPassage;
use App\Models\User;
use App\Models\UserSkillLevel;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->user = User::factory()->create(['current_language_id' => $this->spanish->id]);
    (new UnlockLanguageForUser)->handle($this->user, $this->spanish);
});

function readingPassage(Language $language, CefrLevel $level = CefrLevel::A1): ReadingPassage
{
    return ReadingPassage::factory()->create([
        'language_id' => $language->id,
        'cefr_level' => $level,
        'questions' => [
            ['prompt' => 'Q1', 'options' => ['a', 'b'], 'correct_answer' => 'a'],
            ['prompt' => 'Q2', 'options' => ['c', 'd'], 'correct_answer' => 'c'],
            ['prompt' => 'Q3', 'options' => ['e', 'f'], 'correct_answer' => 'e'],
            ['prompt' => 'Q4', 'options' => ['g', 'h'], 'correct_answer' => 'g'],
        ],
    ]);
}

function setReadingLevel(User $user, Language $language, CefrLevel $level): void
{
    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Reading,
        'cefr_level' => $level,
    ]);
}

it('scores every question right at 100', function () {
    $passage = readingPassage($this->spanish);

    expect((new GradeReadingAttempt)->handle($passage, ['a', 'c', 'e', 'g']))->toBe(100.0);
});

it('gives partial credit', function () {
    $passage = readingPassage($this->spanish);

    expect((new GradeReadingAttempt)->handle($passage, ['a', 'c', 'x', 'x']))->toBe(50.0);
});

it('counts a skipped question wrong rather than shrinking the denominator', function () {
    $passage = readingPassage($this->spanish);

    expect((new GradeReadingAttempt)->handle($passage, ['a', 'c']))->toBe(50.0);
});

it('fails closed for a passage with no questions', function () {
    $passage = ReadingPassage::factory()->create(['language_id' => $this->spanish->id, 'questions' => []]);

    expect((new GradeReadingAttempt)->handle($passage, []))->toBe(0.0);
});

it('serves a passage without leaking the answer key', function () {
    readingPassage($this->spanish);

    $this->actingAs($this->user)
        ->get(route('reading.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('reading/Index')
            ->has('passage.questions', 4)
            ->has('passage.questions.0.prompt')
            ->has('passage.questions.0.options')
            ->missing('passage.questions.0.correct_answer'),
        );
});

it('only offers passages at or below the reading level', function () {
    setReadingLevel($this->user, $this->spanish, CefrLevel::A1);
    readingPassage($this->spanish, CefrLevel::B2);

    $this->actingAs($this->user)
        ->get(route('reading.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('passage', null));
});

it('offers a passage once the reading level reaches it', function () {
    setReadingLevel($this->user, $this->spanish, CefrLevel::B2);
    $passage = readingPassage($this->spanish, CefrLevel::B2);

    $this->actingAs($this->user)
        ->get(route('reading.index'))
        ->assertInertia(fn ($page) => $page->where('passage.id', $passage->id));
});

it('never serves the other language deck', function () {
    $portuguese = Language::query()->where('code', 'pt')->sole();
    readingPassage($portuguese);

    $this->actingAs($this->user)
        ->get(route('reading.index'))
        ->assertInertia(fn ($page) => $page->where('passage', null));
});

it('records a graded attempt', function () {
    $passage = readingPassage($this->spanish);

    $this->actingAs($this->user)
        ->postJson(route('reading.attempts.store', $passage), ['answers' => ['a', 'c', 'e', 'x']])
        ->assertOk()
        ->assertJsonPath('score', 75);

    $attempt = ReadingAttempt::query()->sole();

    expect($attempt->user_id)->toBe($this->user->id)
        ->and($attempt->score)->toBe(75.0)
        ->and($attempt->answers)->toBe(['a', 'c', 'e', 'x']);
});

it('rejects answers that are not a list', function () {
    $passage = readingPassage($this->spanish);

    $this->actingAs($this->user)
        ->postJson(route('reading.attempts.store', $passage), ['answers' => 'nope'])
        ->assertJsonValidationErrorFor('answers');
});

it('moves the reading level once the window is full of passes', function () {
    setReadingLevel($this->user, $this->spanish, CefrLevel::A1);
    $passage = readingPassage($this->spanish);

    ReadingAttempt::factory()->count(19)->create([
        'user_id' => $this->user->id,
        'reading_passage_id' => $passage->id,
        'score' => 100,
    ]);

    $this->actingAs($this->user)
        ->postJson(route('reading.attempts.store', $passage), ['answers' => ['a', 'c', 'e', 'g']])
        ->assertOk();

    expect($this->user->skillLevels()->where('skill', Skill::Reading)->sole()->cefr_level)
        ->toBe(CefrLevel::A2);
});

it('leaves the reading level alone when the window is full of misses', function () {
    setReadingLevel($this->user, $this->spanish, CefrLevel::A1);
    $passage = readingPassage($this->spanish);

    ReadingAttempt::factory()->count(19)->create([
        'user_id' => $this->user->id,
        'reading_passage_id' => $passage->id,
        'score' => 20,
    ]);

    $this->actingAs($this->user)
        ->postJson(route('reading.attempts.store', $passage), ['answers' => ['x', 'x', 'x', 'x']])
        ->assertOk();

    expect($this->user->skillLevels()->where('skill', Skill::Reading)->sole()->cefr_level)
        ->toBe(CefrLevel::A1);
});

it('lifts the blended level once reading catches up with the others', function () {
    foreach ([Skill::Listening, Skill::Speaking, Skill::Writing] as $skill) {
        UserSkillLevel::factory()->create([
            'user_id' => $this->user->id,
            'language_id' => $this->spanish->id,
            'skill' => $skill,
            'cefr_level' => CefrLevel::A2,
        ]);
    }

    setReadingLevel($this->user, $this->spanish, CefrLevel::A1);
    $passage = readingPassage($this->spanish);

    ReadingAttempt::factory()->count(19)->create([
        'user_id' => $this->user->id,
        'reading_passage_id' => $passage->id,
        'score' => 100,
    ]);

    $this->actingAs($this->user)
        ->postJson(route('reading.attempts.store', $passage), ['answers' => ['a', 'c', 'e', 'g']])
        ->assertOk()
        ->assertJsonPath('milestone.message', "You've reached A2 in Spanish!");
});

it('requires authentication', function () {
    $this->get(route('reading.index'))->assertRedirect(route('login'));
});
