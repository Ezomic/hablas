<?php

use App\Actions\RecordScriptedPromptAttempt;
use App\Models\ScriptedPromptAttempt;
use App\Models\ScriptedPromptExercise;
use App\Models\Streak;
use App\Models\User;

it('persists a graded attempt', function () {
    $user = User::factory()->create();
    $exercise = ScriptedPromptExercise::factory()->create([
        'expected_keywords' => ['llamo'],
    ]);

    $attempt = (new RecordScriptedPromptAttempt)->handle($user, $exercise, 'Me llamo Ana.');

    expect($attempt)->toBeInstanceOf(ScriptedPromptAttempt::class)
        ->and($attempt->score)->toBe(100.0)
        ->and($attempt->transcript_guess)->toBe('Me llamo Ana.')
        ->and($attempt->user_id)->toBe($user->id)
        ->and($attempt->scripted_prompt_exercise_id)->toBe($exercise->id);
});

it('allows multiple attempts at the same exercise', function () {
    $user = User::factory()->create();
    $exercise = ScriptedPromptExercise::factory()->create(['expected_keywords' => ['llamo']]);
    $action = new RecordScriptedPromptAttempt;

    $action->handle($user, $exercise, 'No se.');
    $action->handle($user, $exercise, 'Me llamo Ana.');

    expect(ScriptedPromptAttempt::query()->where('user_id', $user->id)->count())->toBe(2);
});

it('records streak activity', function () {
    $user = User::factory()->create();
    $exercise = ScriptedPromptExercise::factory()->create();

    (new RecordScriptedPromptAttempt)->handle($user, $exercise, 'Una respuesta.');

    expect(Streak::query()->where('user_id', $user->id)->sole()->current_length)->toBe(1);
});
