<?php

declare(strict_types=1);

use App\Actions\Lessons\PresentLessonRun;
use App\Actions\Lessons\StartLessonRun;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Models\LessonExercise;
use App\Models\VocabularyItem;
use App\Services\TypingSupport;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
});

function typedWord(array $props): array
{
    return collect($props['plan'])->first(fn (array $entry): bool => $entry['format'] === 'type_word');
}

it('gives a typed word in a lesson some of its letters and drops the old hint', function () {
    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));

    $payload = typedWord(app(PresentLessonRun::class)->handle($run))['payload'];

    expect($payload['mask'])->toBeArray()
        ->and(count($payload['mask']))->toBeGreaterThan(2)
        ->and($payload)->not->toHaveKey('hint')
        ->and(in_array(null, $payload['mask'], true))->toBeTrue();
});

it('takes a letter away after each unaided right answer, and gives one back for a miss', function () {
    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));
    $exercise = LessonExercise::query()->findOrFail(typedWord(app(PresentLessonRun::class)->handle($run))['id']);
    $item = $exercise->targets->map(fn ($target) => $target->targetable)->first(fn ($target): bool => $target instanceof VocabularyItem);
    $support = new TypingSupport;
    $before = $support->revealed($this->user, $item);

    LessonWorld::answer($this->user, $run, $exercise);

    expect($support->revealed($this->user, $item))->toBe($before - 1);

    $second = LessonExercise::query()->where('key', 'recall.type_word.'.str_replace('meet.type_word.', '', $exercise->key))->first();

    expect($second)->not->toBeNull();

    LessonWorld::answer($this->user, $run, $exercise, ['step' => 'again', 'response' => ['text' => 'zzz']]);

    expect($support->revealed($this->user, $item))->toBeLessThanOrEqual($before);
});

it('gives no letters in a check', function () {
    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::Check);

    foreach (app(PresentLessonRun::class)->handle($run)['plan'] as $entry) {
        expect($entry['payload'])->not->toHaveKey('mask');
    }
});
