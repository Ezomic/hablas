<?php

declare(strict_types=1);

use App\Actions\Lessons\PresentLessonRun;
use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
});

function planOf(object $test, LessonRunKind $kind, LessonStage $stage, array $payloads): array
{
    $lesson = LessonWorld::lesson($test->unit, $stage);
    $exercises = array_map(fn (array $row): LessonExercise => LessonExercise::factory()->create(['lesson_id' => $lesson->id, 'format' => $row[0], 'payload' => $row[1]]), $payloads);
    $run = LessonRun::factory()->create([
        'user_id' => $test->user->id,
        'lesson_id' => $lesson->id,
        'kind' => $kind,
        'plan' => array_map(fn (LessonExercise $exercise): array => ['id' => $exercise->id, 'origin' => 'lesson'], $exercises),
    ]);

    return app(PresentLessonRun::class)->handle($run)['plan'];
}

function gap(string $answer): array
{
    return [Format::TypeGap, ['prompt' => 'x ___', 'english' => 'x', 'accepted' => [['text' => $answer, 'spans' => []]]]];
}

function choice(string $answer, array $options): array
{
    return [Format::ChooseGap, ['prompt' => 'x ___', 'options' => $options, 'answer' => $answer]];
}

it('gives a typed gap its answer to type when no earlier exercise has shown that form', function () {
    $plan = planOf($this, LessonRunKind::Lesson, LessonStage::Sentences, [
        choice('somos', ['somos', 'son', 'soy']),
        gap('son'),
    ]);

    expect($plan[0]['payload'])->not->toHaveKey('introduce')
        ->and($plan[1]['payload']['introduce'])->toBe('son');
});

it('does not count a form that was only an option as shown', function () {
    $plan = planOf($this, LessonRunKind::Lesson, LessonStage::Sentences, [
        choice('somos', ['somos', 'son']),
        gap('son'),
    ]);

    expect($plan[1]['payload']['introduce'])->toBe('son');
});

it('asks for a form like any other once a choice or a build has shown it', function () {
    $plan = planOf($this, LessonRunKind::Lesson, LessonStage::Sentences, [
        choice('son', ['son', 'soy']),
        [Format::BuildSentence, ['prompt' => 'x', 'tiles' => ['somos', 'aquí'], 'accepted' => [['text' => 'somos aquí', 'spans' => []]]]],
        gap('son'),
        gap('somos'),
    ]);

    expect($plan[2]['payload'])->not->toHaveKey('introduce')
        ->and($plan[3]['payload'])->not->toHaveKey('introduce');
});

it('introduces a form once, and then it counts as shown', function () {
    $plan = planOf($this, LessonRunKind::Lesson, LessonStage::Sentences, [gap('eres'), gap('eres')]);

    expect($plan[0]['payload']['introduce'])->toBe('eres')
        ->and($plan[1]['payload'])->not->toHaveKey('introduce');
});

it('never shows the answer in a check', function () {
    $plan = planOf($this, LessonRunKind::Check, LessonStage::Check, [gap('son')]);

    expect($plan[0]['payload'])->not->toHaveKey('introduce')->not->toHaveKey('accepted');
});
