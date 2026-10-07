<?php

declare(strict_types=1);

use App\Actions\Lessons\BuildUnitLessons;
use App\Actions\Lessons\PresentLessonRun;
use App\Actions\Lessons\StartLessonRun;
use App\Actions\Lessons\SyncUnitLessons;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Enums\MasteryScope;
use App\Enums\UnitProgressStatus;
use App\Lessons\PreviewContent;
use App\Models\Lesson;
use App\Models\LessonExercise;
use App\Models\Unit;
use App\Models\UnitItemMastery;
use App\Models\UserUnitProgress;
use App\Services\UnitContentRegistry;
use Database\Content\Lessons\Es\CheckingIntoAHotel;
use Database\Seeders\ContentSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\SpanishA1Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(SpanishA1Seeder::class);
    $this->unit = Unit::query()->where('slug', 'checking-into-a-hotel')->firstOrFail();
    $this->released = new PreviewContent(new CheckingIntoAHotel, withLessons: true);
    $this->user = LessonWorld::learner();
    $this->check = fn (): Lesson => LessonWorld::lesson($this->unit, LessonStage::Check);
});

it('seeds the full unit once reviewed content is released, and a second pass writes nothing', function () {
    app()->instance(UnitContentRegistry::class, new UnitContentRegistry([$this->released]));
    $this->seed(ContentSeeder::class);

    $count = LessonExercise::query()->count();
    $updated = LessonExercise::query()->max('updated_at');
    $this->travel(1)->minute();
    $this->seed(ContentSeeder::class);

    expect(Lesson::query()->where('unit_id', $this->unit->id)->orderBy('position')->pluck('stage')->map(fn (LessonStage $stage): string => $stage->value)->all())->toBe(['meet', 'recall', 'sentences', 'task', 'check'])
        ->and(LessonExercise::query()->count())->toBe($count)
        ->and(LessonExercise::query()->max('updated_at'))->toEqual($updated)
        ->and(DB::table('lesson_exercise_targets')->count())->toBeGreaterThan(300);
});

it('retires the authored exercises when the gate closes again, and deletes nothing', function () {
    (new SyncUnitLessons)->handle($this->unit, (new BuildUnitLessons)->handle($this->unit, $this->released));
    $total = LessonExercise::query()->count();

    app()->instance(UnitContentRegistry::class, new UnitContentRegistry([new PreviewContent(new CheckingIntoAHotel)]));
    $this->seed(ContentSeeder::class);

    expect(LessonExercise::query()->count())->toBe($total)
        ->and(LessonExercise::query()->whereNull('retired_at')->count())->toBeLessThan($total)
        ->and(LessonExercise::query()->whereNotNull('retired_at')->where('key', 'like', 'check.a.translate.%')->count())->toBe(4)
        ->and(LessonExercise::query()->whereNull('retired_at')->where('key', 'like', 'check.a.translate.%')->count())->toBe(0);
});

describe('with the content released', function () {
    beforeEach(function () {
        (new SyncUnitLessons)->handle($this->unit, (new BuildUnitLessons)->handle($this->unit, $this->released));
    });

    it('lets a learner who knows everything play the four lessons and the check, and completes the unit', function () {
        foreach ([LessonStage::Meet, LessonStage::Recall, LessonStage::Sentences, LessonStage::Task] as $stage) {
            $run = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, $stage)));
            expect($run->first_try_accuracy)->toEqual(1.0);
            $run->forceFill(['completed_at' => now()->subDays(2)])->save();
        }

        $check = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, ($this->check)(), LessonRunKind::Check));

        expect($check->result['missing'])->toBe([])
            ->and($check->result['unit_completed'])->toBeTrue()
            ->and(UnitItemMastery::query()->where('user_id', $this->user->id)->where('scope', MasteryScope::Full)->count())->toBe(count((new CheckingIntoAHotel)->words()) + 1)
            ->and(UserUnitProgress::query()->where('user_id', $this->user->id)->where('unit_id', $this->unit->id)->value('status'))->toBe(UnitProgressStatus::Completed);
    });

    it('sends a failed check to practice and a retake on a later day, from the other probe set, and then completes', function () {
        LessonWorld::finishTeachingLessons($this->user, $this->unit);
        $run = (new StartLessonRun)->handle($this->user, ($this->check)(), LessonRunKind::Check);

        $check = LessonWorld::play($this->user, $run, fn (LessonExercise $exercise): bool => $exercise->key === 'check.a.translate.bano');

        expect($check->result['unit_completed'])->toBeFalse()
            ->and($check->result['missing'])->not->toBeEmpty();

        $practice = (new StartLessonRun)->handle($this->user, ($this->check)(), LessonRunKind::Practice);
        LessonWorld::play($this->user, $practice);

        expect($practice->fresh()?->plan)->not->toBeEmpty()
            ->and(fn () => (new StartLessonRun)->handle($this->user, ($this->check)(), LessonRunKind::Retake))->toThrow(ValidationException::class)
            ->and(fn () => (new StartLessonRun)->handle($this->user, ($this->check)(), LessonRunKind::Check))->toThrow(ValidationException::class);

        $this->travel(1)->days();
        $retake = (new StartLessonRun)->handle($this->user, ($this->check)(), LessonRunKind::Retake);
        $done = LessonWorld::play($this->user, $retake);

        expect($retake->probe_set)->toBe('b')
            ->and($done->result['missing'])->toBe([])
            ->and($done->result['unit_completed'])->toBeTrue();
    });

    it('shows a lesson passage with its text and answers, and a check with neither', function () {
        LessonWorld::finishTeachingLessons($this->user, $this->unit);
        $task = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Task));
        $entry = collect(app(PresentLessonRun::class)->handle($task)['plan'])->firstWhere('format', 'listen_passage');

        expect($entry['payload']['lines'])->toHaveCount(5)
            ->and($entry['payload']['lines'][0])->toHaveKeys(['speaker', 'text', 'audioUrl', 'audioSlowUrl'])
            ->and($entry['payload'])->not->toHaveKey('dialogue')
            ->and($entry['payload']['questions'][0])->toHaveKey('answer')
            ->and($entry['substitute']['format'])->toBe('read_passage');

        $check = (new StartLessonRun)->handle($this->user, ($this->check)(), LessonRunKind::Check);
        $props = app(PresentLessonRun::class)->handle($check);
        $passage = collect($props['plan'])->firstWhere('format', 'listen_passage');

        expect($passage['payload']['lines'][0])->not->toHaveKey('text')
            ->and($passage['payload']['questions'][0])->not->toHaveKey('answer')
            ->and(json_encode($passage['payload'], JSON_UNESCAPED_UNICODE))->not->toContain('Su habitación es la cinco')
            ->and($passage['substitute']['format'])->toBe('read_passage')
            ->and(json_encode($props, JSON_UNESCAPED_UNICODE))->not->toContain('homophone');
    });

    it('takes up to five misses of the previous lesson into the next lesson as a warm-up', function () {
        $first = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));
        LessonWorld::play($this->user, $first, fn (LessonExercise $exercise): bool => $exercise->format->value === 'type_word')->forceFill(['completed_at' => now()->subDay()])->save();
        $second = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Recall));
        $third = LessonWorld::play($this->user, $second);
        $third->forceFill(['completed_at' => now()->subDay()])->save();
        $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Sentences));

        expect(collect($run->plan)->where('origin', 'warmup')->count())->toBeLessThanOrEqual(5);
    });

    it('answers a guided text with the words it used, the ones it missed and the model answer', function () {
        LessonWorld::finishTeachingLessons($this->user, $this->unit);
        $task = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Task));
        $exercise = LessonExercise::query()->where('key', 'task.write_guided.room')->firstOrFail();
        $result = LessonWorld::answer($this->user, $task, $exercise, ['response' => ['text' => 'Quiero una habitación para dos noches.']]);

        expect($result['grade']->details)->toBe(['found' => ['habitación', 'noches'], 'missing' => ['desayuno', 'incluido']])
            ->and($result['grade']->expected)->toBe('Quiero una habitación para dos noches. ¿El desayuno está incluido?')
            ->and($result['grade']->correct)->toBeFalse();
    });
});

describe('the content pipeline for the hotel unit', function () {
    it('renders the review sheet with the checklist, the authored lessons, both check sets and the targets', function () {
        Artisan::call('lessons:review-sheet', ['language' => 'es', 'unit' => 'checking-into-a-hotel']);

        expect(Artisan::output())->toContain(
            '## Checklist for the reviewer',
            '### Lesson 3: Build sentences',
            'check set b',
            'Why: Where something is takes estar.',
            'targets: la llave as',
            'contrast',
            'keyword slots: tengo / tiene / tenemos ; reserva',
            'required words: habitación / habitaciones',
            'question if skipped:',
        );
    });

    it('leaves the checklist and both check sets out of the owner sheet', function () {
        Artisan::call('lessons:review-sheet', ['language' => 'es', 'unit' => 'checking-into-a-hotel', '--audience' => 'owner']);
        $sheet = Artisan::output();

        expect($sheet)->toContain('### Lesson 4: Do the task')
            ->not->toContain('Checklist for the reviewer')
            ->not->toContain('check set')
            ->not->toContain('Unit check')
            ->not->toContain('check.a');
    });

    it('lists every word of the authored content for the spelling pass', function () {
        Artisan::call('lessons:words', ['language' => 'es', 'unit' => 'checking-into-a-hotel']);
        $words = explode("\n", trim(Artisan::output()));

        expect($words)->toContain('habitaciones', 'quiero', 'cuántas', 'recepcionista', 'está', 'noches', 'disponibles');
    });

    it('answers a guided text over HTTP with the words it used and the model answer', function () {
        (new SyncUnitLessons)->handle($this->unit, (new BuildUnitLessons)->handle($this->unit, $this->released));
        LessonWorld::finishTeachingLessons($this->user, $this->unit);
        $task = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Task));
        $exercise = LessonExercise::query()->where('key', 'task.write_guided.reserva')->firstOrFail();

        $this->actingAs($this->user)
            ->postJson(route('lesson-runs.answers.store', [$task, (string) Str::uuid()]), ['exercise_id' => $exercise->id, 'response' => ['text' => 'Tengo una reserva.']])
            ->assertOk()
            ->assertJsonPath('details', ['found' => ['reserva'], 'missing' => ['dónde', 'baño']])
            ->assertJsonPath('expected', 'Tengo una reserva. ¿Dónde está el baño?');
    });

    it('offers the remediation to a completed check on the player, and not to a lesson', function () {
        (new SyncUnitLessons)->handle($this->unit, (new BuildUnitLessons)->handle($this->unit, $this->released));
        LessonWorld::finishTeachingLessons($this->user, $this->unit);
        $run = (new StartLessonRun)->handle($this->user, ($this->check)(), LessonRunKind::Check);
        $done = LessonWorld::play($this->user, $run, fn (LessonExercise $exercise): bool => $exercise->key === 'check.a.translate.bano');
        $props = app(PresentLessonRun::class)->handle($done);

        expect($props['run']['remediation']['retake'])->toBe('opens_tomorrow')
            ->and($props['run']['remediation']['missing'])->toBeGreaterThan(0)
            ->and($props['run']['remediation']['lessonId'])->toBe(($this->check)()->id);

        $lesson = LessonWorld::play($this->user, (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet)));

        expect(app(PresentLessonRun::class)->handle($lesson)['run']['remediation'])->toBeNull();
    });
});

it('lists a passage answer and a keyword answer in the summary of a check, with the expected answers', function () {
    (new SyncUnitLessons)->handle($this->unit, (new BuildUnitLessons)->handle($this->unit, $this->released));
    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    $run = (new StartLessonRun)->handle($this->user, ($this->check)(), LessonRunKind::Check);
    $done = LessonWorld::play($this->user, $run);
    $answers = collect(app(PresentLessonRun::class)->handle($done)['run']['summary']['answers']);

    expect($answers->firstWhere('prompt', 'Read the conversation.')['given'])->toBe('three / Yes')
        ->and($answers->firstWhere('prompt', 'Read the conversation.')['expected'])->toBe('three / Yes');
});
