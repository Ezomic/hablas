<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Lessons\PresentLessonProgress;
use App\Actions\Lessons\RecordLessonAnswer;
use App\Concerns\InteractsWithCurrentUser;
use App\Http\Requests\StoreLessonAnswerRequest;
use App\Models\LessonRun;
use Illuminate\Http\JsonResponse;

final class LessonAnswerController extends Controller
{
    use InteractsWithCurrentUser;

    public function store(StoreLessonAnswerRequest $request, LessonRun $lessonRun, string $step, RecordLessonAnswer $recordLessonAnswer, PresentLessonProgress $presentLessonProgress): JsonResponse
    {
        $result = $recordLessonAnswer->handle($this->currentUser(), $lessonRun, $step, $request->answer());
        $run = $presentLessonProgress->handle($result['run'], $result['completed']);
        $grade = $result['grade'];

        if ($grade === null) {
            return response()->json(['saved' => true, 'run' => $run, 'milestone' => $result['milestone']]);
        }

        return response()->json([
            'correct' => $grade->correct,
            'expected' => $grade->expected,
            'note' => $grade->note,
            'score' => $grade->score,
            'targets' => $grade->targets,
            'details' => $grade->details,
            'run' => $run,
            'milestone' => $result['milestone'],
        ]);
    }
}
