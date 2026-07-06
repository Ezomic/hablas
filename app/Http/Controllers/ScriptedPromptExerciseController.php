<?php

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\RecordScriptedPromptAttempt;
use App\Actions\SelectExerciseForUser;
use App\Http\Requests\StoreScriptedPromptAttemptRequest;
use App\Models\ScriptedPromptExercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ScriptedPromptExerciseController extends Controller
{
    public function index(Request $request, GetCurrentLanguage $getCurrentLanguage, SelectExerciseForUser $selectExercise): Response
    {
        $language = $getCurrentLanguage->handle($request->user());

        if ($language === null) {
            return Inertia::render('scripted-prompts/Index', ['exercise' => null]);
        }

        $exercise = $selectExercise->handle(
            ScriptedPromptExercise::query()->where('language_id', $language->id),
            $request->user(),
        );

        return Inertia::render('scripted-prompts/Index', [
            'exercise' => $exercise === null ? null : [
                'id' => $exercise->id,
                'prompt_text' => $exercise->prompt_text,
            ],
        ]);
    }

    public function store(
        StoreScriptedPromptAttemptRequest $request,
        ScriptedPromptExercise $scriptedPromptExercise,
        RecordScriptedPromptAttempt $recordScriptedPromptAttempt,
    ): JsonResponse {
        $attempt = $recordScriptedPromptAttempt->handle(
            $request->user(),
            $scriptedPromptExercise,
            $request->validated('transcript_guess'),
        );

        return response()->json(['score' => $attempt->score]);
    }
}
