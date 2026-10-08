<?php

declare(strict_types=1);

use App\Actions\GradeReadingAttempt;
use App\Actions\Languages\UnlockLanguageForUser;
use App\Enums\CefrLevel;
use App\Enums\CefrSubLevel;
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

function setReadingLevel(User $user, Language $language, CefrLevel $level, ?CefrSubLevel $tier = null): void
{
    UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Reading,
        'cefr_level' => $level,
        'sub_level' => $tier,
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

it('lists the stories with the best score', function () {
    $story = readingPassage($this->spanish);
    ReadingAttempt::factory()->create(['user_id' => $this->user->id, 'reading_passage_id' => $story->id, 'score' => 40]);
    ReadingAttempt::factory()->create(['user_id' => $this->user->id, 'reading_passage_id' => $story->id, 'score' => 80]);

    $this->actingAs($this->user)
        ->get(route('reading.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('reading/Index')
            ->has('stories', 1)
            ->where('stories.0.id', $story->id)
            ->where('stories.0.questions', 4)
            ->where('stories.0.best', 80),
        );
});

it('serves a story without leaking the answer key', function () {
    $story = readingPassage($this->spanish);

    $this->actingAs($this->user)
        ->get(route('reading.show', $story))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('reading/Show')
            ->has('passage.questions', 4)
            ->has('passage.questions.0.prompt')
            ->has('passage.questions.0.options')
            ->missing('passage.questions.0.correct_answer'),
        );
});

it('only offers stories at or below the reading level', function () {
    setReadingLevel($this->user, $this->spanish, CefrLevel::A1, CefrSubLevel::A1_3);
    $story = readingPassage($this->spanish, CefrLevel::B2);

    $this->actingAs($this->user)
        ->get(route('reading.index'))
        ->assertInertia(fn ($page) => $page->has('stories', 0));

    $this->actingAs($this->user)->get(route('reading.show', $story))->assertNotFound();
});

it('offers a story once the reading level reaches it', function () {
    setReadingLevel($this->user, $this->spanish, CefrLevel::B2);
    $story = readingPassage($this->spanish, CefrLevel::B2);

    $this->actingAs($this->user)
        ->get(route('reading.index'))
        ->assertInertia(fn ($page) => $page->where('stories.0.id', $story->id));
});

it('never serves the other language deck', function () {
    $portuguese = Language::query()->where('code', 'pt')->sole();
    $story = readingPassage($portuguese);

    $this->actingAs($this->user)
        ->get(route('reading.index'))
        ->assertInertia(fn ($page) => $page->has('stories', 0));

    $this->actingAs($this->user)->get(route('reading.show', $story))->assertNotFound();
});

it('records a graded attempt', function () {
    $passage = readingPassage($this->spanish);

    $this->actingAs($this->user)
        ->postJson(route('reading.attempts.store', $passage), ['answers' => ['a', 'c', 'e', 'x']])
        ->assertOk()
        ->assertJsonPath('score', 75)
        ->assertJsonPath('correct', ['a', 'c', 'e', 'g']);

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
    setReadingLevel($this->user, $this->spanish, CefrLevel::A1, CefrSubLevel::A1_3);
    $passage = readingPassage($this->spanish);

    ReadingAttempt::factory()->count(9)->create([
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
    setReadingLevel($this->user, $this->spanish, CefrLevel::A1, CefrSubLevel::A1_3);
    $passage = readingPassage($this->spanish);

    ReadingAttempt::factory()->count(9)->create([
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

    setReadingLevel($this->user, $this->spanish, CefrLevel::A1, CefrSubLevel::A1_3);
    $passage = readingPassage($this->spanish);

    ReadingAttempt::factory()->count(9)->create([
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

it('gives a story its lines with the clip of each in its speaker voice, or none without clips', function () {
    $story = readingPassage($this->spanish);
    $story->forceFill(['segments' => [['speaker' => 'Ana', 'text' => 'Hola.'], ['speaker' => 'Luis', 'text' => 'Encantado.']]])->save();

    $this->actingAs($this->user)
        ->get(route('reading.show', $story))
        ->assertInertia(fn ($page) => $page
            ->has('passage.segments', 2)
            ->where('passage.segments.0.speaker', 'Ana')
            ->where('passage.segments.1.audioUrl', null),
        );
});
