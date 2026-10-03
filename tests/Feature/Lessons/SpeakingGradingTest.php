<?php

declare(strict_types=1);

use App\Actions\Lessons\GradeLessonAnswer;
use App\Actions\Lessons\ScoreSpeakingTry;
use App\Actions\Lessons\StartLessonRun;
use App\Actions\Lessons\SummarizeLessonRun;
use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonStage;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\User;
use App\Services\SpanishTextNormalizer;
use App\Services\TranscriptScorer;
use Illuminate\Support\Str;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    [$this->unit] = LessonWorld::seededHotel();
    $this->user = LessonWorld::learner();
});

function loaded(string $key): LessonExercise
{
    return LessonExercise::query()->with(['lesson.unit.language', 'targets', 'grammarPoints'])->where('key', $key)->firstOrFail();
}

/** @return array<string, mixed> */
function spoken(string $key, string $transcript): array
{
    return (new ScoreSpeakingTry)->handle(loaded($key), $transcript);
}

describe('a phrase repeated', function () {
    it('passes a transcript that has every word and marks each word', function () {
        $scored = spoken('sentences.speak_repeat.hay', 'hay una habitación disponible para dos noches');

        expect($scored['score'])->toBe(100.0)
            ->and($scored['correct'])->toBeTrue()
            ->and(array_unique(array_column($scored['words'], 'verdict')))->toBe(['exact'])
            ->and($scored['missed'])->toBe(0)
            ->and($scored['heard'])->toBe('hay una habitación disponible para dos noches');
    });

    it('forgives an accent the recogniser left out, and marks it', function () {
        $scored = spoken('sentences.speak_repeat.hay', 'hay una habitacion disponible para dos noches');

        expect($scored['correct'])->toBeTrue()
            ->and(collect($scored['words'])->firstWhere('word', 'habitación')['verdict'])->toBe('accent');
    });

    it('fails a word that is another word with the accent, a wrong word and a missed one', function () {
        $other = spoken('meet.speak_repeat.el-bano', 'el bano');
        $wrong = spoken('sentences.speak_repeat.hay', 'hay una llave disponible para dos noches');
        $missed = spoken('sentences.speak_repeat.hay', 'hay una habitación para dos noches');

        expect($other['correct'])->toBeFalse()
            ->and(collect($other['words'])->firstWhere('word', 'baño')['verdict'])->toBe('wrong')
            ->and(collect($wrong['words'])->firstWhere('word', 'habitación')['verdict'])->toBe('wrong')
            ->and($wrong['score'])->toBe(85.7)
            ->and($wrong['correct'])->toBeTrue()
            ->and(collect($missed['words'])->firstWhere('word', 'disponible')['verdict'])->toBe('missed')
            ->and($missed['missed'])->toBe(1);
    });

    it('needs 80 percent of the words for a sentence', function () {
        $scored = spoken('sentences.speak_repeat.hay', 'hay una llave libre para dos noches');

        expect($scored['score'])->toBe(71.4)
            ->and($scored['correct'])->toBeFalse();
    });

    it('only asks a single word to be present, wherever the recogniser put it', function () {
        $scored = spoken('meet.speak_repeat.disponible', 'tengo disponible hoy');

        expect($scored['score'])->toBe(100.0)
            ->and($scored['correct'])->toBeTrue()
            ->and(spoken('meet.speak_repeat.disponible', 'hola')['correct'])->toBeFalse();
    });

    it('scores nothing for a transcript with no words', function () {
        $scored = spoken('sentences.speak_repeat.hay', '');

        expect($scored['score'])->toBe(0.0)
            ->and($scored['correct'])->toBeFalse()
            ->and($scored['missed'])->toBe(7);
    });
});

describe('a spoken answer', function () {
    it('passes on its keyword slots as whole words, and names only the keywords it heard', function () {
        $scored = spoken('task.speak_answer.reserva', 'sí tengo una reserva');

        expect($scored['score'])->toBe(100.0)
            ->and($scored['correct'])->toBeTrue()
            ->and(array_column($scored['words'], 'word'))->toBe(['tengo', 'reserva'])
            ->and($scored['missed'])->toBe(0);
    });

    it('does not name the keyword it missed', function () {
        $scored = spoken('task.speak_answer.reserva', 'tengo una habitación');

        expect($scored['score'])->toBe(50.0)
            ->and($scored['correct'])->toBeFalse()
            ->and(array_column($scored['words'], 'word'))->toBe(['tengo'])
            ->and($scored['missed'])->toBe(1);
    });

    it('does not match a keyword inside a longer word', function () {
        expect(spoken('task.speak_answer.reserva', 'reservas')['words'])->toBe([]);
    });

    it('matches a keyword of several words in order, and only whole', function () {
        expect(spoken('recall.speak_answer.la-llave', 'la llave')['correct'])->toBeTrue()
            ->and(spoken('recall.speak_answer.la-llave', 'llave la')['correct'])->toBeFalse()
            ->and(spoken('recall.speak_answer.la-llave', 'la')['correct'])->toBeFalse();
    });

    it('is never correct when it has no slots', function () {
        $exercise = LessonExercise::factory()->create([
            'lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Task)->id,
            'format' => Format::SpeakAnswer,
            'payload' => ['prompt' => 'x', 'slots' => []],
        ]);

        expect((new ScoreSpeakingTry)->handle(LessonExercise::query()->with('lesson.unit.language')->findOrFail($exercise->id), 'hola')['correct'])->toBeFalse();
    });
});

describe('scoring one try', function () {
    it('refuses an exercise that is not spoken', function () {
        (new ScoreSpeakingTry)->handle(loaded('meet.type_word.el-hotel'), 'hotel');
    })->throws(LogicException::class);

    it('repeats a text that has no accepted list of its own', function () {
        $exercise = LessonExercise::factory()->create([
            'lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Meet)->id,
            'format' => Format::SpeakRepeat,
            'payload' => ['text' => 'la llave'],
        ]);

        $scored = (new ScoreSpeakingTry)->handle(LessonExercise::query()->with('lesson.unit.language')->findOrFail($exercise->id), 'la llave');

        expect($scored['correct'])->toBeTrue();
    });

    it('refuses a repeat that has no text', function () {
        $exercise = LessonExercise::factory()->create([
            'lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Meet)->id,
            'format' => Format::SpeakRepeat,
            'payload' => ['english' => 'x'],
        ]);

        (new ScoreSpeakingTry)->handle(LessonExercise::query()->with('lesson.unit.language')->findOrFail($exercise->id), 'x');
    })->throws(LogicException::class);

    it('knows the model answer of each kind of exercise', function () {
        $score = new ScoreSpeakingTry;

        expect($score->model(loaded('sentences.speak_repeat.hay')))->toBe('¿Hay una habitación disponible para dos noches?')
            ->and($score->model(loaded('recall.speak_answer.la-llave')))->toBe('la llave')
            ->and($score->model(loaded('task.speak_answer.reserva')))->toBe('tengo reserva');

        $modelled = LessonExercise::factory()->create(['format' => Format::SpeakAnswer, 'payload' => ['prompt' => 'x', 'model' => 'Sí, tengo una reserva.']]);
        $accepted = LessonExercise::factory()->create(['format' => Format::SpeakAnswer, 'payload' => ['prompt' => 'x', 'accepted' => [['text' => 'Hola', 'spans' => []]]]]);

        expect($score->model($modelled))->toBe('Sí, tengo una reserva.')
            ->and($score->model($accepted))->toBe('Hola');
    });
});

describe('TranscriptScorer', function () {
    it('finds a keyword form as whole words, forgiving a dropped accent unless it changes the word', function () {
        $found = (new TranscriptScorer)->keywords(new SpanishTextNormalizer, [['está'], ['él'], ['habitación']], 'esta el habitacion');

        expect($found)->toBe([null, null, 'habitación']);
    });

    it('gives a percentage of whole numbers rounded to one decimal, and zero for nothing', function () {
        $scorer = new TranscriptScorer;

        expect($scorer->percentage(1, 3))->toBe(33.3)
            ->and($scorer->percentage(0, 0))->toBe(0.0);
    });

    it('finds nothing for a slot with no words', function () {
        expect((new TranscriptScorer)->keywords(new SpanishTextNormalizer, [['']], 'hola'))->toBe([null]);
    });
});

describe('grading a spoken answer', function () {
    function gradeSpoken(string $key, array $transcripts): object
    {
        return (new GradeLessonAnswer)->handle(loaded($key), ['transcripts' => $transcripts]);
    }

    it('takes the best of up to three tries', function () {
        $grade = gradeSpoken('sentences.speak_repeat.hay', ['hay una llave libre para dos noches', 'hay una habitación disponible para dos noches', 'nada']);

        expect($grade->correct)->toBeTrue()
            ->and($grade->score)->toBe(100.0)
            ->and($grade->expected)->toBe('¿Hay una habitación disponible para dos noches?')
            ->and($grade->errorTag)->toBeNull()
            ->and($grade->targets)->toHaveCount(3)
            ->and(array_unique(array_column($grade->targets, 'correct')))->toBe([true]);
    });

    it('ignores a fourth try', function () {
        $grade = gradeSpoken('sentences.speak_repeat.hay', ['a', 'b', 'c', 'hay una habitación disponible para dos noches']);

        expect($grade->correct)->toBeFalse();
    });

    it('is wrong, and has no tag, when no try came close', function () {
        $grade = gradeSpoken('sentences.speak_repeat.hay', ['nada', 'otra cosa']);

        expect($grade->correct)->toBeFalse()
            ->and($grade->score)->toBeLessThan(80)
            ->and($grade->errorTag)->toBeNull()
            ->and(array_unique(array_column($grade->targets, 'correct')))->toBe([false]);
    });

    it('is wrong when there is no try at all', function () {
        $grade = (new GradeLessonAnswer)->handle(loaded('sentences.speak_repeat.hay'), []);

        expect($grade->correct)->toBeFalse()
            ->and($grade->score)->toBe(0.0);
    });
});

describe('over HTTP', function () {
    beforeEach(function () {
        $this->run = (new StartLessonRun)->handle($this->user, LessonWorld::lesson($this->unit, LessonStage::Meet));
        $this->speak = LessonExercise::query()->whereIn('id', $this->run->planExerciseIds())->where('format', 'speak_repeat')->firstOrFail();
        $this->tryUrl = fn ($exercise = null): string => route('lesson-runs.speaking-tries.store', [$this->run, $exercise ?? $this->speak]);
    });

    it('scores a try and records nothing', function () {
        $term = $this->speak->payload['text'];

        $this->actingAs($this->user)->postJson(($this->tryUrl)(), ['transcript' => $term])
            ->assertOk()
            ->assertJsonPath('correct', true)
            ->assertJsonPath('score', 100)
            ->assertJsonPath('heard', $term);

        expect(LessonAnswer::query()->count())->toBe(0);
    });

    it('needs a transcript', function () {
        $this->actingAs($this->user)->postJson(($this->tryUrl)(), [])->assertUnprocessable()->assertJsonValidationErrors('transcript');
        $this->actingAs($this->user)->postJson(($this->tryUrl)(), ['transcript' => str_repeat('a', 501)])->assertUnprocessable()->assertJsonValidationErrors('transcript');
    });

    it('answers 404 for another learner\'s run, for an exercise that is not spoken and for one outside the plan', function () {
        $typed = LessonExercise::query()->whereIn('id', $this->run->planExerciseIds())->where('format', 'type_word')->firstOrFail();
        $outside = LessonExercise::query()->where('key', 'task.speak_answer.reserva')->firstOrFail();

        $this->actingAs(User::factory()->create())->postJson(($this->tryUrl)(), ['transcript' => 'x'])->assertNotFound();
        $this->actingAs($this->user)->postJson(($this->tryUrl)($typed), ['transcript' => 'x'])->assertNotFound();
        $this->actingAs($this->user)->postJson(($this->tryUrl)($outside), ['transcript' => 'x'])->assertNotFound();
    });

    it('requires authentication', function () {
        $this->postJson(($this->tryUrl)(), ['transcript' => 'x'])->assertUnauthorized();
    });

    it('grades the answer sent with its transcripts and records the best score', function () {
        $term = $this->speak->payload['text'];

        $this->actingAs($this->user)->postJson(route('lesson-runs.answers.store', [$this->run, (string) Str::uuid()]), [
            'exercise_id' => $this->speak->id,
            'response' => ['transcripts' => ['nada', $term]],
        ])
            ->assertOk()
            ->assertJsonPath('correct', true)
            ->assertJsonPath('score', 100)
            ->assertJsonPath('expected', $term);

        $answer = LessonAnswer::query()->sole();

        expect($answer->is_correct)->toBeTrue()
            ->and($answer->response)->toBe(['transcripts' => ['nada', $term]]);
    });

    it('refuses more than three transcripts', function () {
        $this->actingAs($this->user)->postJson(route('lesson-runs.answers.store', [$this->run, (string) Str::uuid()]), [
            'exercise_id' => $this->speak->id,
            'response' => ['transcripts' => ['a', 'b', 'c', 'd']],
        ])->assertUnprocessable()->assertJsonValidationErrors('response.transcripts');
    });
});

it('shows the last spoken try and the model answer in the summary of a check', function () {
    LessonWorld::finishTeachingLessons($this->user, $this->unit);
    $check = LessonRun::factory()->create([
        'user_id' => $this->user->id,
        'lesson_id' => LessonWorld::lesson($this->unit, LessonStage::Check)->id,
        'kind' => 'check',
        'status' => 'completed',
        'plan' => [],
    ]);
    $exercise = LessonExercise::query()->where('key', 'check.a.speak_answer')->firstOrFail();
    LessonAnswer::factory()->create(['lesson_run_id' => $check->id, 'lesson_exercise_id' => $exercise->id, 'response' => ['transcripts' => ['nada', 'tengo una reserva']], 'is_correct' => true]);
    $repeat = LessonExercise::query()->where('key', 'sentences.speak_repeat.hay')->firstOrFail();
    LessonAnswer::factory()->create(['lesson_run_id' => $check->id, 'lesson_exercise_id' => $repeat->id, 'response' => ['transcripts' => []], 'is_correct' => false]);
    $model = LessonExercise::query()->where('key', 'recall.speak_answer.la-llave')->firstOrFail();
    LessonAnswer::factory()->create(['lesson_run_id' => $check->id, 'lesson_exercise_id' => $model->id, 'response' => ['transcripts' => ['la llave']], 'is_correct' => true]);
    $heard = LessonExercise::query()->where('key', 'meet.listen_choose.el-hotel')->firstOrFail();
    LessonAnswer::factory()->create(['lesson_run_id' => $check->id, 'lesson_exercise_id' => $heard->id, 'response' => ['choice' => 'hotel'], 'is_correct' => true]);

    $answers = collect((new SummarizeLessonRun)->handle($check)['answers']);

    expect($answers->firstWhere('given', 'tengo una reserva')['correct'])->toBeTrue()
        ->and($answers->firstWhere('expected', '¿Hay una habitación disponible para dos noches?')['given'])->toBe('')
        ->and($answers->firstWhere('given', 'la llave')['expected'])->toBe('la llave')
        ->and($answers->firstWhere('given', 'hotel')['expected'])->toBe('hotel');
});
