<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\LessonAnswer;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('lessons:flags')]
#[Description('List the answers a learner marked "My answer should count", with their exercise and the answer given')]
class ListFlaggedLessonAnswers extends Command
{
    public function handle(): int
    {
        $answers = LessonAnswer::query()->whereNotNull('flagged_at')->with(['lessonExercise.lesson.unit'])->orderBy('flagged_at')->get();

        $this->table(['Flagged', 'Unit', 'Exercise', 'Given', 'Expected'], $answers->map(function (LessonAnswer $answer): array {
            $exercise = $answer->lessonExercise;
            $accepted = $exercise?->payload['accepted'] ?? null;
            $expected = is_array($accepted) && is_array($accepted[0] ?? null) && is_string($accepted[0]['text'] ?? null) ? $accepted[0]['text'] : '';

            return [
                $answer->flagged_at?->toDateTimeString() ?? '',
                $exercise?->lesson?->unit->slug ?? '',
                $exercise->key ?? '',
                is_string($answer->response['text'] ?? null) ? $answer->response['text'] : '',
                $expected,
            ];
        })->all());

        return self::SUCCESS;
    }
}
