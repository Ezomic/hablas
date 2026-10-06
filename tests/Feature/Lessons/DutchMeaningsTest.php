<?php

declare(strict_types=1);

use App\Actions\Lessons\GradeLessonAnswer;
use App\Enums\LessonExerciseFormat;
use App\Lessons\LocalizeExercise;
use App\Models\Lesson;
use App\Models\LessonExercise;
use App\Models\Unit;
use App\Models\VocabularyItem;

afterEach(fn () => app()->setLocale('en'));

function dutchUnitExercise(LessonExerciseFormat $format, array $payload): LessonExercise
{
    $unit = Unit::factory()->create();
    VocabularyItem::factory()->create(['unit_id' => $unit->id, 'language_id' => $unit->language_id, 'term' => 'la llave', 'translation_en' => 'key', 'translation_nl' => 'sleutel']);
    VocabularyItem::factory()->create(['unit_id' => $unit->id, 'language_id' => $unit->language_id, 'term' => 'la habitación', 'translation_en' => 'room', 'translation_nl' => null]);

    return LessonExercise::factory()->create([
        'lesson_id' => Lesson::factory()->create(['unit_id' => $unit->id])->id,
        'format' => $format,
        'payload' => $payload,
    ])->load('lesson');
}

it('keeps English meanings when the interface is English', function () {
    $exercise = dutchUnitExercise(LessonExerciseFormat::ChooseMeaning, ['prompt' => 'la llave', 'options' => ['key', 'room'], 'answer' => 'key']);

    (new LocalizeExercise)->handle($exercise);

    expect($exercise->payload['options'])->toBe(['key', 'room']);
});

it('shows Dutch meanings, leaves untranslated ones English, and never touches the Spanish', function () {
    app()->setLocale('nl');
    $exercise = dutchUnitExercise(LessonExerciseFormat::ChooseMeaning, ['prompt' => 'la llave', 'options' => ['key', 'room'], 'answer' => 'key']);

    (new LocalizeExercise)->handle($exercise);

    expect($exercise->payload)->toBe(['prompt' => 'la llave', 'options' => ['sleutel', 'room'], 'answer' => 'sleutel'])
        ->and($exercise->isDirty('payload'))->toBeFalse();
});

it('localizes teach cards, recall cues and matching pairs', function () {
    app()->setLocale('nl');

    $teach = dutchUnitExercise(LessonExerciseFormat::TeachWord, ['term' => 'la llave', 'translation' => 'key']);
    $type = dutchUnitExercise(LessonExerciseFormat::TypeWord, ['prompt' => 'key', 'english' => 'key', 'accepted' => ['la llave']]);
    $match = dutchUnitExercise(LessonExerciseFormat::MatchPairs, ['pairs' => [['target' => 'a', 'left' => 'la llave', 'right' => 'key']]]);

    foreach ([$teach, $type, $match] as $exercise) {
        (new LocalizeExercise)->handle($exercise);
    }

    expect($teach->payload['translation'])->toBe('sleutel')
        ->and($type->payload)->toMatchArray(['prompt' => 'sleutel', 'english' => 'sleutel', 'accepted' => ['la llave']])
        ->and($match->payload['pairs'][0]['right'])->toBe('sleutel');
});

it('grades the Dutch option the learner was shown', function () {
    app()->setLocale('nl');
    $exercise = dutchUnitExercise(LessonExerciseFormat::ChooseMeaning, ['prompt' => 'la llave', 'options' => ['key', 'room'], 'answer' => 'key']);

    expect((new GradeLessonAnswer)->handle($exercise, ['choice' => 'sleutel'])->correct)->toBeTrue();

    $english = dutchUnitExercise(LessonExerciseFormat::ChooseMeaning, ['prompt' => 'la llave', 'options' => ['key', 'room'], 'answer' => 'key']);
    app()->setLocale('en');

    expect((new GradeLessonAnswer)->handle($english, ['choice' => 'key'])->correct)->toBeTrue();
});

it('falls back to English for a word without a Dutch meaning', function () {
    $item = VocabularyItem::factory()->create(['translation_en' => 'room', 'translation_nl' => null]);
    app()->setLocale('nl');

    expect($item->meaning())->toBe('room');

    $item->translation_nl = 'kamer';

    expect($item->meaning())->toBe('kamer');
});
