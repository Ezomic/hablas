<?php

declare(strict_types=1);

use App\Actions\Lessons\AlignAnswer;
use App\Actions\Lessons\GradeLessonAnswer;
use App\Enums\AccentVerdict;
use App\Enums\ErrorTagCategory;
use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonStage;
use App\Lessons\Grade;
use App\Lessons\TargetRef;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\Lesson;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\Unit;
use App\Models\VocabularyItem;
use App\Services\AccentComparer;
use App\Services\FirstTryRule;
use App\Services\PortugueseTextNormalizer;
use App\Services\SpanishTextNormalizer;
use Illuminate\Support\Facades\DB;
use Tests\Fixtures\Lessons\LessonWorld;

function seededExercise(string $key): LessonExercise
{
    return LessonExercise::query()->with(['lesson.unit.language', 'targets', 'grammarPoints'])->where('key', $key)->firstOrFail();
}

/**
 * @param  list<string>  $accepted
 * @param  array<string, array{int, int}>  $spans  by target key
 */
function typedExercise(Language $language, LessonStage $stage, array $accepted, array $spans = [], Format $format = Format::TypeWord, array $extra = []): LessonExercise
{
    $unit = Unit::factory()->create(['language_id' => $language->id]);
    $lesson = Lesson::factory()->stage($stage)->create(['unit_id' => $unit->id]);

    return LessonExercise::factory()->create([
        'lesson_id' => $lesson->id,
        'format' => $format,
        'payload' => ['prompt' => 'x', 'accepted' => array_map(fn (string $text): array => ['text' => $text, 'spans' => $spans], $accepted), ...$extra],
    ]);
}

function gradeText(LessonExercise $exercise, string $text): Grade
{
    return (new GradeLessonAnswer)->handle(LessonExercise::query()->with(['lesson.unit.language', 'targets', 'grammarPoints'])->findOrFail($exercise->id), ['text' => $text]);
}

describe('AccentComparer', function () {
    it('judges one typed word against the expected one', function (string $language, string $expected, string $given, ?AccentVerdict $verdict) {
        $normalizer = $language === 'es' ? new SpanishTextNormalizer : new PortugueseTextNormalizer;

        expect((new AccentComparer)->compare($normalizer, $expected, $given))->toBe($verdict);
    })->with([
        'exact' => ['es', 'habitación', 'Habitación', AccentVerdict::Exact],
        'missing accent' => ['es', 'habitación', 'habitacion', AccentVerdict::Missing],
        'missing accent that makes another word' => ['es', 'está', 'esta', AccentVerdict::OtherWord],
        'él for el' => ['es', 'él', 'el', AccentVerdict::OtherWord],
        'tú for tu' => ['es', 'tú', 'tu', AccentVerdict::OtherWord],
        'wrong accent mark' => ['pt', 'avó', 'avô', AccentVerdict::OtherWord],
        'avó without accent' => ['pt', 'avó', 'avo', AccentVerdict::OtherWord],
        'é for e' => ['pt', 'é', 'e', AccentVerdict::OtherWord],
        'an accent that is not there' => ['es', 'hotel', 'hotél', AccentVerdict::OtherWord],
        'ñ is never forgiven' => ['es', 'año', 'ano', null],
        'nasal marks are never forgiven' => ['pt', 'pão', 'pao', null],
        'ç is never forgiven' => ['pt', 'maçã', 'maca', null],
        'a different word' => ['es', 'baño', 'llave', null],
        'país is not pais' => ['pt', 'país', 'pais', AccentVerdict::OtherWord],
        'dá is not da' => ['pt', 'dá', 'da', AccentVerdict::OtherWord],
        'nós is not nos' => ['pt', 'nós', 'nos', AccentVerdict::OtherWord],
        'às is not as' => ['pt', 'às', 'as', AccentVerdict::OtherWord],
        'pôde is not pode' => ['pt', 'pôde', 'pode', AccentVerdict::OtherWord],
        'habló is not hablo' => ['es', 'habló', 'hablo', AccentVerdict::OtherWord],
        'aún is not aun' => ['es', 'aún', 'aun', AccentVerdict::OtherWord],
    ]);

    it('is wrong in the check when the accent that tells two words apart is dropped', function (string $code, string $expected, string $given) {
        $language = $code === 'es' ? LessonWorld::spanish() : Language::factory()->create(['code' => $code]);

        $exercise = typedExercise($language, LessonStage::Check, [$expected]);

        expect(gradeText($exercise, $expected)->correct)->toBeTrue()
            ->and(gradeText($exercise, $given)->correct)->toBeFalse();
    })->with([
        ['pt', 'o país', 'o pais'],
        ['pt', 'dá', 'da'],
        ['pt', 'nós', 'nos'],
        ['pt', 'às', 'as'],
        ['pt', 'pôde', 'pode'],
        ['es', 'habló', 'hablo'],
        ['es', 'aún', 'aun'],
    ]);

    it('forgives a dropped accent that makes another word in every lesson, with the note, and fails it in the check', function () {
        $spanish = LessonWorld::spanish();

        foreach ([LessonStage::Meet, LessonStage::Recall, LessonStage::Sentences, LessonStage::Task] as $stage) {
            $forgiven = gradeText(typedExercise($spanish, $stage, ['¿cómo estás?']), '¿cómo estas?');

            expect($forgiven->correct)->toBeTrue($stage->value)
                ->and($forgiven->note)->toBe('other_word');
        }

        expect(gradeText(typedExercise($spanish, LessonStage::Check, ['¿cómo estás?']), '¿cómo estas?')->correct)->toBeFalse();
    });

    it('forgives every dropped accent of a dictation in a lesson, as the audio gives none', function () {
        $exercise = typedExercise(LessonWorld::spanish(), LessonStage::Sentences, ['Buenas tardes, Pablo. ¿Cómo estás?'], format: Format::ListenType);

        expect(gradeText($exercise, 'buenas tardes pablo como estas')->correct)->toBeTrue();
    });

    it('still fails a wrong accent mark, or an accent that is not there, in a lesson', function (string $code, string $expected, string $given) {
        $language = $code === 'es' ? LessonWorld::spanish() : Language::factory()->create(['code' => $code]);

        expect(gradeText(typedExercise($language, LessonStage::Meet, [$expected]), $given)->correct)->toBeFalse();
    })->with([
        ['pt', 'avó', 'avô'],
        ['es', 'hotel', 'hotél'],
    ]);

    it('keeps accents in the exact key and folds them in the answer key', function () {
        $normalizer = new SpanishTextNormalizer;

        expect($normalizer->exactKey('¿Cómo está, Ana?'))->toBe('cómo está ana')
            ->and($normalizer->answerKey('¿Cómo está, Ana?'))->toBe('como esta ana')
            ->and($normalizer->exactKey("hab\u{0069}taci\u{006F}\u{0301}n"))->toBe('habitación')
            ->and($normalizer->articles())->toContain('el', 'la')
            ->and((new PortugueseTextNormalizer)->accentWords())->toContain('é', 'avó');
    });
});

describe('AlignAnswer', function () {
    it('lines the words up with the closest accepted answer and pins a wrong word to its place', function () {
        $alignment = (new AlignAnswer)->handle(new SpanishTextNormalizer, 'La habitacion esta disponible', ['El baño está aquí', 'La habitación está disponible']);

        expect($alignment->acceptedIndex)->toBe(1)
            ->and(array_map(fn ($word): ?AccentVerdict => $word->verdict, $alignment->words))->toBe([AccentVerdict::Exact, AccentVerdict::Missing, AccentVerdict::OtherWord, AccentVerdict::Exact])
            ->and($alignment->extra)->toBe([]);
    });

    it('reports a missing word and an extra word', function () {
        $missing = (new AlignAnswer)->handle(new SpanishTextNormalizer, 'El está incluido', ['El desayuno está incluido']);
        $extra = (new AlignAnswer)->handle(new SpanishTextNormalizer, 'El desayuno está incluido muy', ['El desayuno está incluido']);

        expect(array_map(fn ($word): ?string => $word->given, $missing->words))->toBe(['el', null, 'está', 'incluido'])
            ->and($extra->extra)->toBe(['muy']);
    });

    it('needs an accepted answer', function () {
        (new AlignAnswer)->handle(new SpanishTextNormalizer, 'x', []);
    })->throws(InvalidArgumentException::class);

    it('aligns an empty response against everything missing', function () {
        $alignment = (new AlignAnswer)->handle(new SpanishTextNormalizer, '', ['la llave']);

        expect(array_map(fn ($word): ?string => $word->given, $alignment->words))->toBe([null, null]);
    });
});

describe('accents by stage', function () {
    beforeEach(function () {
        [$this->unit] = LessonWorld::seededHotel();
    });

    it('forgives a missing accent in lessons 1 and 2 and counts it as right first time', function () {
        $exercise = seededExercise('recall.type_word.la-habitacion');
        $grade = gradeText($exercise, 'la habitacion');

        $answer = LessonAnswer::factory()->make(['attempt' => 1, 'is_correct' => $grade->correct, 'note' => $grade->note]);
        $answer->setRelation('lessonExercise', $exercise);

        expect($grade->correct)->toBeTrue()
            ->and($grade->note)->toBe('accent')
            ->and((new FirstTryRule)->rightFirstTime($answer))->toBeTrue();
    });

    it('forgives a missing accent in lessons 3 and 4 too, with the note, and counts it as right first time', function () {
        $exercise = seededExercise('task.transform.plural');
        $grade = gradeText($exercise, 'Las habitaciones están disponibles');
        $slip = gradeText($exercise, 'Las habitaciones estan disponibles');

        $answer = LessonAnswer::factory()->make(['attempt' => 1, 'is_correct' => true, 'note' => 'accent']);
        $answer->setRelation('lessonExercise', $exercise);

        expect($grade->correct)->toBeTrue()
            ->and($grade->note)->toBeNull()
            ->and($slip->correct)->toBeTrue()
            ->and($slip->note)->toBe('accent')
            ->and((new FirstTryRule)->rightFirstTime($answer))->toBeTrue();
    });

    it('marks a missing accent wrong in the check, and only on the word that slipped', function () {
        $exercise = seededExercise('check.a.translate.0');
        $grade = gradeText($exercise, 'El baño está en la habitacion');
        $byKey = collect($grade->targets)->mapWithKeys(fn (array $target): array => [$target['type'].':'.$target['id'] => $target['correct']]);

        $baño = TargetRef::for(VocabularyItem::query()->where('term', 'el baño')->firstOrFail())->key();
        $habitacion = TargetRef::for(VocabularyItem::query()->where('term', 'la habitación')->firstOrFail())->key();

        expect($grade->correct)->toBeFalse()
            ->and($grade->note)->toBe('accent')
            ->and($byKey[$baño])->toBeTrue()
            ->and($byKey[$habitacion])->toBeFalse();
    });

    it('forgives esta for está in a lesson, with the note, and fails only the grammar point in the check', function () {
        $lesson = gradeText(seededExercise('sentences.translate.desayuno'), 'El desayuno esta incluido');

        expect($lesson->correct)->toBeTrue()
            ->and($lesson->note)->toBe('other_word');

        $check = gradeText(seededExercise('check.a.translate.0'), 'El baño esta en la habitación');
        $wrong = collect($check->targets)->where('correct', false);

        expect($check->correct)->toBeFalse()
            ->and($check->note)->toBe('other_word')
            ->and($wrong)->toHaveCount(1)
            ->and($wrong->first()['type'])->toBe((new GrammarPoint)->getMorphClass());
    });

    it('fails only the misspelt word', function () {
        $exercise = seededExercise('sentences.translate.desayuno');
        $grade = gradeText($exercise, 'El desayuno está inclduido');
        $wrong = collect($grade->targets)->where('correct', false);

        expect($grade->correct)->toBeFalse()
            ->and($wrong)->toHaveCount(1)
            ->and($wrong->first()['id'])->toBe(VocabularyItem::query()->where('term', 'incluido')->firstOrFail()->id);
    });

    it('tags a wrong grammar span with the grammar point\'s own category', function () {
        $grade = gradeText(seededExercise('sentences.translate.desayuno'), 'El desayuno es incluido');

        expect($grade->correct)->toBeFalse()
            ->and($grade->errorTag)->toBe(ErrorTagCategory::SerEstarConfusion);
    });

    it('tags nothing for a right answer or for a wrong word that is not grammar', function () {
        expect(gradeText(seededExercise('sentences.translate.desayuno'), 'El desayuno está incluido')->errorTag)->toBeNull()
            ->and(gradeText(seededExercise('sentences.translate.desayuno'), 'El desayuno está inclduido')->errorTag)->toBeNull();
    });
});

describe('articles', function () {
    beforeEach(function () {
        LessonWorld::seededHotel();
    });

    it('requires the article from lesson 2 and tags the wrong gender', function () {
        $grade = gradeText(seededExercise('recall.type_word.el-desayuno'), 'la desayuno');

        expect($grade->correct)->toBeFalse()
            ->and($grade->note)->toBe('article')
            ->and($grade->errorTag)->toBe(ErrorTagCategory::WrongGender)
            ->and($grade->expected)->toBe('el desayuno');
    });

    it('fails a bare noun from lesson 2 on, but accepts it in lesson 1', function () {
        expect(gradeText(seededExercise('recall.type_word.el-desayuno'), 'desayuno')->correct)->toBeFalse()
            ->and(gradeText(seededExercise('meet.type_word.el-desayuno'), 'desayuno')->correct)->toBeTrue()
            ->and(gradeText(seededExercise('meet.type_word.el-desayuno'), 'la desayuno')->correct)->toBeFalse();
    });

    it('accepts both articles for a common-gender noun', function () {
        $exercise = seededExercise('recall.type_word.el-recepcionista');

        expect(gradeText($exercise, 'el recepcionista')->correct)->toBeTrue()
            ->and(gradeText($exercise, 'la recepcionista')->correct)->toBeTrue()
            ->and(gradeText($exercise, 'los recepcionista')->correct)->toBeFalse();
    });
});

describe('Portuguese', function () {
    it('treats avô as another word than avó, and pao as another than pão', function () {
        $pt = Language::factory()->create(['code' => 'pt']);
        $exercise = typedExercise($pt, LessonStage::Recall, ['a avó']);

        expect(gradeText($exercise, 'a avô')->correct)->toBeFalse()
            ->and(gradeText($exercise, 'a avô')->note)->toBe('other_word')
            ->and(gradeText($exercise, 'a avó')->correct)->toBeTrue();

        $pao = typedExercise($pt, LessonStage::Recall, ['o pão']);

        expect(gradeText($pao, 'o pao')->correct)->toBeFalse()
            ->and(gradeText($pao, 'o pão')->correct)->toBeTrue();
    });

    it('does not accept an extra typed word, in a lesson or a check', function (LessonStage $stage) {
        $exercise = typedExercise(LessonWorld::spanish(), $stage, ['el desayuno']);

        expect(gradeText($exercise, 'el desayuno')->correct)->toBeTrue()
            ->and(gradeText($exercise, 'el desayuno incluido')->correct)->toBeFalse()
            ->and(gradeText($exercise, 'muy el desayuno')->correct)->toBeFalse();
    })->with([LessonStage::Recall, LessonStage::Check]);

    it('treats ano as another word than año in Spanish', function () {
        $exercise = typedExercise(LessonWorld::spanish(), LessonStage::Recall, ['el año']);

        expect(gradeText($exercise, 'el ano')->correct)->toBeFalse();
    });

    it('tags a declared Spanish form as a Portunol slip', function () {
        $pt = Language::factory()->create(['code' => 'pt']);
        $exercise = typedExercise($pt, LessonStage::Recall, ['a esquerda'], extra: ['portunol_slips' => ['izquierda']]);
        $grade = gradeText($exercise, 'a izquierda');

        expect($grade->correct)->toBeFalse()
            ->and($grade->note)->toBe('portunol')
            ->and($grade->errorTag)->toBe(ErrorTagCategory::PortunolSlip);
    });

    it('does not tag a different mistake as a Portunol slip', function () {
        $pt = Language::factory()->create(['code' => 'pt']);
        $exercise = typedExercise($pt, LessonStage::Recall, ['a esquerda'], extra: ['portunol_slips' => ['izquierda']]);

        expect(gradeText($exercise, 'a direita')->errorTag)->toBeNull();
    });
});

describe('other formats', function () {
    beforeEach(function () {
        LessonWorld::seededHotel();
    });

    it('grades a choice by the chosen option', function () {
        $exercise = seededExercise('recall.choose_gap.bano-aqui');

        $right = (new GradeLessonAnswer)->handle($exercise, ['choice' => 'está']);
        $wrong = (new GradeLessonAnswer)->handle($exercise, ['choice' => 'es']);

        expect($right->correct)->toBeTrue()
            ->and($wrong->correct)->toBeFalse()
            ->and($wrong->expected)->toBe('está')
            ->and($wrong->errorTag)->toBe(ErrorTagCategory::SerEstarConfusion)
            ->and($right->targets[0]['correct'])->toBeTrue();
    });

    it('is right first time in matching only with no wrong pair, and pins the wrong ones', function () {
        $exercise = seededExercise('meet.match_pairs.1');
        $keys = collect($exercise->targets)->map(fn ($target): string => TargetRef::keyFor($target->targetable_type, $target->targetable_id));

        $clean = (new GradeLessonAnswer)->handle($exercise, ['wrong' => []]);
        $slip = (new GradeLessonAnswer)->handle($exercise, ['wrong' => [$keys[0]]]);

        expect($clean->correct)->toBeTrue()
            ->and($slip->correct)->toBeFalse()
            ->and(collect($slip->targets)->where('correct', false))->toHaveCount(1);
    });

    it('scores a passage by its questions', function () {
        $exercise = seededExercise('task.read_passage.reception');

        $right = (new GradeLessonAnswer)->handle($exercise, ['choices' => ['two']]);
        $wrong = (new GradeLessonAnswer)->handle($exercise, ['choices' => ['three']]);

        expect($right->correct)->toBeTrue()
            ->and($right->score)->toBe(100.0)
            ->and($wrong->correct)->toBeFalse()
            ->and($wrong->score)->toBe(0.0);
    });

    it('matches guided writing by whole forms, not stems', function () {
        $exercise = seededExercise('task.write_guided.room');

        $full = (new GradeLessonAnswer)->handle($exercise, ['text' => 'Quiero una habitación para dos noches con desayuno']);
        $stems = (new GradeLessonAnswer)->handle($exercise, ['text' => 'habita noch desay inclu']);
        $partial = (new GradeLessonAnswer)->handle($exercise, ['text' => 'Una habitacion para dos noches con desayuno']);

        expect($full->correct)->toBeFalse()
            ->and($full->score)->toBe(75.0)
            ->and($stems->score)->toBe(0.0)
            ->and($partial->note)->toBe('accent');
    });

    it('treats a teach card as right whatever is sent', function () {
        $grade = (new GradeLessonAnswer)->handle(seededExercise('meet.teach_word.la-llave'), []);

        expect($grade->correct)->toBeTrue()
            ->and($grade->targets)->toHaveCount(1);
    });

    it('refuses an exact-match exercise that has no accepted answers', function () {
        $exercise = typedExercise(LessonWorld::spanish(), LessonStage::Recall, []);

        (new GradeLessonAnswer)->handle(LessonExercise::query()->with(['lesson.unit.language', 'targets'])->findOrFail($exercise->id), ['text' => 'x']);
    })->throws(LogicException::class);
});

describe('targets without a span', function () {
    it('takes the verdict of the whole exercise for a target no accepted answer names', function () {
        $exercise = typedExercise(LessonWorld::spanish(), LessonStage::Recall, ['la llave']);
        $item = VocabularyItem::factory()->create();
        DB::table('lesson_exercise_targets')->insert(['lesson_exercise_id' => $exercise->id, 'targetable_type' => $item->getMorphClass(), 'targetable_id' => $item->id, 'is_probe' => false, 'is_contrast' => false]);

        expect(gradeText($exercise, 'la llave')->targets[0]['correct'])->toBeTrue()
            ->and(gradeText($exercise, 'el baño')->targets[0]['correct'])->toBeFalse();
    });

    it('skips an accepted entry that is not an answer', function () {
        $exercise = typedExercise(LessonWorld::spanish(), LessonStage::Recall, ['la llave']);
        $payload = $exercise->payload;
        $payload['accepted'][] = 'stray';
        $exercise->forceFill(['payload' => $payload])->save();

        expect(gradeText($exercise, 'la llave')->correct)->toBeTrue();
    });
});
