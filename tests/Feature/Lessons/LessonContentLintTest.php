<?php

declare(strict_types=1);

use App\Actions\Lessons\BuildLessonPlan;
use App\Actions\Lessons\BuildUnitLessons;
use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Lessons\AnswerSpans;
use App\Lessons\ArticleSwapper;
use App\Lessons\AuthoredExercise;
use App\Lessons\InvalidLessonContent;
use App\Lessons\LessonDefinition;
use App\Lessons\TargetRef;
use App\Lessons\TargetSpec;
use App\Lessons\WordData;
use App\Models\GrammarPoint;
use App\Models\Lesson;
use App\Models\LessonExercise;
use App\Models\Unit;
use App\Models\VocabularyItem;
use App\Services\SpanishTextNormalizer;
use App\Services\UnitContentRegistry;
use Tests\Fixtures\Lessons\ArrayContent;
use Tests\Fixtures\Lessons\LessonWorld;

/** @param  list<AuthoredExercise>  $exercises */
function lint(array $exercises, ?Unit $unit = null, array $words = []): array
{
    return (new BuildUnitLessons)->handle($unit ?? Unit::query()->where('slug', 'checking-into-a-hotel')->first() ?? LessonWorld::hotelUnit(), new ArrayContent(words: $words, exercises: $exercises));
}

function authored(Format $format, array $payload = [], array $accepted = [], array $targets = [], LessonStage $stage = LessonStage::Sentences, string $key = 'k', string $block = 'main'): AuthoredExercise
{
    return new AuthoredExercise($stage, $format, $key, $payload, $accepted, $targets, block: $block);
}

/** @param  list<array{string, string, string}>  $items */
function smallUnit(array $items, bool $grammar = false): Unit
{
    $language = LessonWorld::spanish();
    $unit = Unit::factory()->create(['language_id' => $language->id, 'slug' => 'checking-into-a-hotel']);

    foreach ($items as [$term, $translation, $pos]) {
        VocabularyItem::factory()->create(['language_id' => $language->id, 'unit_id' => $unit->id, 'term' => $term, 'translation_en' => $translation, 'part_of_speech' => $pos]);
    }

    if ($grammar) {
        GrammarPoint::factory()->create(['language_id' => $language->id, 'unit_id' => $unit->id]);
    }

    return $unit;
}

describe('answer keys', function () {
    it('rejects two options that normalise to the same text', function () {
        expect(fn () => lint([authored(Format::ChooseGap, ['options' => ['es', 'Es', 'hay'], 'answer' => 'hay'])]))->toThrow(InvalidLessonContent::class, 'normalise to the same text');
    });

    it('rejects a distractor that is also an accepted answer', function () {
        expect(fn () => lint([authored(Format::ChooseGap, ['options' => ['es', 'está', 'hay'], 'answer' => 'es'], accepted: ['está'])]))
            ->toThrow(InvalidLessonContent::class, "the distractor 'está' is also an accepted answer");
    });

    it('rejects a passage question whose answer is not among its options', function () {
        $payload = ['dialogue' => [], 'questions' => [['prompt' => 'q', 'options' => ['a', 'b'], 'answer' => 'c']]];

        expect(fn () => lint([authored(Format::ReadPassage, $payload, stage: LessonStage::Task)]))->toThrow(InvalidLessonContent::class, "a question's answer is not among its options");
    });

    it('rejects guided writing without required words, or with a required word that has no form', function () {
        expect(fn () => lint([authored(Format::WriteGuided, ['prompt' => 'x'], stage: LessonStage::Task)]))->toThrow(InvalidLessonContent::class, 'needs required words')
            ->and(fn () => lint([authored(Format::WriteGuided, ['prompt' => 'x', 'required' => [['forms' => []]]], stage: LessonStage::Task)]))->toThrow(InvalidLessonContent::class, 'has no accepted form');
    });

    it('turns guided writing\'s required words into whole-form lists with their targets', function () {
        $lessons = lint([authored(Format::WriteGuided, ['prompt' => 'x', 'required' => [['forms' => ['habitación'], 'term' => 'la habitación'], ['forms' => ['noches']]]], stage: LessonStage::Task)]);
        $task = collect($lessons)->first(fn (LessonDefinition $lesson): bool => $lesson->stage === LessonStage::Task);

        expect($task->exercises[0]->payload['required'][0]['target'])->toBe(TargetRef::for(VocabularyItem::query()->where('term', 'la habitación')->firstOrFail())->key())
            ->and($task->exercises[0]->payload['required'][1]['target'])->toBeNull();
    });

    it('needs accepted answers, or a text to take one from', function () {
        expect(fn () => lint([authored(Format::TypeGap, ['prompt' => 'x'])]))->toThrow(InvalidLessonContent::class, 'has no accepted answers');
    });

    it('does not repeat an accepted answer that a variant would duplicate', function () {
        $lessons = lint([authored(Format::TranslateSentence, ['english' => 'x'], accepted: ['Yo tengo una reserva', 'tengo una reserva'], targets: [TargetSpec::word('la reserva', 'reserva')])]);
        $sentences = collect($lessons)->first(fn (LessonDefinition $lesson): bool => $lesson->stage === LessonStage::Sentences);

        expect(collect($sentences->exercises[0]->payload['accepted'])->pluck('text')->all())->toBe(['Yo tengo una reserva', 'tengo una reserva']);
    });

    it('rejects a target that names the grammar point when the unit has none, or an item it lacks', function () {
        $unit = smallUnit([['el hotel', 'hotel', 'noun'], ['la llave', 'key', 'noun']]);

        expect(fn () => lint([authored(Format::TypeGap, accepted: ['está'], targets: [TargetSpec::grammar('está')])], $unit))->toThrow(InvalidLessonContent::class, 'has none')
            ->and(fn () => lint([authored(Format::TypeGap, accepted: ['está'], targets: [TargetSpec::word('el aeropuerto')])], $unit))->toThrow(InvalidLessonContent::class, "no vocabulary item 'el aeropuerto'");
    });

    it('rejects an exercise key used twice in a lesson', function () {
        $one = authored(Format::ChooseGap, ['options' => ['es', 'hay'], 'answer' => 'es']);

        expect(fn () => lint([$one, $one]))->toThrow(InvalidLessonContent::class, "Exercise key 'k' is used twice");
    });

    it('builds a build_sentence without distractor tiles in lesson 2', function () {
        $lessons = lint([authored(Format::BuildSentence, ['english' => 'x'], accepted: ['El baño está aquí'], stage: LessonStage::Recall)]);
        $recall = collect($lessons)->first(fn (LessonDefinition $lesson): bool => $lesson->stage === LessonStage::Recall);
        $tiles = collect($recall->exercises)->first(fn ($exercise): bool => $exercise->format === Format::BuildSentence)->payload['tiles'];

        expect($tiles)->toHaveCount(4);
    });
});

describe('the ramp edge cases', function () {
    it('fails a lesson 1 that tests an item before it has been taught', function () {
        expect(fn () => lint([authored(Format::ChooseGap, ['options' => ['es', 'hay'], 'answer' => 'es'], targets: [TargetSpec::grammar('es')], stage: LessonStage::Meet)]))
            ->toThrow(InvalidLessonContent::class, 'before its teach card');
    });

    it('needs at least two items to make a distractor', function () {
        $unit = smallUnit([['el hotel', 'hotel', 'noun']]);

        expect(fn () => lint([], $unit))->toThrow(InvalidLessonContent::class, 'has no distractors');
    });

    it('folds a last single item into the previous group when matching', function () {
        $items = [];

        foreach (range(1, 11) as $number) {
            $items[] = ["el objeto{$number}", "object {$number}", 'noun'];
        }

        $meet = collect(lint([], smallUnit($items)))->first(fn (LessonDefinition $lesson): bool => $lesson->stage === LessonStage::Meet);
        $matches = collect($meet->exercises)->filter(fn ($exercise): bool => $exercise->format === Format::MatchPairs)->map(fn ($exercise): int => count($exercise->payload['pairs']))->values()->all();

        expect($matches)->toBe([5, 6]);
    });

    it('accepts a noun typed without an article in lesson 1 when it has none to drop', function () {
        $unit = smallUnit([['agua', 'water', 'noun'], ['el vaso', 'glass', 'noun']]);
        $meet = collect(lint([], $unit))->first(fn (LessonDefinition $lesson): bool => $lesson->stage === LessonStage::Meet);
        $typed = collect($meet->exercises)->first(fn ($exercise): bool => $exercise->key === 'meet.type_word.agua');

        expect(collect($typed->payload['accepted'])->pluck('text')->all())->toBe(['agua'])
            ->and($typed->payload['hint'])->toBe('a _ _ _');
    });

    it('does not offer the same meaning twice, or the same term twice, as options', function () {
        $unit = smallUnit([
            ['el cuarto', 'room', 'noun'],
            ['la habitación', 'room', 'noun'],
            ['el hotel', 'hotel', 'noun'],
            ['disponible', 'available', 'adjective'],
        ]);
        $words = [new WordData('el cuarto', cue: 'room (small)'), new WordData('la habitación', cue: 'room (hotel)'), new WordData('disponible', forms: ['disponibles', 'disponibles'])];
        $lessons = collect(lint([], $unit, $words))->keyBy(fn (LessonDefinition $lesson): string => $lesson->stage->value);

        $meaning = collect($lessons['meet']->exercises)->first(fn ($exercise): bool => $exercise->key === 'meet.choose_meaning.el-cuarto');
        $adjective = collect($lessons['recall']->exercises)->first(fn ($exercise): bool => $exercise->key === 'recall.choose_word.disponible');

        expect($meaning->payload['options'])->toHaveCount(3)
            ->and(array_count_values($meaning->payload['options'])['room'])->toBe(1)
            ->and(array_count_values($adjective->payload['options'])['disponibles'])->toBe(1);
    });
});

describe('small helpers', function () {
    it('finds the span of a form, and nothing for a form without words', function () {
        $normalizer = new SpanishTextNormalizer;

        expect((new AnswerSpans)->find($normalizer, 'La llave está aquí', ['a' => 'está aquí']))->toBe(['a' => [2, 2]])
            ->and((new AnswerSpans)->find($normalizer, 'La llave está aquí', ['a' => '¿?']))->toBeNull()
            ->and((new AnswerSpans)->find($normalizer, 'La llave está aquí', ['a' => 'baño']))->toBeNull();
    });

    it('swaps the article of a noun phrase, and nothing else', function () {
        $normalizer = new SpanishTextNormalizer;

        expect((new ArticleSwapper)->swap($normalizer, 'la llave'))->toBe('el llave')
            ->and((new ArticleSwapper)->swap($normalizer, 'llave'))->toBeNull()
            ->and((new ArticleSwapper)->swap($normalizer, 'hola amigo'))->toBeNull();
    });
});

describe('plans without a previous lesson', function () {
    it('gives a lesson with no earlier lesson no warm-up, and refuses to practise nothing', function () {
        [$unit] = LessonWorld::seededHotel();
        $user = LessonWorld::learner();

        Lesson::query()->where('unit_id', $unit->id)->where('stage', LessonStage::Meet)->delete();

        $plan = (new BuildLessonPlan)->handle($user, LessonWorld::lesson($unit, LessonStage::Recall), LessonRunKind::Lesson, 1)['plan'];

        expect(collect($plan)->where('origin', 'warmup'))->toHaveCount(0);

        LessonExercise::query()->whereNull('probe_set')->update(['retired_at' => now()]);

        expect(fn () => (new BuildLessonPlan)->handle($user, LessonWorld::lesson($unit, LessonStage::Check), LessonRunKind::Practice, 1))->toThrow(LogicException::class, 'nothing to practise');
    });
});

describe('the content registry', function () {
    it('finds content classes in a folder of language folders and ignores other classes', function () {
        $registry = new UnitContentRegistry(path: __DIR__.'/../../Fixtures/Lessons/content', namespace: 'Tests\\Fixtures\\Lessons\\Content\\');

        expect($registry->all())->toHaveCount(1)
            ->and($registry->all()[0]->unitSlug())->toBe('checking-into-a-hotel');
    });

    it('finds nothing in a folder that does not exist', function () {
        expect((new UnitContentRegistry(path: '/nowhere'))->all())->toBe([]);
    });
});
