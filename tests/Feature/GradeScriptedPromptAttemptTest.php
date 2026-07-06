<?php

use App\Actions\GradeScriptedPromptAttempt;
use App\Models\ScriptedPromptExercise;

it('scores 100 when all expected keywords are present', function () {
    $exercise = ScriptedPromptExercise::factory()->create([
        'expected_keywords' => ['llamo', 'soy'],
    ]);

    $score = (new GradeScriptedPromptAttempt)->handle($exercise, 'Me llamo Ana y soy de España.');

    expect($score)->toBe(100.0);
});

it('gives partial credit when only some keywords are present', function () {
    $exercise = ScriptedPromptExercise::factory()->create([
        'expected_keywords' => ['llamo', 'soy'],
    ]);

    $score = (new GradeScriptedPromptAttempt)->handle($exercise, 'Me llamo Ana.');

    expect($score)->toBe(50.0);
});

it('scores 0 when no keywords are present', function () {
    $exercise = ScriptedPromptExercise::factory()->create([
        'expected_keywords' => ['llamo', 'soy'],
    ]);

    $score = (new GradeScriptedPromptAttempt)->handle($exercise, 'No entiendo la pregunta.');

    expect($score)->toBe(0.0);
});

it('is accent- and case-insensitive but does not fold ñ to n', function () {
    $exercise = ScriptedPromptExercise::factory()->create([
        'expected_keywords' => ['está', 'año'],
    ]);

    $score = (new GradeScriptedPromptAttempt)->handle($exercise, 'ESTA muy bien, tengo un ano.');

    expect($score)->toBe(50.0);
});

it('fails closed when no expected keywords are configured', function () {
    $exercise = ScriptedPromptExercise::factory()->create([
        'expected_keywords' => [],
    ]);

    expect((new GradeScriptedPromptAttempt)->handle($exercise, 'Cualquier respuesta.'))->toBe(0.0);
});
