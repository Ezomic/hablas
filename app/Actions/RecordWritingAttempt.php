<?php

namespace App\Actions;

use App\Models\User;
use App\Models\WritingAttempt;
use App\Models\WritingExercise;

class RecordWritingAttempt
{
    public function handle(User $user, WritingExercise $exercise, string $response): WritingAttempt
    {
        $isCorrect = (new GradeWritingAttempt)->handle($exercise, $response);

        return WritingAttempt::create([
            'user_id' => $user->id,
            'writing_exercise_id' => $exercise->id,
            'response' => $response,
            'is_correct' => $isCorrect,
            'submitted_at' => now(),
        ]);
    }
}
