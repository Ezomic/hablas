<?php

declare(strict_types=1);

use App\Enums\LessonExerciseFormat;
use App\Enums\LessonStage;
use App\Lessons\SubstituteBuilder;

it('replaces a spoken question later in the course by a written answer to it, not a copy', function () {
    $substitute = (new SubstituteBuilder)->handle(LessonStage::Task, LessonExerciseFormat::SpeakAnswer, [
        'prompt' => 'Buenas tardes. ¿Cómo estás?',
        'english' => 'Good afternoon. How are you?',
        'slots' => [['estoy', 'bien'], ['bien', 'gracias', 'muy']],
        'model' => 'Estoy bien, gracias.',
    ], []);

    expect($substitute['format'])->toBe(LessonExerciseFormat::WriteGuided)
        ->and($substitute['payload'])->toMatchArray([
            'prompt' => 'Buenas tardes. ¿Cómo estás?',
            'english' => 'Good afternoon. How are you?',
            'chips' => ['estoy', 'bien'],
            'answers' => true,
        ])
        ->and($substitute['payload']['required'])->toBe([
            ['forms' => ['estoy', 'bien'], 'target' => null],
            ['forms' => ['bien', 'gracias', 'muy'], 'target' => null],
        ]);
});
