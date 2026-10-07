<?php

declare(strict_types=1);

use App\Actions\Lessons\PresentLessonRun;
use App\Actions\Lessons\StartLessonRun;
use App\Enums\ExerciseFamily;
use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Enums\SpeechSpeed;
use App\Models\Lesson;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\SpeechClip;
use App\Models\UserSetting;
use App\Speech\SpeechKey;
use Illuminate\Support\Facades\Storage;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    Storage::fake('public');
    config(['speech.disk' => 'public']);

    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();

    $spokenAnswer = LessonExercise::factory()->create([
        'lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Recall)->id,
        'key' => 'recall.speak_answer.la-llave',
        'format' => Format::SpeakAnswer,
        'payload' => ['prompt' => 'key', 'english' => 'key', 'text' => 'la llave', 'slots' => [['la llave']], 'accepted' => [['text' => 'la llave', 'spans' => []]]],
    ]);

    LessonExercise::factory()->create([
        'lesson_id' => $spokenAnswer->lesson_id,
        'key' => 'recall.speak_answer.la-llave.sub',
        'format' => Format::TypeWord,
        'payload' => ['prompt' => 'key', 'english' => 'key', 'accepted' => [['text' => 'la llave', 'spans' => []]]],
        'substitute_for_id' => $spokenAnswer->id,
    ]);
});

function clipFor(string $text, SpeechSpeed $speed = SpeechSpeed::Normal): void
{
    SpeechClip::query()->create([
        'language' => 'es',
        'voice_id' => 'supertonic-f1',
        'speed' => $speed,
        'hash' => app(SpeechKey::class)->make('es', 'supertonic-f1', $speed, $text),
        'bytes' => 1000,
    ]);
}

/** @param  list<LessonExercise>  $exercises */
function runWith(object $test, LessonRunKind $kind, LessonStage $stage, array $exercises): LessonRun
{
    return LessonRun::factory()->create([
        'user_id' => $test->user->id,
        'lesson_id' => LessonWorld::lesson($test->unit, $stage)->id,
        'kind' => $kind,
        'plan' => array_map(fn (LessonExercise $exercise): array => ['id' => $exercise->id, 'origin' => 'lesson'], $exercises),
    ]);
}

function exerciseOf(string $key): LessonExercise
{
    return LessonExercise::query()->where('key', $key)->firstOrFail();
}

function listenPair(object $test): LessonExercise
{
    return LessonExercise::factory()->create([
        'lesson_id' => LessonWorld::lesson($test->unit, LessonStage::Recall)->id,
        'format' => Format::ListenPair,
        'payload' => ['text' => 'la llave', 'english' => 'key', 'options' => ['la llave', 'la reserva'], 'answer' => 'la llave'],
    ]);
}

/** @return array<string, mixed> */
function presentedEntry(LessonRun $run, LessonExercise $exercise): array
{
    $plan = app(PresentLessonRun::class)->handle($run)['plan'];

    return collect($plan)->firstWhere('id', $exercise->id) ?? throw new RuntimeException('Not in the plan.');
}

describe('a check', function () {
    it('keeps the spoken text and the answers of every listening and speaking exercise out of its props', function (string $key, ?string $spoken) {
        LessonWorld::finishTeachingLessons($this->user, $this->unit);
        $exercise = $key === 'listen_pair' ? listenPair($this) : exerciseOf($key);
        $run = runWith($this, LessonRunKind::Check, LessonStage::Check, [$exercise]);

        $entry = presentedEntry($run, $exercise);
        $payload = $entry['payload'];

        expect($payload)->not->toHaveKeys(['text', 'accepted', 'answer', 'slots', 'pattern', 'required']);

        if ($spoken !== null) {
            expect(json_encode($payload, JSON_THROW_ON_ERROR))->not->toContain($spoken);
        }

        if ($entry['substitute'] !== null && $exercise->format === Format::ListenType && $spoken !== null) {
            expect(json_encode($entry['substitute'], JSON_THROW_ON_ERROR))->not->toContain($spoken);
        }
    })->with([
        'dictation' => ['check.a.listen_type.es', 'La reserva es para dos noches'],
        'a word heard and chosen' => ['meet.listen_choose.el-hotel', 'el hotel'],
        'a pair heard, whose options are all that is left' => ['listen_pair', null],
        'a phrase repeated' => ['sentences.speak_repeat.hay', 'habitación disponible'],
        'a spoken answer to an English cue' => ['recall.speak_answer.la-llave', 'la llave'],
        'a spoken answer to a question' => ['check.a.speak_answer', 'Tiene una reserva'],
    ]);

    it('sends the clip of a dictation instead of its text, and plays a question that is only heard', function () {
        LessonWorld::finishTeachingLessons($this->user, $this->unit);
        clipFor('La reserva es para dos noches.');
        clipFor('¿Tiene una reserva?');
        $dictation = exerciseOf('check.a.listen_type.es');
        $question = exerciseOf('check.a.speak_answer');
        $run = runWith($this, LessonRunKind::Check, LessonStage::Check, [$dictation, $question]);

        $dictationPayload = presentedEntry($run, $dictation)['payload'];
        $questionPayload = presentedEntry($run, $question)['payload'];

        expect($dictationPayload['audioUrl'])->toContain('.mp3')
            ->and($dictationPayload['audioSlowUrl'])->toBeNull()
            ->and($dictationPayload['audioRole'])->toBe('prompt')
            ->and($questionPayload['audioUrl'])->toContain('.mp3')
            ->and($questionPayload['audioRole'])->toBe('prompt')
            ->and($questionPayload)->not->toHaveKeys(['prompt', 'english']);
    });
});

describe('the allow-list', function () {
    it('sends only named keys for every spoken exercise in lessons and checks, whatever else the payload holds', function () {
        LessonWorld::finishTeachingLessons($this->user, $this->unit);
        $extras = ['portunol_slips' => ['SECRETSLIP'], 'model' => 'SECRETMODEL', 'target' => 'SECRETTARGET', 'pattern' => 'SECRETPATTERN', 'slots' => [['SECRETSLOT']], 'accepted' => ['SECRETACCEPTED'], 'future_key' => 'SECRETFUTURE'];
        $spoken = LessonExercise::query()->whereIn('format', [Format::ListenChoose, Format::ListenPair, Format::ListenType, Format::SpeakRepeat, Format::SpeakAnswer])->whereNull('substitute_for_id')->get();
        $spoken->push(listenPair($this));

        expect($spoken->count())->toBeGreaterThan(10);

        foreach ($spoken as $exercise) {
            $exercise->forceFill(['payload' => [...$exercise->payload, ...$extras]])->save();
        }

        foreach ([[LessonRunKind::Lesson, LessonStage::Task, false], [LessonRunKind::Check, LessonStage::Check, true]] as [$kind, $stage, $check]) {
            $run = runWith($this, $kind, $stage, $spoken->all());
            $allowed = ['prompt', 'english', 'text', 'options', 'answer', 'audioUrl', 'audioSlowUrl', 'audioRole'];

            foreach ($spoken as $exercise) {
                $payload = presentedEntry($run, $exercise)['payload'];

                $sendsModel = ! $check && $exercise->format === Format::SpeakAnswer;

                expect(array_diff(array_keys($payload), [...$allowed, ...($sendsModel ? ['model'] : [])]))->toBe([])
                    ->and(str_replace($sendsModel ? 'SECRETMODEL' : '', '', json_encode($payload, JSON_THROW_ON_ERROR)))->not->toContain('SECRET');

                if ($check) {
                    expect($payload)->not->toHaveKeys(['text', 'answer']);
                }

                if ($exercise->format === Format::ListenType) {
                    expect($payload)->not->toHaveKeys(['text', 'answer', 'english']);
                }
            }
        }
    });
});

it('sends no audio of a spoken answer with a model answer in a check, and does not look its clip up', function () {
    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    clipFor('la llave');
    clipFor('la llave', SpeechSpeed::Slow);
    $exercise = exerciseOf('recall.speak_answer.la-llave');
    $check = presentedEntry(runWith($this, LessonRunKind::Check, LessonStage::Check, [$exercise]), $exercise)['payload'];
    $lesson = presentedEntry(runWith($this, LessonRunKind::Lesson, LessonStage::Recall, [$exercise]), $exercise)['payload'];

    expect($check)->not->toHaveKeys(['audioUrl', 'audioSlowUrl', 'audioRole', 'text'])
        ->and($lesson['audioUrl'])->toContain('.mp3')
        ->and($lesson['audioRole'])->toBe('model');
});

describe('a lesson', function () {
    it('never sends the text or the accepted answers of a dictation', function () {
        $exercise = exerciseOf('sentences.listen_type.reserva');
        $run = runWith($this, LessonRunKind::Lesson, LessonStage::Sentences, [$exercise]);

        $payload = presentedEntry($run, $exercise)['payload'];

        expect($payload)->not->toHaveKeys(['text', 'accepted'])
            ->and(json_encode($payload, JSON_THROW_ON_ERROR))->not->toContain('reserva es para');
    });

    it('keeps the text of a word heard and chosen next to its clips, since its answer is among the options', function () {
        $exercise = exerciseOf('meet.listen_choose.el-hotel');
        $run = runWith($this, LessonRunKind::Lesson, LessonStage::Meet, [$exercise]);
        clipFor('el hotel');
        clipFor('el hotel', SpeechSpeed::Slow);

        $payload = presentedEntry($run, $exercise)['payload'];

        expect($payload['text'])->toBe('el hotel')
            ->and($payload['audioUrl'])->toContain('.mp3')
            ->and($payload['audioSlowUrl'])->toContain('.mp3')
            ->and($payload['audioRole'])->toBe('prompt')
            ->and($payload['answer'])->toBe('hotel');
    });

    it('shows the text of a phrase to repeat, but not what is behind its grading', function () {
        $exercise = exerciseOf('sentences.speak_repeat.hay');
        $run = runWith($this, LessonRunKind::Lesson, LessonStage::Sentences, [$exercise]);

        $payload = presentedEntry($run, $exercise)['payload'];

        expect($payload['text'])->toBe('¿Hay una habitación disponible para dos noches?')
            ->and($payload)->not->toHaveKeys(['accepted', 'slots']);
    });

    it('never sends the model answer of a spoken answer to an English cue, and says its clip plays afterwards', function () {
        $exercise = exerciseOf('recall.speak_answer.la-llave');
        $run = runWith($this, LessonRunKind::Lesson, LessonStage::Recall, [$exercise]);

        $payload = presentedEntry($run, $exercise)['payload'];

        expect($payload)->not->toHaveKeys(['text', 'accepted', 'slots'])
            ->and($payload['prompt'])->toBe('key')
            ->and($payload['audioRole'])->toBe('model');
    });

    it('shows a spoken question and plays it first', function () {
        $exercise = exerciseOf('task.speak_answer.reserva');
        $run = runWith($this, LessonRunKind::Lesson, LessonStage::Task, [$exercise]);

        $payload = presentedEntry($run, $exercise)['payload'];

        expect($payload['prompt'])->toBe('¿Tiene una reserva?')
            ->and($payload['audioRole'])->toBe('prompt')
            ->and($payload)->not->toHaveKey('slots');
    });

    it('sends no audio fields for an exercise that is not heard', function () {
        $exercise = exerciseOf('meet.type_word.el-hotel');
        $run = runWith($this, LessonRunKind::Lesson, LessonStage::Meet, [$exercise]);

        expect(presentedEntry($run, $exercise)['payload'])->not->toHaveKeys(['audioUrl', 'audioRole']);
    });
});

describe('the pause state', function () {
    it('lists the pauses that are still running, and not the ones that have ended', function () {
        $this->freezeTime();
        UserSetting::factory()->for($this->user)->create(['listening_paused_until' => now()->addMinutes(30), 'speaking_paused_until' => now()->subMinute()]);
        $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));

        $pauses = app(PresentLessonRun::class)->handle($run)['settings']['pauses'];

        expect($pauses['listening'])->toBe(now()->addMinutes(30)->toIso8601String())
            ->and($pauses['speaking'])->toBeNull();
    });

    it('lists the reason an exercise was skipped', function () {
        $run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));
        $exercise = LessonExercise::query()->whereIn('id', $run->planExerciseIds())->where('format', 'listen_choose')->firstOrFail();
        LessonWorld::answer($this->user, $run, $exercise, ['skipped' => true, 'skip_reason' => 'paused', 'response' => null]);

        expect(app(PresentLessonRun::class)->handle($run->fresh())['answers'][0]['skipReason'])->toBe('paused');
    });
});

it('sends the clips of the examples on a grammar card', function () {
    clipFor('El hotel está cerca.');
    $card = LessonExercise::query()->where('format', 'teach_grammar')->firstOrFail();
    $run = runWith($this, LessonRunKind::Lesson, LessonStage::Recall, [$card]);

    $examples = presentedEntry($run, $card)['payload']['examples'];

    expect($examples[0]['audioUrl'])->toContain('.mp3')
        ->and($examples[1]['audioUrl'])->toBeNull();
});

it('keeps the substitute of a skippable exercise in the plan', function () {
    $exercise = exerciseOf('meet.listen_choose.el-hotel');
    $run = runWith($this, LessonRunKind::Lesson, LessonStage::Meet, [$exercise]);

    expect(presentedEntry($run, $exercise)['substitute']['format'])->toBe('choose_meaning')
        ->and(Lesson::query()->count())->toBe(5)
        ->and(ExerciseFamily::Listening->skill()->value)->toBe('listening');
});
