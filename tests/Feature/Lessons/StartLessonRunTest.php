<?php

declare(strict_types=1);

use App\Actions\Lessons\BuildLessonPlan;
use App\Actions\Lessons\BuildUnitLessons;
use App\Actions\Lessons\StartLessonRun;
use App\Actions\Lessons\SyncUnitLessons;
use App\Enums\CefrLevel;
use App\Enums\LessonExerciseFormat;
use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Enums\LessonStage;
use App\Enums\LessonState;
use App\Enums\SrsRating;
use App\Enums\UnitProgressStatus;
use App\Lessons\TargetRef;
use App\Models\Language;
use App\Models\Lesson;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Models\Unit;
use App\Models\UnitItemMastery;
use App\Models\UserUnitProgress;
use App\Models\VocabularyItem;
use App\Services\LessonProgress;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\Fixtures\Lessons\HotelContent;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
    $this->lesson1 = LessonWorld::lesson($this->unit, LessonStage::Meet);
    $this->lesson2 = LessonWorld::lesson($this->unit, LessonStage::Recall);
    $this->check = LessonWorld::lesson($this->unit, LessonStage::Check);
});

function refusal(Closure $callback): string
{
    try {
        $callback();
    } catch (ValidationException $exception) {
        return $exception->errors()['lesson'][0];
    }

    return '';
}

it('starts a run with its plan and seed, and puts the unit in progress', function () {
    $run = (new StartLessonRun)->handle($this->user, $this->lesson1);

    expect($run->status)->toBe(LessonRunStatus::InProgress)
        ->and($run->kind)->toBe(LessonRunKind::Lesson)
        ->and($run->open_lesson_id)->toBe($this->lesson1->id)
        ->and($run->seed)->toBeGreaterThan(0)
        ->and(count($run->plan))->toBeGreaterThan(30)
        ->and(UserUnitProgress::query()->where('user_id', $this->user->id)->where('unit_id', $this->unit->id)->value('status'))->toBe(UnitProgressStatus::InProgress);
});

it('resumes the run in progress instead of duplicating it', function () {
    $first = (new StartLessonRun)->handle($this->user, $this->lesson1);
    $second = (new StartLessonRun)->handle($this->user, $this->lesson1);

    expect($second->id)->toBe($first->id)
        ->and(LessonRun::query()->count())->toBe(1);
});

it('opens the same run when a second create races the first', function () {
    $competitor = null;

    DB::listen(function ($query) use (&$competitor): void {
        if ($competitor !== null || ! str_contains($query->sql, 'from "lesson_runs"') || ! str_contains($query->sql, '"open_lesson_id"')) {
            return;
        }

        $competitor = DB::table('lesson_runs')->insertGetId([
            'user_id' => $this->user->id,
            'lesson_id' => $this->lesson1->id,
            'open_lesson_id' => $this->lesson1->id,
            'kind' => 'lesson',
            'status' => 'in_progress',
            'plan' => '[]',
            'seed' => 5,
            'counts_as_evidence' => false,
            'started_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    });

    $run = (new StartLessonRun)->handle($this->user, $this->lesson1);

    expect($run->id)->toBe($competitor)
        ->and(LessonRun::query()->count())->toBe(1);
});

it('lets another learner start the same lesson', function () {
    (new StartLessonRun)->handle($this->user, $this->lesson1);
    $other = LessonWorld::learner();

    (new StartLessonRun)->handle($other, $this->lesson1);

    expect(LessonRun::query()->count())->toBe(2);
});

it('shuffles inside blocks only, and a run keeps its plan', function () {
    $plan = (new BuildLessonPlan)->handle($this->user, $this->lesson1, LessonRunKind::Lesson, 7);
    $again = (new BuildLessonPlan)->handle($this->user, $this->lesson1, LessonRunKind::Lesson, 7);
    $other = (new BuildLessonPlan)->handle($this->user, $this->lesson1, LessonRunKind::Lesson, 8);

    $blocks = fn (array $built): array => array_map(
        fn (array $entry): string => LessonExercise::query()->findOrFail($entry['id'])->block,
        $built['plan'],
    );

    expect($again)->toBe($plan)
        ->and($other['plan'])->not->toBe($plan['plan'])
        ->and($blocks($plan))->toBe($blocks($other));
});

it('keeps every teach card before the exercises of its block', function () {
    $built = (new BuildLessonPlan)->handle($this->user, $this->lesson1, LessonRunKind::Lesson, 3);
    $seen = [];

    foreach ($built['plan'] as $entry) {
        $exercise = LessonExercise::query()->with('targets')->findOrFail($entry['id']);

        if ($exercise->format->isTeach()) {
            $seen[$exercise->targets[0]->targetable_id] = true;

            continue;
        }

        if ($exercise->format->value !== 'match_pairs') {
            expect($seen)->toHaveKey($exercise->targets[0]->targetable_id);
        }
    }
});

describe('unlocking', function () {
    it('opens a lesson only after the one before it has a completed run', function () {
        expect(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->lesson2)))->toBe('Finish the lesson before it first.');

        $run = (new StartLessonRun)->handle($this->user, $this->lesson1);
        LessonWorld::play($this->user, $run);

        expect((new StartLessonRun)->handle($this->user, $this->lesson2)->lesson_id)->toBe($this->lesson2->id);
    });

    it('lets a completed lesson be replayed at any time', function () {
        $run = (new StartLessonRun)->handle($this->user, $this->lesson1);
        LessonWorld::play($this->user, $run);

        $replay = (new StartLessonRun)->handle($this->user, $this->lesson1);

        expect($replay->id)->not->toBe($run->id)
            ->and($replay->status)->toBe(LessonRunStatus::InProgress);
    });

    it('opens the check on a later day than lesson 4, not the same day', function () {
        foreach ([LessonStage::Meet, LessonStage::Recall, LessonStage::Sentences, LessonStage::Task] as $stage) {
            $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, $stage));
            LessonWorld::play($this->user, $run);
        }

        expect(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Check)))->toBe('The check opens tomorrow.');

        $this->travel(1)->days();

        expect((new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Check)->kind)->toBe(LessonRunKind::Check);
    });

    it('keeps the check locked while a lesson is unfinished', function () {
        expect(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Check)))->toBe('Finish the lessons before the check.');
    });

    it('does not let a lesson be started as a check or the check as a lesson', function () {
        expect(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Lesson)))->toBe('That kind of run does not belong to this lesson.')
            ->and(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->lesson1, LessonRunKind::Check)))->toBe('That kind of run does not belong to this lesson.');
    });

    it('plans a check from one probe set, the one seen least recently', function () {
        $set = fn (): ?string => (new BuildLessonPlan)->handle($this->user, $this->check, LessonRunKind::Check)['probe_set'];
        $seen = fn (string $set, int $daysAgo) => LessonRun::factory()->completed()->create([
            'user_id' => $this->user->id,
            'lesson_id' => $this->check->id,
            'kind' => LessonRunKind::Check,
            'probe_set' => $set,
            'started_at' => now()->subDays($daysAgo),
        ]);

        expect($set())->toBe('a');

        $seen('a', 3);
        expect($set())->toBe('b');

        $seen('b', 2);
        expect($set())->toBe('a');

        $run = (new BuildLessonPlan)->handle($this->user, $this->check, LessonRunKind::Check);

        expect(LessonExercise::query()->whereIn('id', array_column($run['plan'], 'id'))->pluck('probe_set')->unique()->all())->toBe(['a']);
    });
});

describe('where the unit stands', function () {
    it('refuses a locked unit with 403 and a unit in the other language with 404', function () {
        $this->unit->forceFill(['cefr_level' => CefrLevel::B1])->save();

        expect(fn () => (new StartLessonRun)->handle($this->user, $this->lesson1))->toThrow(function (HttpException $exception) {
            expect($exception->getStatusCode())->toBe(403);
        });

        $this->unit->forceFill(['cefr_level' => CefrLevel::A1])->save();
        $portuguese = Language::factory()->create(['code' => 'pt']);
        $this->user->forceFill(['current_language_id' => $portuguese->id])->save();
        $this->user->unlockedLanguages()->attach($portuguese->id);

        expect(fn () => (new StartLessonRun)->handle($this->user->fresh(), $this->lesson1))->toThrow(function (HttpException $exception) {
            expect($exception->getStatusCode())->toBe(404);
        });
    });

    it('refuses a held-back new unit with the review message, but not a unit in progress', function () {
        $card = SrsCard::factory()->create(['user_id' => $this->user->id, 'language_id' => $this->unit->language_id]);
        SrsReview::factory()->count(8)->create(['user_id' => $this->user->id, 'srs_card_id' => $card->id, 'rating' => SrsRating::Again]);

        expect(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->lesson1)))->toBe('Clear your reviews first, then start this unit.');

        UserUnitProgress::factory()->create(['user_id' => $this->user->id, 'unit_id' => $this->unit->id, 'status' => UnitProgressStatus::InProgress, 'completed_at' => null]);

        expect((new StartLessonRun)->handle($this->user, $this->lesson1)->kind)->toBe(LessonRunKind::Lesson);
    });

    it('keeps a completed unit from being downgraded when a lesson is replayed', function () {
        UserUnitProgress::factory()->create(['user_id' => $this->user->id, 'unit_id' => $this->unit->id, 'status' => UnitProgressStatus::Completed]);

        (new StartLessonRun)->handle($this->user, $this->lesson1);

        expect(UserUnitProgress::query()->where('user_id', $this->user->id)->value('status'))->toBe(UnitProgressStatus::Completed);
    });

    it('words a refusal in the language of the app locale', function () {
        LessonExercise::query()->where('lesson_id', $this->lesson1->id)->update(['retired_at' => now()]);
        app()->setLocale('nl');

        expect(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->lesson1)))->toBe('Deze les heeft nog geen oefeningen.');
    });

    it('refuses a lesson that has no exercises', function () {
        LessonExercise::query()->where('lesson_id', $this->lesson1->id)->update(['retired_at' => now()]);

        expect(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->lesson1)))->toBe('This lesson has no exercises yet.');
    });
});

describe('warm-up and review', function () {
    it('takes up to five of the previous lesson\'s first-try misses', function () {
        $run = (new StartLessonRun)->handle($this->user, $this->lesson1);
        $misses = [];

        LessonWorld::play($this->user, $run, function (LessonExercise $exercise) use (&$misses): bool {
            if ($exercise->format->isTeach() || count($misses) >= 7) {
                return false;
            }

            $misses[] = $exercise->substitute_for_id ?? $exercise->id;

            return true;
        });

        $second = (new StartLessonRun)->handle($this->user, $this->lesson2);
        $warmup = collect($second->plan)->where('origin', 'warmup')->pluck('id')->all();

        expect($warmup)->toHaveCount(5)
            ->and(array_diff($warmup, $misses))->toBe([])
            ->and($second->plan[0]['origin'])->toBe('warmup');
    });

    it('gives lesson 1 no warm-up and no review', function () {
        $run = (new StartLessonRun)->handle($this->user, $this->lesson1);

        expect(collect($run->plan)->pluck('origin')->unique()->all())->toBe(['lesson']);
    });

    it('takes review exercises from a completed unit of this language, two in lesson 2', function () {
        $done = LessonWorld::hotelUnit(slug: 'done-unit');
        (new SyncUnitLessons)->handle($done, (new BuildUnitLessons)->handle($done, new HotelContent));
        UserUnitProgress::factory()->create(['user_id' => $this->user->id, 'unit_id' => $done->id, 'status' => UnitProgressStatus::Completed]);

        $plan = (new BuildLessonPlan)->handle($this->user, $this->lesson2, LessonRunKind::Lesson, 4)['plan'];
        $review = collect($plan)->where('origin', 'review');
        $doneLessons = Lesson::query()->where('unit_id', $done->id)->pluck('id');

        expect($review)->toHaveCount(2)
            ->and(LessonExercise::query()->whereIn('id', $review->pluck('id'))->whereIn('lesson_id', $doneLessons)->count())->toBe(2)
            ->and(LessonExercise::query()->whereIn('id', $review->pluck('id'))->pluck('format')->unique()->map(fn ($format) => $format->value)->all())->toBe(['type_word']);
    });

    it('never reviews from a unit that is not done or from another language', function () {
        $notDone = LessonWorld::hotelUnit(slug: 'not-done');
        (new SyncUnitLessons)->handle($notDone, (new BuildUnitLessons)->handle($notDone, new HotelContent));

        $portuguese = Language::factory()->create(['code' => 'pt']);
        $foreign = Unit::factory()->create(['language_id' => $portuguese->id]);
        $lesson = Lesson::factory()->stage(LessonStage::Recall)->create(['unit_id' => $foreign->id]);
        LessonExercise::factory()->create(['lesson_id' => $lesson->id, 'format' => LessonExerciseFormat::TypeWord]);
        UserUnitProgress::factory()->create(['user_id' => $this->user->id, 'unit_id' => $foreign->id, 'status' => UnitProgressStatus::Completed]);

        $plan = (new BuildLessonPlan)->handle($this->user, $this->lesson2, LessonRunKind::Lesson, 4)['plan'];

        expect(collect($plan)->where('origin', 'review'))->toHaveCount(0);
    });

    it('adds three review exercises in lesson 3 and four in lesson 4', function () {
        $done = LessonWorld::hotelUnit(slug: 'done-unit');
        (new SyncUnitLessons)->handle($done, (new BuildUnitLessons)->handle($done, new HotelContent));
        UserUnitProgress::factory()->create(['user_id' => $this->user->id, 'unit_id' => $done->id, 'status' => UnitProgressStatus::Completed]);

        $counts = fn (LessonStage $stage): int => collect((new BuildLessonPlan)->handle($this->user, LessonWorld::lesson($this->unit, $stage), LessonRunKind::Lesson, 1)['plan'])->where('origin', 'review')->count();

        expect($counts(LessonStage::Sentences))->toBe(3)
            ->and($counts(LessonStage::Task))->toBe(4);
    });
});

describe('remediation', function () {
    function finishedCheck(object $test, array $wrongKeys): LessonRun
    {
        LessonWorld::finishTeachingLessons($test->user, $test->unit);
        $run = (new StartLessonRun)->handle($test->user, $test->check, LessonRunKind::Check);

        return LessonWorld::play($test->user, $run, fn (LessonExercise $exercise): bool => in_array($exercise->key, $wrongKeys, true));
    }

    it('refuses practice and retake before any check, and when nothing is missing', function () {
        expect(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Practice)))->toBe('Take the unit check first.')
            ->and(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Retake)))->toBe('Take the unit check first.');
    });

    it('offers a practice run of three exercises per missed item and a retake the next day', function () {
        $check = finishedCheck($this, ['check.a.type_word.la-llave', 'check.b.type_word.la-llave']);
        $llave = VocabularyItem::query()->where('term', 'la llave')->firstOrFail();

        expect($check->status)->toBe(LessonRunStatus::Completed)
            ->and(UnitItemMastery::query()->where('masterable_id', $llave->id)->exists())->toBeFalse();

        $practice = (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Practice);
        $targets = fn (int $id): array => LessonExercise::query()->with('targets')->findOrFail($id)->targets->map(fn ($target): string => TargetRef::keyFor($target->targetable_type, $target->targetable_id))->all();

        expect(count($practice->plan))->toBeLessThanOrEqual(3 * 1)
            ->and(count($practice->plan))->toBeGreaterThan(0)
            ->and(collect($practice->plan)->pluck('origin')->unique()->all())->toBe(['practice'])
            ->and(collect($practice->plan)->every(fn (array $entry): bool => in_array(TargetRef::vocabulary($llave->id)->key(), $targets($entry['id']), true)))->toBeTrue();
    });

    it('holds only the missed item\'s probes in a retake, from the other set, and not on the same day', function () {
        finishedCheck($this, ['check.a.type_word.la-llave']);

        expect(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Retake)))->toBe('The retake opens tomorrow.');

        $this->travel(1)->days();
        $retake = (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Retake);
        $llave = VocabularyItem::query()->where('term', 'la llave')->firstOrFail();

        expect($retake->probe_set)->toBe('b')
            ->and($retake->plan)->not->toBeEmpty();

        foreach ($retake->plan as $entry) {
            $exercise = LessonExercise::query()->with('targets')->findOrFail($entry['id']);

            expect($exercise->probe_set)->toBe('b')
                ->and($exercise->targets->contains(fn ($target): bool => $target->is_probe && $target->targetable_id === $llave->id))->toBeTrue();
        }
    });

    it('refuses a retake while a practice run is open instead of silently resuming the practice', function () {
        finishedCheck($this, ['check.a.type_word.la-llave']);
        $practice = (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Practice);
        $this->travel(1)->days();

        expect(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Retake)))->toBe('Finish the run you have open for this lesson first.')
            ->and((new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Practice)->id)->toBe($practice->id)
            ->and(LessonRun::query()->where('open_lesson_id', $this->check->id)->count())->toBe(1);
    });

    it('refuses another full check after one was taken, today and on any later day, and leaves only practice and a retake', function () {
        finishedCheck($this, ['check.a.type_word.la-llave']);
        $message = 'Practise the missed items and retake them, instead of taking the whole check again.';

        expect(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Check)))->toBe($message)
            ->and((new LessonProgress)->state($this->user, $this->check))->toBe(LessonState::Remediation);

        $this->travel(1)->days();

        expect(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Check)))->toBe($message)
            ->and((new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Retake)->kind)->toBe(LessonRunKind::Retake);
    });

    it('spaces a retake of a retake by a day as well, and still refuses the full check', function () {
        finishedCheck($this, ['check.a.type_word.la-llave']);
        $this->travel(1)->days();

        $retake = (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Retake);

        foreach ($retake->planExerciseIds() as $id) {
            $exercise = LessonExercise::query()->findOrFail($id);
            LessonWorld::answer($this->user, $retake, $exercise, ['response' => LessonWorld::wrongResponse($exercise)]);
        }

        expect(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Retake)))->toBe('The retake opens tomorrow.')
            ->and(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Check)))->toContain('Practise the missed items');

        $this->travel(1)->days();

        expect((new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Retake)->probe_set)->toBe('a');
    });

    it('does not reopen the full check on the same day when a lesson is replayed after a failed check', function () {
        finishedCheck($this, ['check.a.type_word.la-llave']);
        $replay = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Task));
        LessonWorld::play($this->user, $replay);

        expect((new LessonProgress)->state($this->user, $this->check))->toBe(LessonState::OpensTomorrow);

        $this->travel(1)->days();

        expect((new LessonProgress)->state($this->user, $this->check))->toBe(LessonState::Available);
    });

    it('lets the check follow the lessons when only a test-out came before them', function () {
        $testOut = (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::TestOut);
        $testOut = LessonWorld::play($this->user, $testOut, fn (LessonExercise $exercise): bool => $exercise->key === 'check.a.type_word.la-llave');
        $testOut->forceFill(['completed_at' => now()->subDays(5)])->save();
        LessonWorld::finishTeachingLessons($this->user, $this->unit);

        expect((new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Check)->kind)->toBe(LessonRunKind::Check);
    });

    it('describes what is left to do after a check, and says nothing once everything is proven', function () {
        $check = finishedCheck($this, ['check.a.type_word.la-llave']);
        $progress = new LessonProgress;

        expect($progress->remediation($this->user, $this->unit))->toBe(['lessonId' => $this->check->id, 'missing' => 1, 'retake' => 'opens_tomorrow']);

        $this->travel(1)->days();

        expect($progress->remediation($this->user, $this->unit)['retake'])->toBe('open');

        $retake = (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Retake);
        LessonWorld::play($this->user, $retake);

        expect($progress->remediation($this->user, $this->unit))->toBeNull()
            ->and($check->status)->toBe(LessonRunStatus::Completed);
    });

    it('refuses the retake when everything is mastered', function () {
        finishedCheck($this, []);

        expect(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::Retake)))->toBe('Nothing is left to practise.');
    });
});

describe('testing out', function () {
    it('opens the check at once on a unit that has not been started, and is not delayed', function () {
        $run = (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::TestOut);

        expect($run->kind)->toBe(LessonRunKind::TestOut)
            ->and($run->probe_set)->toBe('a');
    });

    it('is refused once the unit has been started', function () {
        $run = (new StartLessonRun)->handle($this->user, $this->lesson1);
        LessonWorld::play($this->user, $run);

        expect(refusal(fn () => (new StartLessonRun)->handle($this->user, $this->check, LessonRunKind::TestOut)))->toBe('Taking the check now is for a unit you have not started.');
    });
});

it('moves a unit marked available to in progress when its first lesson starts', function () {
    UserUnitProgress::factory()->create(['user_id' => $this->user->id, 'unit_id' => $this->unit->id, 'status' => UnitProgressStatus::Available, 'completed_at' => null]);

    (new StartLessonRun)->handle($this->user, $this->lesson1);

    expect(UserUnitProgress::query()->where('user_id', $this->user->id)->value('status'))->toBe(UnitProgressStatus::InProgress);
});
