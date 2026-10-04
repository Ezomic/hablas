<?php

declare(strict_types=1);

use App\Actions\Lessons\PresentLessonRun;
use App\Actions\Lessons\StartLessonRun;
use App\Actions\Lessons\SummarizeLessonRun;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Models\GrammarPoint;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\VocabularyItem;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
});

it('summarises a lesson with its accuracy per type and the items that needed a second go', function () {
    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));
    $missed = false;
    $run = LessonWorld::play($this->user, $run, function (LessonExercise $exercise) use (&$missed): bool {
        if ($missed || $exercise->format->value !== 'type_word') {
            return false;
        }

        $missed = true;

        return true;
    });

    $summary = (new SummarizeLessonRun)->handle($run);

    expect($summary['accuracy'])->toHaveKeys(['choice', 'writing'])
        ->and($summary['accuracy']['choice'])->toBe(1.0)
        ->and($summary['accuracy']['writing'])->toBeLessThan(1.0)
        ->and($summary['retried'])->toHaveCount(1)
        ->and($summary['items'])->toBe([])
        ->and($summary['answers'])->toBe([])
        ->and($summary['unitCompleted'])->toBeFalse();
});

it('lists the mastered and missing items and every answer once a check is over', function () {
    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    $check = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::Check);
    $wrong = LessonExercise::query()->whereIn('id', $check->planExerciseIds())->where('key', 'like', 'check.%.type_word.el-hotel')->firstOrFail();

    foreach ($check->planExerciseIds() as $id) {
        $exercise = LessonExercise::query()->with('substitute')->findOrFail($id);

        if ($exercise->format->isSpeaking()) {
            LessonWorld::answer($this->user, $check, $exercise, ['skipped' => true, 'skip_reason' => 'unsupported', 'response' => null]);
            $exercise = $exercise->substitute ?? $exercise;
        }

        LessonWorld::answer($this->user, $check, $exercise, $exercise->id === $wrong->id ? ['response' => ['text' => 'zzz']] : []);
    }

    $summary = (new SummarizeLessonRun)->handle($check->fresh());
    $row = collect($summary['answers'])->first(fn (array $answer): bool => $answer['given'] === 'zzz');
    $items = collect($summary['items']);

    expect($row)->toBe(['prompt' => 'hotel', 'given' => 'zzz', 'expected' => 'el hotel', 'correct' => false, 'learnedLanguage' => true])
        ->and($items->firstWhere('term', 'el hotel'))->toBe(['term' => 'el hotel', 'translation' => 'hotel', 'mastered' => false])
        ->and($items->where('mastered', true))->not->toBeEmpty()
        ->and($summary['retried'])->toBe([]);
});

it('presents the summary and the lesson to play next only for a completed run', function () {
    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));

    $open = (app(PresentLessonRun::class))->handle($run);
    $done = (app(PresentLessonRun::class))->handle(LessonWorld::play($this->user, $run));

    expect($open['run']['summary'])->toBeNull()
        ->and($open['run']['next'])->toBeNull()
        ->and($done['run']['summary'])->toBeArray()
        ->and($done['run']['next'])->toMatchArray(['stage' => 'recall', 'state' => 'available'])
        ->and($done['unit'])->toBe(['id' => $this->unit->id, 'title' => 'Checking into a hotel']);
});

it('has nothing next when the next lesson waits for another day', function () {
    LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet)));
    LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Recall)));
    LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Sentences)));
    $fourth = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Task)));

    expect((app(PresentLessonRun::class))->handle($fourth)['run']['next'])->toMatchArray(['stage' => 'check', 'state' => 'opens_tomorrow']);
});

it('describes the items of a result, and skips a reference that points at nothing', function () {
    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::TestOut);
    $item = VocabularyItem::query()->where('unit_id', $this->unit->id)->firstOrFail();
    $point = GrammarPoint::query()->where('unit_id', $this->unit->id)->firstOrFail();
    $run->forceFill(['result' => [
        'mastered' => ['junk', ['type' => $item->getMorphClass(), 'id' => 999999], ['type' => $item->getMorphClass(), 'id' => $item->id]],
        'missing' => [['type' => $point->getMorphClass(), 'id' => $point->id], ['type' => $point->getMorphClass(), 'id' => 999999]],
        'enrolled' => 'many',
    ]])->save();

    expect((new SummarizeLessonRun)->handle($run)['items'])->toBe([
        ['term' => $item->term, 'translation' => $item->translation_en, 'mastered' => true],
        ['term' => $point->title, 'translation' => null, 'mastered' => false],
    ])->and((new SummarizeLessonRun)->handle($run)['cardsEnrolled'])->toBe(0);
});

it('shows an empty prompt and expected answer for an exercise that has none', function () {
    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::TestOut);
    $bare = LessonExercise::factory()->create(['lesson_id' => $run->lesson_id, 'format' => 'type_word', 'payload' => []]);
    LessonAnswer::factory()->create(['lesson_run_id' => $run->id, 'lesson_exercise_id' => $bare->id, 'response' => ['text' => 'x'], 'is_correct' => false]);
    LessonAnswer::factory()->create(['lesson_run_id' => $run->id, 'lesson_exercise_id' => LessonExercise::factory()->create(['lesson_id' => $run->lesson_id, 'format' => 'type_word', 'payload' => []])->id, 'response' => null, 'is_correct' => true]);

    expect((new SummarizeLessonRun)->handle($run)['answers'])->toBe([
        ['prompt' => '', 'given' => 'x', 'expected' => '', 'correct' => false, 'learnedLanguage' => true],
        ['prompt' => '', 'given' => '', 'expected' => '', 'correct' => true, 'learnedLanguage' => true],
    ]);
});

it('has nothing next once every lesson of the unit is done', function () {
    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    $check = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::Check));

    expect((app(PresentLessonRun::class))->handle($check)['run']['next'])->toBeNull();
});
