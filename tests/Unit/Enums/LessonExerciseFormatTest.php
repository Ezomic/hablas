<?php

declare(strict_types=1);

use App\Enums\LessonExerciseFormat;

it('knows which formats are answered in the learned language', function (LessonExerciseFormat $format, bool $learned): void {
    expect($format->answersInLearnedLanguage())->toBe($learned);
})->with([
    [LessonExerciseFormat::TypeWord, true],
    [LessonExerciseFormat::ChooseWord, true],
    [LessonExerciseFormat::ListenType, true],
    [LessonExerciseFormat::SpeakAnswer, true],
    [LessonExerciseFormat::ChooseMeaning, false],
    [LessonExerciseFormat::ListenChoose, false],
    [LessonExerciseFormat::ReadPassage, false],
]);

it('keeps the learned-language format list in step with the frontend', function (): void {
    $source = (string) file_get_contents(__DIR__.'/../../../resources/js/lib/lessonPayload.ts');

    preg_match('/function answersInLearnedLanguage[^{]*\{\s*return \[(.*?)\]\.includes/s', $source, $block);
    preg_match_all("/'([a-z_]+)'/", $block[1] ?? '', $names);

    $php = collect(LessonExerciseFormat::cases())
        ->filter(fn (LessonExerciseFormat $format): bool => $format->answersInLearnedLanguage())
        ->map(fn (LessonExerciseFormat $format): string => $format->value)
        ->all();

    sort($php);
    $listed = $names[1];
    sort($listed);

    expect($listed)->toBe($php);
});
