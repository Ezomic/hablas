<?php

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\RecordScriptedPromptAttempt;
use App\Http\Requests\StoreScriptedPromptAttemptRequest;
use App\Models\ScriptedPromptExercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ScriptedPromptExerciseController extends Controller
{
    public function index(Request $request, GetCurrentLanguage $getCurrentLanguage): Response
    {
        $language = $getCurrentLanguage->handle($request->user());

        if ($language === null) {
            return Inertia::render('scripted-prompts/Index', ['exercise' => null]);
        }

        $exercise = ScriptedPromptExercise::query()
            ->where('language_id', $language->id)
            ->whereDoesntHave('attempts', fn ($query) => $query->where('user_id', $request->user()->id))
            ->inRandomOrder()
            ->first()
            ?? ScriptedPromptExercise::query()->where('language_id', $language->id)->inRandomOrder()->first();

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
