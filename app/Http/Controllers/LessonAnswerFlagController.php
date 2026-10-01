<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Lessons\FlagLessonAnswer;
use App\Actions\Lessons\PresentLessonProgress;
use App\Concerns\InteractsWithCurrentUser;
use App\Models\LessonRun;
use Illuminate\Http\JsonResponse;

final class LessonAnswerFlagController extends Controller
{
    use InteractsWithCurrentUser;

    public function store(LessonRun $lessonRun, string $step, FlagLessonAnswer $flagLessonAnswer, PresentLessonProgress $presentLessonProgress): JsonResponse
    {
        $result = $flagLessonAnswer->handle($this->currentUser(), $lessonRun, $step);

        return response()->json([
            'flagged' => true,
            'run' => $presentLessonProgress->handle($result['run'], $result['completed']),
        ]);
    }
}
