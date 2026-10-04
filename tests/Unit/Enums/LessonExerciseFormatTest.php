<?php

declare(strict_types=1);

use App\Enums\LessonExerciseFormat;

it('knows which formats are answered in the learned language', function (LessonExerciseFormat $format, bool $learned): void {
    expect($format->answersInLearnedLanguage())->toBe($learned);
})->with([
    [LessonExerciseFormat::TypeWord, true],
    [LessonExerciseFormat::ChooseWord, true],
    [LessonExerciseFormat::ListenType, true],
    [LessonExerciseFormat::ChooseMeaning, false],
    [LessonExerciseFormat::ListenChoose, false],
    [LessonExerciseFormat::ReadPassage, false],
]);
