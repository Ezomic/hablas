<?php

use App\Actions\RecordWritingAttempt;
use App\Enums\WritingExerciseType;
use App\Models\User;
use App\Models\WritingAttempt;
use App\Models\WritingExercise;

it('persists a graded attempt', function () {
    $user = User::factory()->create();
    $exercise = WritingExercise::factory()->create([
        'type' => WritingExerciseType::FillInTemplate,
        'correct_answers' => ['soy'],
    ]);

    $attempt = (new RecordWritingAttempt)->handle($user, $exercise, 'soy');

    expect($attempt)->toBeInstanceOf(WritingAttempt::class)
        ->and($attempt->is_correct)->toBeTrue()
        ->and($attempt->response)->toBe('soy')
        ->and($attempt->user_id)->toBe($user->id)
        ->and($attempt->writing_exercise_id)->toBe($exercise->id);
});

it('allows multiple attempts at the same exercise', function () {
    $user = User::factory()->create();
    $exercise = WritingExercise::factory()->create([
        'type' => WritingExerciseType::FillInTemplate,
        'correct_answers' => ['soy'],
    ]);
    $action = new RecordWritingAttempt;

    $action->handle($user, $exercise, 'estoy');
    $action->handle($user, $exercise, 'soy');

    expect(WritingAttempt::query()->where('user_id', $user->id)->count())->toBe(2);
});
