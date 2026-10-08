<?php

declare(strict_types=1);

use App\Actions\Lessons\GradeLessonAnswer;
use App\Enums\LessonExerciseFormat;
use App\Lessons\PersonalizeExercise;
use App\Models\Lesson;
use App\Models\LessonExercise;
use App\Models\Unit;
use Tests\Fixtures\Lessons\LessonWorld;

function introExercise(): LessonExercise
{
    $unit = Unit::factory()->create(['language_id' => LessonWorld::spanish()->id]);

    return LessonExercise::factory()->create([
        'lesson_id' => Lesson::factory()->create(['unit_id' => $unit->id])->id,
        'format' => LessonExerciseFormat::WriteGuided,
        'payload' => [
            'prompt' => 'Buenos días. ¿Quién eres?',
            'required' => [
                ['forms' => ['soy', 'me', 'llamo'], 'target' => null],
                ['forms' => ['ana', 'pablo', '{name}'], 'target' => null],
            ],
        ],
    ])->load('lesson.unit.language');
}

it('fills the learner name into the answers that ask for it', function () {
    $exercise = introExercise();

    (new PersonalizeExercise)->handle($exercise, 'Luigi');

    expect($exercise->payload['required'][1]['forms'])->toBe(['ana', 'pablo', 'Luigi'])
        ->and($exercise->isDirty('payload'))->toBeFalse();
});

it('drops the placeholder when there is no name', function () {
    $exercise = introExercise();

    (new PersonalizeExercise)->handle($exercise, null);

    expect($exercise->payload['required'][1]['forms'])->toBe(['ana', 'pablo']);
});

it('accepts the learner name as the answer to who are you, and still refuses another one', function () {
    expect((new GradeLessonAnswer)->handle(introExercise(), ['text' => 'buenos días soy Luigi'], 'Luigi')->correct)->toBeTrue()
        ->and((new GradeLessonAnswer)->handle(introExercise(), ['text' => 'buenos días soy Luigi'], null)->correct)->toBeFalse()
        ->and((new GradeLessonAnswer)->handle(introExercise(), ['text' => 'buenos días soy Ana'], 'Luigi')->correct)->toBeTrue();
});
