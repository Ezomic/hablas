<?php

use App\Actions\RecordShadowingAttempt;
use App\Models\ShadowingAttempt;
use App\Models\ShadowingExercise;
use App\Models\User;

it('persists a scored attempt', function () {
    $user = User::factory()->create();
    $exercise = ShadowingExercise::factory()->create(['target_transcript' => 'Hola']);

    $attempt = (new RecordShadowingAttempt)->handle($user, $exercise, 'Hola');

    expect($attempt)->toBeInstanceOf(ShadowingAttempt::class)
        ->and($attempt->score)->toBe(100.0)
        ->and($attempt->transcript_guess)->toBe('Hola')
        ->and($attempt->user_id)->toBe($user->id)
        ->and($attempt->shadowing_exercise_id)->toBe($exercise->id);
});

it('allows multiple attempts at the same exercise', function () {
    $user = User::factory()->create();
    $exercise = ShadowingExercise::factory()->create(['target_transcript' => 'Hola']);
    $action = new RecordShadowingAttempt;

    $action->handle($user, $exercise, 'wrong');
    $action->handle($user, $exercise, 'Hola');

    expect(ShadowingAttempt::query()->where('user_id', $user->id)->count())->toBe(2);
});
