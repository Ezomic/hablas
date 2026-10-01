<?php

declare(strict_types=1);

use App\Actions\Lessons\BuildUnitLessons;
use App\Actions\Lessons\EvaluateUnitMastery;
use App\Actions\Lessons\StartLessonRun;
use App\Actions\Lessons\SyncUnitLessons;
use App\Actions\Srs\EnrollPendingUnitContent;
use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Enums\LessonStage;
use App\Enums\MasteryScope;
use App\Enums\UnitProgressStatus;
use App\Models\GrammarPoint;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\SrsCard;
use App\Models\UnitItemMastery;
use App\Models\UserUnitProgress;
use App\Models\VocabularyItem;
use App\Services\UnitMasteryReader;
use Illuminate\Support\Facades\DB;
use Tests\Fixtures\Lessons\HotelContent;
use Tests\Fixtures\Lessons\LessonWorld;

/**
 * Plays the run, answering the named exercises wrong. A check gives nothing
 * back, so each is answered once.
 *
 * @param  list<string>  $wrongKeys
 */
function playCheck(object $test, LessonRun $run, array $wrongKeys): LessonRun
{
    foreach ($run->planExerciseIds() as $id) {
        $exercise = LessonExercise::query()->with('substitute')->findOrFail($id);

        if ($exercise->format->isSpeaking()) {
            LessonWorld::answer($test->user, $run, $exercise, ['skipped' => true, 'skip_reason' => 'unsupported', 'response' => null]);
            $exercise = $exercise->substitute ?? $exercise;
        }

        LessonWorld::answer($test->user, $run, $exercise, in_array($exercise->key, $wrongKeys, true) ? ['response' => LessonWorld::wrongResponse($exercise)] : []);
    }

    return $run->fresh() ?? $run;
}

function masteredTerms(object $test): array
{
    return UnitItemMastery::query()
        ->where('user_id', $test->user->id)
        ->where('masterable_type', (new VocabularyItem)->getMorphClass())
        ->get()
        ->map(fn (UnitItemMastery $mastery): string => VocabularyItem::query()->findOrFail($mastery->masterable_id)->term)
        ->sort()
        ->values()
        ->all();
}

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
    $this->grammar = GrammarPoint::query()->where('unit_id', $this->unit->id)->firstOrFail();
});

function startCheck(object $test): LessonRun
{
    LessonWorld::finishTeachingLessons($test->user, $test->unit);

    return (new StartLessonRun)->handle($test->user, LessonWorld::lesson($test->unit, LessonStage::Check), LessonRunKind::Check);
}

it('masters a word only when all its probes in the run are right', function () {
    $run = startCheck($this);
    $run = playCheck($this, $run, ['check.a.type_word.la-llave']);

    expect(masteredTerms($this))->not->toContain('la llave')
        ->and(masteredTerms($this))->toContain('el hotel', 'la habitación', 'el baño')
        ->and($run->result['missing'])->toContain(['type' => (new VocabularyItem)->getMorphClass(), 'id' => VocabularyItem::query()->where('term', 'la llave')->value('id')]);
});

it('fails a word with two probes when only one of them is wrong', function () {
    $run = startCheck($this);
    playCheck($this, $run, ['check.a.translate.0']);

    expect(masteredTerms($this))->not->toContain('el baño', 'la habitación')
        ->and(masteredTerms($this))->toContain('la llave', 'el hotel');
});

it('never counts speaking, guided writing, tiles, choices or passages as mastery probes', function () {
    $probeExercises = LessonExercise::query()->whereHas('targets', fn ($query) => $query->where('is_probe', true))->get();
    $formats = $probeExercises->map(fn (LessonExercise $exercise): string => $exercise->format->value)->unique()->sort()->values()->all();

    expect($formats)->toBe(['listen_type', 'translate_sentence', 'type_gap', 'type_word'])
        ->and($probeExercises->every(fn (LessonExercise $exercise): bool => $exercise->lesson->stage === LessonStage::Check))->toBeTrue();

    $run = startCheck($this);
    $run = playCheck($this, $run, ['check.a.speak_answer', 'check.a.speak_answer.sub']);

    expect(masteredTerms($this))->toHaveCount(10);
});

describe('the grammar point', function () {
    it('is mastered with both contrast probes right and at most one other wrong', function () {
        $run = startCheck($this);
        playCheck($this, $run, ['check.a.translate.0']);

        expect(UnitItemMastery::query()->where('masterable_type', (new GrammarPoint)->getMorphClass())->where('scope', MasteryScope::Full)->count())->toBe(1);
    });

    it('is never mastered without a contrast probe, even when every other probe is right', function () {
        DB::table('lesson_exercise_targets')->where('targetable_type', (new GrammarPoint)->getMorphClass())->update(['is_contrast' => false]);

        $run = startCheck($this);
        playCheck($this, $run, []);

        expect(UnitItemMastery::query()->where('masterable_type', (new GrammarPoint)->getMorphClass())->exists())->toBeFalse()
            ->and(masteredTerms($this))->toHaveCount(10);
    });

    it('needs the contrast probes: a wrong contrast item keeps it missing', function (string $wrong) {
        $run = startCheck($this);
        playCheck($this, $run, [$wrong]);

        expect(UnitItemMastery::query()->where('masterable_type', (new GrammarPoint)->getMorphClass())->exists())->toBeFalse();
    })->with([
        'a gap whose answer is not the target pattern' => ['check.a.type_gap.hay'],
        'a dictation whose answer is not the target pattern' => ['check.a.listen_type.es'],
    ]);

    it('fails with two wrong probes that are not contrast items', function () {
        $run = startCheck($this);
        playCheck($this, $run, ['check.a.translate.0', 'check.a.translate.1']);

        expect(UnitItemMastery::query()->where('masterable_type', (new GrammarPoint)->getMorphClass())->exists())->toBeFalse();
    });
});

it('never counts a flagged probe as right', function () {
    $run = startCheck($this);
    $exercise = LessonExercise::query()->where('key', 'check.a.type_word.la-llave')->firstOrFail();
    LessonWorld::answer($this->user, $run, $exercise);
    LessonAnswer::query()->where('lesson_exercise_id', $exercise->id)->update(['flagged_at' => now()]);

    foreach ($run->planExerciseIds() as $id) {
        if ($id !== $exercise->id) {
            $other = LessonExercise::query()->with('substitute')->findOrFail($id);

            if ($other->format->isSpeaking()) {
                LessonWorld::answer($this->user, $run, $other, ['skipped' => true, 'skip_reason' => 'unsupported', 'response' => null]);
                $other = $other->substitute;
            }

            LessonWorld::answer($this->user, $run, $other);
        }
    }

    expect(masteredTerms($this))->not->toContain('la llave')
        ->and(masteredTerms($this))->toContain('el hotel');
});

it('never lets a skipped probe that has no substitute settle the run or count towards mastery', function () {
    $run = startCheck($this);
    $skipped = LessonExercise::query()->where('key', 'check.a.type_word.la-llave')->firstOrFail();

    foreach ($run->planExerciseIds() as $id) {
        $exercise = LessonExercise::query()->with('substitute')->findOrFail($id);

        if ($id === $skipped->id) {
            LessonAnswer::factory()->create(['lesson_run_id' => $run->id, 'lesson_exercise_id' => $id, 'skipped' => true, 'is_correct' => null]);

            continue;
        }

        if ($exercise->format->isSpeaking()) {
            LessonWorld::answer($this->user, $run, $exercise, ['skipped' => true, 'skip_reason' => 'unsupported', 'response' => null]);
            $exercise = $exercise->substitute;
        }

        LessonWorld::answer($this->user, $run, $exercise);
    }

    $result = (new EvaluateUnitMastery)->handle($run->fresh());

    expect($run->fresh()->status)->toBe(LessonRunStatus::InProgress)
        ->and(masteredTerms($this))->not->toContain('la llave')
        ->and(masteredTerms($this))->toContain('el hotel')
        ->and($result['unit_completed'])->toBeFalse();
});

it('lets a substitute probe stand in for a skipped dictation', function () {
    $run = startCheck($this);
    $dictation = LessonExercise::query()->where('key', 'check.a.listen_type.es')->with('substitute')->firstOrFail();

    foreach ($run->planExerciseIds() as $id) {
        $exercise = LessonExercise::query()->with('substitute')->findOrFail($id);

        if ($exercise->format->isSpeaking() || $id === $dictation->id) {
            LessonWorld::answer($this->user, $run, $exercise, ['skipped' => true, 'skip_reason' => 'paused', 'response' => null]);
            $exercise = $exercise->substitute;
        }

        LessonWorld::answer($this->user, $run, $exercise);
    }

    $reserva = VocabularyItem::query()->where('term', 'la reserva')->firstOrFail();

    expect(masteredTerms($this))->toContain('la reserva')
        ->and(UnitItemMastery::query()->where('masterable_type', (new GrammarPoint)->getMorphClass())->exists())->toBeTrue()
        ->and(LessonAnswer::query()->where('skipped', true)->where('lesson_exercise_id', $dictation->id)->count())->toBe(1);
});

it('accumulates mastery across a check and a retake, and completes the unit when everything is mastered', function () {
    $run = startCheck($this);
    playCheck($this, $run, ['check.a.type_word.la-llave']);

    expect(UserUnitProgress::query()->where('user_id', $this->user->id)->value('status'))->toBe(UnitProgressStatus::InProgress)
        ->and((new UnitMasteryReader)->missing($this->user, $this->unit))->toHaveCount(1);

    $this->travel(1)->days();
    $retake = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::Retake);
    $retake = LessonWorld::play($this->user, $retake);

    expect($retake->probe_set)->toBe('b')
        ->and(masteredTerms($this))->toHaveCount(10)
        ->and($retake->result['unit_completed'])->toBeTrue()
        ->and($retake->result['missing'])->toBe([])
        ->and(UserUnitProgress::query()->where('user_id', $this->user->id)->value('status'))->toBe(UnitProgressStatus::Completed);
});

it('completes the unit exactly once', function () {
    $run = startCheck($this);
    $run = playCheck($this, $run, []);
    $completedAt = UserUnitProgress::query()->where('user_id', $this->user->id)->firstOrFail()->completed_at;

    expect($run->result['unit_completed'])->toBeTrue();

    $progress = UserUnitProgress::query()->where('user_id', $this->user->id)->firstOrFail();
    $this->travel(1)->days();
    (new EvaluateUnitMastery)->handle($run);

    expect($progress->fresh()->completed_at->equalTo($completedAt))->toBeTrue()
        ->and(UserUnitProgress::query()->count())->toBe(1);
});

it('enrols mastered items at once, up to the daily cap, in their own language only', function () {
    $run = startCheck($this);
    $run = playCheck($this, $run, ['check.a.type_word.la-llave']);

    $cards = SrsCard::query()->where('user_id', $this->user->id)->get();

    expect($run->result['enrolled'])->toBe(10)
        ->and($cards)->toHaveCount(10)
        ->and($cards->pluck('language_id')->unique()->all())->toBe([$this->unit->language_id])
        ->and($cards->pluck('cardable_type')->unique()->sort()->values()->all())->toBe([(new GrammarPoint)->getMorphClass(), (new VocabularyItem)->getMorphClass()]);
});

it('keeps the cap when the whole unit is mastered, leaving one item for the next review session', function () {
    $run = startCheck($this);
    $run = playCheck($this, $run, []);

    expect($run->result['unit_completed'])->toBeTrue()
        ->and($run->result['enrolled'])->toBe(10)
        ->and(SrsCard::query()->where('user_id', $this->user->id)->count())->toBe(10);

    (new EnrollPendingUnitContent)->handle($this->user, $this->unit->language);

    expect(SrsCard::query()->where('user_id', $this->user->id)->count())->toBe(10);

    $this->travel(1)->days();
    (new EnrollPendingUnitContent)->handle($this->user, $this->unit->language);

    expect(SrsCard::query()->where('user_id', $this->user->id)->count())->toBe(11);
});

describe('a unit whose sentence and grammar lessons are not released', function () {
    beforeEach(function () {
        $this->content = new HotelContent(lessonsReviewed: false);
        (new SyncUnitLessons)->handle($this->unit, (new BuildUnitLessons)->handle($this->unit, $this->content));
    });

    it('masters words with the words scope and never completes the unit', function () {
        LessonWorld::finishTeachingLessons($this->user, $this->unit);
        $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::Check);
        $run = LessonWorld::play($this->user, $run);

        expect(UnitItemMastery::query()->where('scope', MasteryScope::Words)->count())->toBe(10)
            ->and(UnitItemMastery::query()->where('scope', MasteryScope::Full)->count())->toBe(0)
            ->and($run->result['unit_completed'])->toBeFalse()
            ->and($run->result['missing'])->toBe([])
            ->and(UserUnitProgress::query()->where('user_id', $this->user->id)->value('status'))->toBe(UnitProgressStatus::InProgress)
            ->and(SrsCard::query()->where('user_id', $this->user->id)->count())->toBe(10);
    });

    it('has a words-only check scope until the full content lands, and then asks for everything again', function () {
        expect((new UnitMasteryReader)->scope($this->unit))->toBe(MasteryScope::Words);

        LessonWorld::finishTeachingLessons($this->user, $this->unit);
        LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Check), LessonRunKind::Check));

        (new SyncUnitLessons)->handle($this->unit, (new BuildUnitLessons)->handle($this->unit, new HotelContent));

        expect((new UnitMasteryReader)->scope($this->unit))->toBe(MasteryScope::Full)
            ->and((new UnitMasteryReader)->missing($this->user, $this->unit))->toHaveCount(11);
    });
});
