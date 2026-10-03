<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Lessons\PauseExerciseFamily;
use App\Concerns\InteractsWithCurrentUser;
use App\Enums\ExerciseFamily;
use App\Http\Requests\StoreExercisePauseRequest;
use Illuminate\Http\JsonResponse;

final class ExercisePauseController extends Controller
{
    use InteractsWithCurrentUser;

    public function store(StoreExercisePauseRequest $request, ExerciseFamily $family, PauseExerciseFamily $pauseExerciseFamily): JsonResponse
    {
        $until = $pauseExerciseFamily->handle($this->currentUser(), $family, $request->integer('minutes'));

        return response()->json(['family' => $family->value, 'until' => $until?->toIso8601String()]);
    }
}
