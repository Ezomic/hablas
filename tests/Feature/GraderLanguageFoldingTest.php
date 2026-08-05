<?php

use App\Actions\GradeScriptedPromptAttempt;
use App\Actions\GradeShadowingAttempt;
use App\Actions\GradeWritingAttempt;
use App\Enums\WritingExerciseType;
use App\Models\Language;
use App\Models\ScriptedPromptExercise;
use App\Models\ShadowingExercise;
use App\Models\WritingExercise;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->portuguese = Language::query()->where('code', 'pt')->sole();
});

// 'ê' folds in Portuguese but not in Spanish, so it tells the two rule sets
// apart: a Portuguese learner typing the unaccented form used to be graded
// against Spanish folding and marked wrong.
it('folds a portuguese circumflex when shadowing portuguese', function () {
    $exercise = ShadowingExercise::factory()->create([
        'language_id' => $this->portuguese->id,
        'target_transcript' => 'você tem três',
    ]);

    expect((new GradeShadowingAttempt)->handle($exercise, 'voce tem tres'))->toBe(100.0);
});

it('leaves a circumflex unfolded when shadowing spanish', function () {
    $exercise = ShadowingExercise::factory()->create([
        'language_id' => $this->spanish->id,
        'target_transcript' => 'você tem três',
    ]);

    expect((new GradeShadowingAttempt)->handle($exercise, 'voce tem tres'))->toBe(33.3);
});

it('folds a portuguese circumflex when grading portuguese writing', function () {
    $exercise = WritingExercise::factory()->create([
        'language_id' => $this->portuguese->id,
        'type' => WritingExerciseType::FillInTemplate,
        'correct_answers' => ['você está bem'],
    ]);

    expect((new GradeWritingAttempt)->handle($exercise, 'voce esta bem'))->toBeTrue();
});

it('leaves a circumflex unfolded when grading spanish writing', function () {
    $exercise = WritingExercise::factory()->create([
        'language_id' => $this->spanish->id,
        'type' => WritingExerciseType::FillInTemplate,
        'correct_answers' => ['você está bem'],
    ]);

    expect((new GradeWritingAttempt)->handle($exercise, 'voce esta bem'))->toBeFalse();
});

it('folds a portuguese circumflex when grading a portuguese scripted prompt', function () {
    $exercise = ScriptedPromptExercise::factory()->create([
        'language_id' => $this->portuguese->id,
        'expected_keywords' => ['você'],
    ]);

    expect((new GradeScriptedPromptAttempt)->handle($exercise, 'e voce'))->toBe(100.0);
});

it('keeps nasal marks phonemic in portuguese', function () {
    $exercise = ShadowingExercise::factory()->create([
        'language_id' => $this->portuguese->id,
        'target_transcript' => 'pão',
    ]);

    expect((new GradeShadowingAttempt)->handle($exercise, 'pao'))->toBe(0.0);
});

it('keeps the spanish tilde phonemic in spanish', function () {
    $exercise = ShadowingExercise::factory()->create([
        'language_id' => $this->spanish->id,
        'target_transcript' => 'año',
    ]);

    expect((new GradeShadowingAttempt)->handle($exercise, 'ano'))->toBe(0.0);
});

it('refuses to grade an exercise with no language', function () {
    $exercise = ShadowingExercise::factory()->create(['language_id' => $this->spanish->id]);
    $exercise->setRelation('language', null);

    expect(fn () => (new GradeShadowingAttempt)->handle($exercise, 'whatever'))
        ->toThrow(RuntimeException::class);
});
