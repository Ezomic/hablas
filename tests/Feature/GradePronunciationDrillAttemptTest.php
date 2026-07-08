<?php

use App\Actions\GradePronunciationDrillAttempt;
use App\Models\PronunciationDrillExercise;

it('grades a correct nasal-marked target as correct', function () {
    $exercise = PronunciationDrillExercise::factory()->create([
        'word_a' => 'pão',
        'word_b' => 'pau',
        'target_word' => 'pão',
    ]);

    $grade = (new GradePronunciationDrillAttempt)->handle($exercise, 'pão');

    expect($grade)->toBe(['is_correct' => true, 'score' => 100.0]);
});

it('grades the other word of the pair as incorrect', function () {
    $exercise = PronunciationDrillExercise::factory()->create([
        'word_a' => 'pão',
        'word_b' => 'pau',
        'target_word' => 'pão',
    ]);

    $grade = (new GradePronunciationDrillAttempt)->handle($exercise, 'pau');

    expect($grade)->toBe(['is_correct' => false, 'score' => 0.0]);
});

it('is tolerant of the transcript being a full sentence rather than a bare word', function () {
    $exercise = PronunciationDrillExercise::factory()->create([
        'word_a' => 'mão',
        'word_b' => 'mau',
        'target_word' => 'mão',
    ]);

    $grade = (new GradePronunciationDrillAttempt)->handle($exercise, 'eu digo mão');

    expect($grade['is_correct'])->toBeTrue();
});

it('is case and punctuation insensitive but stays sensitive to the nasal mark', function () {
    $exercise = PronunciationDrillExercise::factory()->create([
        'word_a' => 'lã',
        'word_b' => 'lá',
        'target_word' => 'lã',
    ]);

    expect((new GradePronunciationDrillAttempt)->handle($exercise, 'LÃ!')['is_correct'])->toBeTrue()
        ->and((new GradePronunciationDrillAttempt)->handle($exercise, 'lá')['is_correct'])->toBeFalse();
});
