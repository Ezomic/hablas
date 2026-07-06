<?php

use App\Actions\GradeShadowingAttempt;
use App\Models\ShadowingExercise;

it('scores a perfect match at 100', function () {
    $exercise = ShadowingExercise::factory()->create(['target_transcript' => 'Hola, ¿cómo estás?']);

    $score = (new GradeShadowingAttempt)->handle($exercise, 'Hola, ¿cómo estás?');

    expect($score)->toBe(100.0);
});

it('scores a completely unrelated guess at 0', function () {
    $exercise = ShadowingExercise::factory()->create(['target_transcript' => 'Buenos días']);

    $score = (new GradeShadowingAttempt)->handle($exercise, 'zzz qqq');

    expect($score)->toBe(0.0);
});

it('gives partial credit for a partial word match', function () {
    $exercise = ShadowingExercise::factory()->create(['target_transcript' => 'Quisiera agua por favor']);

    $score = (new GradeShadowingAttempt)->handle($exercise, 'Quisiera agua');

    expect($score)->toBe(50.0);
});

it('is case, punctuation, and vowel-accent insensitive', function () {
    $exercise = ShadowingExercise::factory()->create(['target_transcript' => '¿Dónde está el hotel?']);

    $score = (new GradeShadowingAttempt)->handle($exercise, 'donde esta el hotel');

    expect($score)->toBe(100.0);
});

it('does not fold ñ to n, since they are distinct Spanish phonemes', function () {
    $exercise = ShadowingExercise::factory()->create(['target_transcript' => 'Hoy es mi año de graduación']);

    $score = (new GradeShadowingAttempt)->handle($exercise, 'Hoy es mi ano de graduacion');

    expect($score)->toBeLessThan(100.0);
});

it('does not double-count repeated words', function () {
    $exercise = ShadowingExercise::factory()->create(['target_transcript' => 'hola hola hola']);

    $score = (new GradeShadowingAttempt)->handle($exercise, 'hola');

    expect($score)->toBe(100.0);
});
