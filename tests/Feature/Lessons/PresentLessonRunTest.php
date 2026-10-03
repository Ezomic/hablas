<?php

declare(strict_types=1);

use App\Actions\Lessons\BuildUnitLessons;
use App\Actions\Lessons\GetUnitLessonOverview;
use App\Actions\Lessons\PresentLessonRun;
use App\Actions\Lessons\StartLessonRun;
use App\Actions\Lessons\SyncUnitLessons;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use Illuminate\Support\Str;
use Tests\Fixtures\Lessons\HotelContent;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
});

function lessonWithSubstitute(object $test): array
{
    $run = (new StartLessonRun)->handle($test->user, LessonWorld::lesson($test->unit, LessonStage::Meet));

    return [$run, (app(PresentLessonRun::class))->handle($run)];
}

it('presents the plan with each exercise and its substitute, and the stage settings', function () {
    [$run, $props] = lessonWithSubstitute($this);
    $withSubstitute = collect($props['plan'])->first(fn (array $entry): bool => $entry['substitute'] !== null);

    expect($props['run'])->toMatchArray(['id' => $run->id, 'kind' => 'lesson', 'status' => 'in_progress', 'seed' => $run->seed, 'result' => null, 'summarySeen' => false])
        ->and($props['lesson'])->toMatchArray(['stage' => 'meet', 'title' => 'Meet the words', 'position' => 1])
        ->and($props['settings'])->toBe(['feedback' => true, 'hintsAreFree' => true, 'audioSpeed' => 0.75, 'replayLimit' => null, 'offersSlowerAudio' => true, 'speechLocale' => 'es-ES', 'pauses' => ['listening' => null, 'speaking' => null]])
        ->and($props['plan'])->toHaveCount(count($run->plan))
        ->and($withSubstitute['substitute']['format'])->not->toBe($withSubstitute['format'])
        ->and($props['answers'])->toBe([]);
});

it('keeps the plan order and the origin of each exercise', function () {
    [$run, $props] = lessonWithSubstitute($this);

    expect(array_column($props['plan'], 'id'))->toBe($run->planExerciseIds())
        ->and(array_unique(array_column($props['plan'], 'origin')))->toBe(['lesson']);
});

it('shows only accepted texts, not their spans', function () {
    [$run, $props] = lessonWithSubstitute($this);
    $typed = collect($props['plan'])->first(fn (array $entry): bool => $entry['format'] === 'type_word');

    expect($typed['payload']['accepted'][0])->toBeString();
});

it('lists the answers so far, and the stored result once completed', function () {
    [$run] = lessonWithSubstitute($this);
    $exercise = LessonExercise::query()->findOrFail($run->plan[0]['id']);
    LessonWorld::answer($this->user, $run, $exercise);

    $props = (app(PresentLessonRun::class))->handle($run->fresh());

    expect($props['answers'])->toHaveCount(1)
        ->and($props['answers'][0])->toMatchArray(['exerciseId' => $exercise->id, 'attempt' => 1, 'hinted' => false, 'skipped' => false, 'correct' => true, 'flagged' => false, 'settled' => true]);

    $done = LessonWorld::play($this->user, $run);
    $props = (app(PresentLessonRun::class))->handle($done);

    expect($props['run']['status'])->toBe('completed')
        ->and($props['run']['result']['first_try_accuracy'])->toEqual(1.0);
});

it('leaves the answer keys out of a check, which gives no verdict', function () {
    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::Check);

    $props = (app(PresentLessonRun::class))->handle($run);
    $json = json_encode($props, JSON_THROW_ON_ERROR);
    $passage = collect($props['plan'])->first(fn (array $entry): bool => $entry['format'] === 'listen_passage');

    expect($props['settings']['feedback'])->toBeFalse()
        ->and($json)->not->toContain('"accepted"')
        ->and($json)->not->toContain('"answer"')
        ->and($json)->not->toContain('"slots"')
        ->and($json)->not->toContain('"required"');

    LessonWorld::answer($this->user, $run, LessonExercise::query()->findOrFail($run->plan[0]['id']));

    expect((app(PresentLessonRun::class))->handle($run->fresh())['answers'][0]['correct'])->toBeNull();
});

it('does not leak the answer through the exercise keys of a check', function () {
    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::Check);
    $props = (app(PresentLessonRun::class))->handle($run);
    $exercises = LessonExercise::query()->whereIn('id', $run->planExerciseIds())->where('format', 'type_word')->get();

    expect($exercises)->not->toBeEmpty();

    foreach ($exercises as $exercise) {
        $entry = collect($props['plan'])->firstWhere('id', $exercise->id);
        $json = json_encode($entry, JSON_THROW_ON_ERROR);

        foreach ($exercise->payload['accepted'] as $accepted) {
            $answer = is_array($accepted) ? (string) $accepted['text'] : (string) $accepted;

            expect($json)->not->toContain(Str::slug($answer))->not->toContain($answer);
        }

        expect($entry['key'])->toBe((string) $exercise->id);
    }
});

it('presents a passage\'s questions without their answers in a check', function () {
    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    $run = LessonRun::factory()->create([
        'user_id' => $this->user->id,
        'lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Check)->id,
        'kind' => LessonRunKind::Check,
        'plan' => [['id' => LessonExercise::query()->where('key', 'task.listen_passage.reception')->value('id'), 'origin' => 'lesson']],
    ]);

    $entry = (app(PresentLessonRun::class))->handle($run)['plan'][0];

    expect($entry['payload']['questions'][0])->not->toHaveKey('answer')
        ->and($entry['payload'])->not->toHaveKey('substitute_questions');
});

describe('the unit overview', function () {
    it('shows five lessons, the unit\'s first open', function () {
        $overview = (new GetUnitLessonOverview)->handle($this->user, $this->unit);

        expect(array_column($overview['lessons'], 'state'))->toBe(['available', 'locked', 'locked', 'locked', 'locked'])
            ->and(array_column($overview['lessons'], 'stage'))->toBe(['meet', 'recall', 'sentences', 'task', 'check'])
            ->and($overview['mastery'])->toBe(['mastered' => 0, 'total' => 11])
            ->and($overview['skipped'])->toBe(['listening' => 0, 'speaking' => 0])
            ->and($overview['contentPending'])->toBeFalse();
    });

    it('shows a lesson in progress, a completed one with its best accuracy, and the next one open', function () {
        $run = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet)));
        (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Recall));

        $overview = (new GetUnitLessonOverview)->handle($this->user, $this->unit);

        expect(array_column($overview['lessons'], 'state'))->toBe(['completed', 'in_progress', 'locked', 'locked', 'locked'])
            ->and($overview['lessons'][0]['bestAccuracy'])->toBe(1.0)
            ->and($overview['lessons'][1]['bestAccuracy'])->toBeNull()
            ->and($run->status->value)->toBe('completed');
    });

    it('counts the listening and speaking exercises that were skipped', function () {
        LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet)));

        $overview = (new GetUnitLessonOverview)->handle($this->user, $this->unit);

        expect($overview['skipped']['speaking'])->toBe(5)
            ->and($overview['skipped']['listening'])->toBe(0);
    });

    it('opens the check the next day, then marks it as passed once everything is mastered', function () {
        LessonWorld::finishTeachingLessons($this->user, $this->unit);

        $states = fn (): array => array_column((new GetUnitLessonOverview)->handle($this->user, $this->unit)['lessons'], 'state', 'stage');

        expect($states()['check'])->toBe('available');

        $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::Check);
        LessonWorld::play($this->user, $run);

        $overview = (new GetUnitLessonOverview)->handle($this->user, $this->unit);

        expect($states()['check'])->toBe('completed')
            ->and($overview['mastery'])->toBe(['mastered' => 11, 'total' => 11]);
    });

    it('says the sentence and grammar lessons are on their way for a unit with only its words released', function () {
        (new SyncUnitLessons)->handle($this->unit, (new BuildUnitLessons)->handle($this->unit, new HotelContent(lessonsReviewed: false)));

        $overview = (new GetUnitLessonOverview)->handle($this->user, $this->unit);

        expect(array_column($overview['lessons'], 'state', 'stage'))->toMatchArray(['sentences' => 'coming', 'task' => 'coming'])
            ->and($overview['contentPending'])->toBeTrue()
            ->and($overview['mastery']['total'])->toBe(10);
    });

    it('has nothing to show for a unit without lessons', function () {
        $bare = LessonWorld::hotelUnit(slug: 'bare');
        $overview = (new GetUnitLessonOverview)->handle($this->user, $bare);

        expect(array_unique(array_column($overview['lessons'], 'state')))->toBe(['coming'])
            ->and($overview['contentPending'])->toBeFalse();
    });
});

it('leaves out a plan entry whose exercise no longer exists', function () {
    [$run] = lessonWithSubstitute($this);
    $plan = $run->plan;
    $plan[] = ['id' => 999999, 'origin' => 'lesson'];
    $run->forceFill(['plan' => $plan])->save();

    $props = (app(PresentLessonRun::class))->handle($run->fresh());

    expect(array_column($props['plan'], 'id'))->not->toContain(999999)
        ->and($props['plan'])->toHaveCount(count($plan) - 1);
});

it('marks a self-checked or flagged answer as settled and a wrong one as not', function () {
    [$run] = lessonWithSubstitute($this);
    $typed = LessonExercise::query()->whereIn('id', $run->planExerciseIds())->where('format', 'type_word')->firstOrFail();

    $wrong = LessonWorld::answer($this->user, $run, $typed, ['response' => ['text' => 'zzz']])['answer'];
    $selfChecked = LessonWorld::answer($this->user, $run, $typed, ['response' => ['text' => 'zzz'], 'self_graded_correct' => true])['answer'];

    $props = (app(PresentLessonRun::class))->handle($run->fresh());

    expect(array_column($props['answers'], 'settled'))->toBe([false, true])
        ->and($wrong->settlesExercise())->toBeFalse()
        ->and($selfChecked->settlesExercise())->toBeTrue();
});

it('marks every answer of a check as settled and gives it no verdict', function () {
    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    $check = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::Check);
    LessonWorld::answer($this->user, $check, LessonExercise::query()->findOrFail($check->plan[0]['id']), ['response' => ['text' => 'zzz']]);

    $answer = (app(PresentLessonRun::class))->handle($check->fresh())['answers'][0];

    expect($answer['settled'])->toBeTrue()
        ->and($answer['correct'])->toBeNull();
});
