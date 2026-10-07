<?php

declare(strict_types=1);

use App\Actions\Lessons\CompleteLessonRun;
use App\Actions\Lessons\StartLessonRun;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Models\LessonExercise;
use App\Services\LessonMastery;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
    $this->lesson = LessonWorld::lesson($this->unit, LessonStage::Meet);
});

function playLesson(object $test, ?Closure $mistake = null)
{
    $run = LessonWorld::play($test->user, (new StartLessonRun)->handle($test->user, $test->lesson), $mistake);
    (new CompleteLessonRun)->handle($run);

    return $run->fresh();
}

it('has nothing mastered before a first run', function () {
    expect((new LessonMastery)->hasCompletedRun($this->user, $this->lesson))->toBeFalse()
        ->and((new LessonMastery)->isMastered($this->user, $this->lesson))->toBeFalse();
});

it('masters a lesson played right first time', function () {
    playLesson($this);

    expect((new LessonMastery)->missing($this->user, $this->lesson))->toBe([])
        ->and((new LessonMastery)->isMastered($this->user, $this->lesson))->toBeTrue()
        ->and((new LessonMastery)->share($this->user, $this->lesson))->toBe(1.0);
});

it('lists only what was missed, and a replay plans only those exercises', function () {
    $missed = null;
    $first = playLesson($this, function (LessonExercise $exercise) use (&$missed): bool {
        if ($missed === null && ! $exercise->format->isTeach()) {
            $missed = $exercise->id;
        }

        return $exercise->id === $missed;
    });

    expect((new LessonMastery)->missing($this->user, $this->lesson))->toBe([$missed])
        ->and((new LessonMastery)->share($this->user, $this->lesson))->toBeLessThan(1.0);

    $replay = (new StartLessonRun)->handle($this->user, $this->lesson);

    expect($replay->id)->not->toBe($first->id)
        ->and(array_column($replay->plan, 'id'))->toBe([$missed]);

    $replay = LessonWorld::play($this->user, $replay);
    (new CompleteLessonRun)->handle($replay);

    expect((new LessonMastery)->isMastered($this->user, $this->lesson))->toBeTrue();
});

it('replays the whole lesson once everything was right first time', function () {
    playLesson($this);

    $replay = (new StartLessonRun)->handle($this->user, $this->lesson);

    expect(count($replay->plan))->toBeGreaterThan(1)
        ->and($replay->kind)->toBe(LessonRunKind::Lesson);
});
