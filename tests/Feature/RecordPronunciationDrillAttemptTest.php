<?php

use App\Actions\RecordPronunciationDrillAttempt;
use App\Models\PronunciationDrillAttempt;
use App\Models\PronunciationDrillExercise;
use App\Models\User;

it('persists a graded attempt', function () {
    $user = User::factory()->create();
    $exercise = PronunciationDrillExercise::factory()->create([
        'word_a' => 'pão',
        'word_b' => 'pau',
        'target_word' => 'pão',
    ]);

    $attempt = (new RecordPronunciationDrillAttempt)->handle($user, $exercise, 'pão');

    expect($attempt)->toBeInstanceOf(PronunciationDrillAttempt::class)
        ->and($attempt->is_correct)->toBeTrue()
        ->and($attempt->score)->toBe(100.0)
        ->and($attempt->transcript_guess)->toBe('pão')
        ->and($attempt->user_id)->toBe($user->id)
        ->and($attempt->pronunciation_drill_exercise_id)->toBe($exercise->id);
});

it('persists an incorrect attempt too', function () {
    $user = User::factory()->create();
    $exercise = PronunciationDrillExercise::factory()->create([
        'word_a' => 'pão',
        'word_b' => 'pau',
        'target_word' => 'pão',
    ]);

    $attempt = (new RecordPronunciationDrillAttempt)->handle($user, $exercise, 'pau');

    expect($attempt->is_correct)->toBeFalse()
        ->and($attempt->score)->toBe(0.0);
});

it('allows multiple attempts at the same exercise', function () {
    $user = User::factory()->create();
    $exercise = PronunciationDrillExercise::factory()->create([
        'word_a' => 'pão',
        'word_b' => 'pau',
        'target_word' => 'pão',
    ]);
    $action = new RecordPronunciationDrillAttempt;

    $action->handle($user, $exercise, 'pau');
    $action->handle($user, $exercise, 'pão');

    expect(PronunciationDrillAttempt::query()->where('user_id', $user->id)->count())->toBe(2);
});
