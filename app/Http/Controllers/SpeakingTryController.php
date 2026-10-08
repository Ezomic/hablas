<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Lessons\ScoreSpeakingTry;
use App\Concerns\InteractsWithCurrentUser;
use App\Http\Requests\StoreSpeakingTryRequest;
use App\Lessons\PersonalizeExercise;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Services\LearnerName;
use Illuminate\Http\JsonResponse;

final class SpeakingTryController extends Controller
{
    use InteractsWithCurrentUser;

    public function store(StoreSpeakingTryRequest $request, LessonRun $lessonRun, LessonExercise $lessonExercise, ScoreSpeakingTry $scoreSpeakingTry, LearnerName $learnerName, PersonalizeExercise $personalizeExercise): JsonResponse
    {
        abort_unless($lessonRun->user_id === $this->currentUser()->id, 404);
        abort_if($lessonRun->kind->isCheck(), 404);
        abort_unless($lessonExercise->format->isSpeaking(), 404);
        abort_unless(in_array($lessonExercise->id, $lessonRun->planExerciseIds(), true), 404);

        $lessonExercise->loadMissing('lesson.unit.language');
        $personalizeExercise->handle($lessonExercise, $learnerName->for($this->currentUser()));

        return response()->json($scoreSpeakingTry->handle($lessonExercise, $request->string('transcript')->toString()));
    }
}
