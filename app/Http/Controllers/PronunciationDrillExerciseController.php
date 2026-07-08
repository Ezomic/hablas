<?php

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\RecordPronunciationDrillAttempt;
use App\Actions\SelectExerciseForUser;
use App\Http\Requests\StorePronunciationDrillAttemptRequest;
use App\Models\PronunciationDrillExercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PronunciationDrillExerciseController extends Controller
{
    public function index(Request $request, GetCurrentLanguage $getCurrentLanguage, SelectExerciseForUser $selectExercise): Response
    {
        $language = $getCurrentLanguage->handle($request->user());

        if ($language === null) {
            return Inertia::render('pronunciation-drills/Index', ['exercise' => null]);
        }

        $exercise = $selectExercise->handle(
            PronunciationDrillExercise::query()->where('language_id', $language->id),
            $request->user(),
        );

        return Inertia::render('pronunciation-drills/Index', [
            'exercise' => $exercise === null ? null : [
                'id' => $exercise->id,
                'word_a' => $exercise->word_a,
                'word_a_translation_en' => $exercise->word_a_translation_en,
                'word_b' => $exercise->word_b,
                'word_b_translation_en' => $exercise->word_b_translation_en,
                'target_word' => $exercise->target_word,
                'audio_url' => $exercise->audio_url,
            ],
        ]);
    }

    public function store(
        StorePronunciationDrillAttemptRequest $request,
        PronunciationDrillExercise $pronunciationDrillExercise,
        RecordPronunciationDrillAttempt $recordPronunciationDrillAttempt,
    ): JsonResponse {
        $attempt = $recordPronunciationDrillAttempt->handle(
            $request->user(),
            $pronunciationDrillExercise,
            $request->validated('transcript_guess'),
        );

        return response()->json(['is_correct' => $attempt->is_correct, 'score' => $attempt->score]);
    }
}
