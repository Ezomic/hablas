<?php

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\RecordShadowingAttempt;
use App\Actions\SelectExerciseForUser;
use App\Concerns\InteractsWithCurrentUser;
use App\Http\Requests\StoreShadowingAttemptRequest;
use App\Models\ShadowingExercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShadowingExerciseController extends Controller
{
    use InteractsWithCurrentUser;

    public function index(Request $request, GetCurrentLanguage $getCurrentLanguage, SelectExerciseForUser $selectExercise): Response
    {
        $language = $getCurrentLanguage->handle($this->currentUser());

        if ($language === null) {
            return Inertia::render('shadowing/Index', ['exercise' => null]);
        }

        $exercise = $selectExercise->handle(
            ShadowingExercise::query()->where('language_id', $language->id),
            $this->currentUser(),
        );

        return Inertia::render('shadowing/Index', [
            'exercise' => $exercise === null ? null : [
                'id' => $exercise->id,
                'target_transcript' => $exercise->target_transcript,
                'audio_url' => $exercise->audio_url,
            ],
        ]);
    }

    public function store(
        StoreShadowingAttemptRequest $request,
        ShadowingExercise $shadowingExercise,
        RecordShadowingAttempt $recordShadowingAttempt,
    ): JsonResponse {
        $attempt = $recordShadowingAttempt->handle(
            $this->currentUser(),
            $shadowingExercise,
            $request->validated('transcript_guess'),
        );

        return response()->json(['score' => $attempt->score]);
    }
}
