<?php

use App\Actions\GradeWritingAttempt;
use App\Enums\WritingExerciseType;
use App\Models\WritingExercise;

it('grades a fill-in-template exercise as correct for an exact match', function () {
    $exercise = WritingExercise::factory()->create([
        'type' => WritingExerciseType::FillInTemplate,
        'correct_answers' => ['soy'],
    ]);

    expect((new GradeWritingAttempt)->handle($exercise, 'soy'))->toBeTrue();
});

it('grades a fill-in-template exercise as incorrect for a wrong answer', function () {
    $exercise = WritingExercise::factory()->create([
        'type' => WritingExerciseType::FillInTemplate,
        'correct_answers' => ['soy'],
    ]);

    expect((new GradeWritingAttempt)->handle($exercise, 'estoy'))->toBeFalse();
});

it('accepts any of several correct_answers variants', function () {
    $exercise = WritingExercise::factory()->create([
        'type' => WritingExerciseType::SentenceTransformation,
        'correct_answers' => ['nosotros comemos a las dos', 'comemos a las dos'],
    ]);

    expect((new GradeWritingAttempt)->handle($exercise, 'Comemos a las dos'))->toBeTrue();
});

it('is case, whitespace, and accent insensitive for fill-in-template and sentence-transformation', function () {
    $exercise = WritingExercise::factory()->create([
        'type' => WritingExerciseType::FillInTemplate,
        'correct_answers' => ['está'],
    ]);

    expect((new GradeWritingAttempt)->handle($exercise, '  ESTA  '))->toBeTrue();
});

it('grades a guided-paragraph exercise as correct when all required stems are present', function () {
    $exercise = WritingExercise::factory()->create([
        'type' => WritingExerciseType::GuidedParagraph,
        'correct_answers' => ['levant', 'desayun', 'trabaj'],
    ]);

    $response = 'Me levanto a las siete, desayuno pan, y trabajo hasta las cinco.';

    expect((new GradeWritingAttempt)->handle($exercise, $response))->toBeTrue();
});

it('grades a guided-paragraph exercise as incorrect when a required stem is missing', function () {
    $exercise = WritingExercise::factory()->create([
        'type' => WritingExerciseType::GuidedParagraph,
        'correct_answers' => ['levant', 'desayun', 'trabaj'],
    ]);

    $response = 'Me levanto a las siete y desayuno pan.';

    expect((new GradeWritingAttempt)->handle($exercise, $response))->toBeFalse();
});
