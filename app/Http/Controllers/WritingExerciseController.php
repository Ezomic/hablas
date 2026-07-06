<?php

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\RecordWritingAttempt;
use App\Actions\SelectExerciseForUser;
use App\Http\Requests\StoreWritingAttemptRequest;
use App\Models\WritingExercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WritingExerciseController extends Controller
{
    public function index(Request $request, GetCurrentLanguage $getCurrentLanguage, SelectExerciseForUser $selectExercise): Response
    {
        $language = $getCurrentLanguage->handle($request->user());

        if ($language === null) {
            return Inertia::render('writing/Index', ['exercise' => null]);
        }

        $exercise = $selectExercise->handle(
            WritingExercise::query()->where('language_id', $language->id),
            $request->user(),
        );

        return Inertia::render('writing/Index', [
            'exercise' => $exercise === null ? null : [
                'id' => $exercise->id,
                'type' => $exercise->type->value,
                'prompt' => $exercise->prompt,
                'template' => $exercise->template,
            ],
        ]);
    }

    public function store(
        StoreWritingAttemptRequest $request,
        WritingExercise $writingExercise,
        RecordWritingAttempt $recordWritingAttempt,
    ): JsonResponse {
        $attempt = $recordWritingAttempt->handle(
            $request->user(),
            $writingExercise,
            $request->validated('response'),
        );

        return response()->json(['is_correct' => $attempt->is_correct]);
    }
}
