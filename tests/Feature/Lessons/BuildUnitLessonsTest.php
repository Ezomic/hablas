<?php

declare(strict_types=1);

use App\Actions\Lessons\BuildUnitLessons;
use App\Enums\ExerciseFamily;
use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonStage;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\ExerciseDefinition;
use App\Lessons\InvalidLessonContent;
use App\Lessons\LessonDefinition;
use App\Lessons\TargetSpec;
use App\Lessons\UnitContent;
use App\Lessons\WordData;
use App\Models\Unit;
use App\Models\VocabularyItem;
use Tests\Fixtures\Lessons\HotelContent;
use Tests\Fixtures\Lessons\LessonWorld;

/** @return array<string, LessonDefinition> */
function builtLessons(?UnitContent $content = null, ?Unit $unit = null): array
{
    $unit ??= LessonWorld::hotelUnit();
    $built = [];

    foreach ((new BuildUnitLessons)->handle($unit, $content ?? new HotelContent) as $lesson) {
        $built[$lesson->stage->value] = $lesson;
    }

    return $built;
}

/** @return list<ExerciseDefinition> */
function originals(LessonDefinition $lesson): array
{
    return array_values(array_filter($lesson->exercises, fn (ExerciseDefinition $exercise): bool => $exercise->substituteForKey === null));
}

/** @return list<string> */
function formatsOf(LessonDefinition $lesson): array
{
    return array_map(fn (ExerciseDefinition $exercise): string => $exercise->format->value, originals($lesson));
}

it('builds five lessons in order from reviewed content', function () {
    $lessons = builtLessons();

    expect(array_keys($lessons))->toBe(['meet', 'recall', 'sentences', 'task', 'check'])
        ->and(array_map(fn (LessonDefinition $lesson): int => $lesson->position, array_values($lessons)))->toBe([1, 2, 3, 4, 5]);
});

it('builds nothing for words that have not had their independent review', function () {
    expect(builtLessons(new HotelContent(wordsReviewed: false, lessonsReviewed: false)))->toBe([]);
});

it('holds back authored lessons until they are reviewed, leaving lessons 1 and 2 and a words-only check', function () {
    $lessons = builtLessons(new HotelContent(lessonsReviewed: false));

    expect(array_keys($lessons))->toBe(['meet', 'recall', 'check']);

    $recall = array_values(array_filter($lessons['check']->exercises, fn (ExerciseDefinition $exercise): bool => $exercise->substituteForKey === null));

    expect(array_unique(array_map(fn (ExerciseDefinition $exercise): string => $exercise->format->value, $recall)))->toBe(['type_word'])
        ->and(count($recall))->toBe(20);
});

it('holds back authored lessons when the owner has not approved them', function () {
    $unit = LessonWorld::hotelUnit();
    $content = new class implements UnitContent
    {
        public function languageCode(): string
        {
            return 'es';
        }

        public function unitSlug(): string
        {
            return 'checking-into-a-hotel';
        }

        public function words(): array
        {
            return [];
        }

        public function grammarExamples(): array
        {
            return [];
        }

        public function exercises(): array
        {
            return [new AuthoredExercise(LessonStage::Task, Format::BuildSentence, 'x', ['distractors' => ['es', 'hay']], ['La llave está aquí'])];
        }

        public function reviews(): array
        {
            return [
                new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'ai', '2026-10-02'),
                new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'ai', '2026-10-02'),
            ];
        }
    };

    $stages = array_map(fn (LessonDefinition $lesson): string => $lesson->stage->value, (new BuildUnitLessons)->handle($unit, $content));

    expect($stages)->toBe(['meet', 'recall', 'check']);
});

describe('lesson 1', function () {
    it('teaches in batches of 4, 3 and 3 before it tests', function () {
        $meet = builtLessons()['meet'];
        $teach = array_values(array_filter(originals($meet), fn (ExerciseDefinition $exercise): bool => $exercise->format === Format::TeachWord));
        $perBlock = array_count_values(array_map(fn (ExerciseDefinition $exercise): string => $exercise->block, $teach));

        expect($perBlock)->toBe(['batch-1' => 4, 'batch-2' => 3, 'batch-3' => 3]);
    });

    it('puts a teach card and a first recognition exercise before anything else on an item', function () {
        $meet = originals(builtLessons()['meet']);
        $seen = [];

        foreach ($meet as $exercise) {
            if ($exercise->format === Format::MatchPairs) {
                continue;
            }

            $key = $exercise->targets[0]->ref->key();

            if ($exercise->format === Format::TeachWord) {
                expect($seen[$key] ?? null)->toBeNull();
                $seen[$key] = 'taught';

                continue;
            }

            if (($seen[$key] ?? null) === 'taught') {
                expect($exercise->format)->toBeIn([Format::ChooseMeaning, Format::ListenChoose]);
                $seen[$key] = 'recognised';

                continue;
            }

            expect($seen[$key] ?? null)->toBe('recognised');
        }

        expect(count($seen))->toBe(10);
    });

    it('covers every item in at least three graded exercises across at least two types', function () {
        $meet = originals(builtLessons()['meet']);
        $byItem = [];

        foreach ($meet as $exercise) {
            if ($exercise->format->isTeach()) {
                continue;
            }

            foreach ($exercise->targets as $target) {
                $byItem[$target->ref->key()][] = $exercise->format->family()?->value;
            }
        }

        expect($byItem)->toHaveCount(10);

        foreach ($byItem as $families) {
            expect(count($families))->toBeGreaterThanOrEqual(3)
                ->and(count(array_unique($families)))->toBeGreaterThanOrEqual(2);
        }
    });

    it('matches all ten words in a mixed block at the end', function () {
        $meet = originals(builtLessons()['meet']);
        $matches = array_values(array_filter($meet, fn (ExerciseDefinition $exercise): bool => $exercise->format === Format::MatchPairs));

        expect($matches)->toHaveCount(2)
            ->and(array_sum(array_map(fn (ExerciseDefinition $exercise): int => count($exercise->targets), $matches)))->toBe(10)
            ->and(end($meet)->block)->toBe('mixed');
    });

    it('shows the first letter and the letter slots of what is typed, with the article whole', function () {
        $meet = originals(builtLessons()['meet']);
        $typed = collect($meet)->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === 'meet.type_word.la-llave');

        expect($typed->payload['hint'])->toBe('la  l _ _ _ _')
            ->and(collect($typed->payload['accepted'])->pluck('text')->all())->toBe(['la llave', 'llave']);
    });

    it('builds only the families asked for', function () {
        $unit = LessonWorld::hotelUnit();
        $lessons = (new BuildUnitLessons)->handle($unit, new HotelContent, [ExerciseFamily::Choice, ExerciseFamily::Writing]);
        $formats = collect($lessons)->flatMap(fn (LessonDefinition $lesson): array => formatsOf($lesson))->unique()->values()->all();

        expect($formats)->not->toContain('listen_choose', 'listen_type', 'speak_repeat', 'speak_answer', 'listen_passage');
    });
});

describe('the ramp by part of speech', function () {
    it('gives a noun the same noun with the wrong article as a distractor', function () {
        $exercise = collect(originals(builtLessons()['recall']))->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === 'recall.choose_word.el-desayuno');

        expect($exercise->payload['options'])->toContain('la desayuno')
            ->and($exercise->payload['answer'])->toBe('el desayuno');
    });

    it('gives no wrong-article distractor to a common-gender noun, and accepts both articles', function () {
        $recall = originals(builtLessons()['recall']);
        $choice = collect($recall)->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === 'recall.choose_word.el-recepcionista');
        $typed = collect($recall)->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === 'recall.type_word.el-recepcionista');

        expect($choice->payload['options'])->not->toContain('la recepcionista')
            ->and(collect($typed->payload['accepted'])->pluck('text')->all())->toBe(['el recepcionista', 'la recepcionista'])
            ->and($typed->payload['prompt'])->toBe('receptionist (at the hotel desk)');
    });

    it('gives an adjective its other forms as distractors', function () {
        $exercise = collect(originals(builtLessons()['recall']))->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === 'recall.choose_word.incluido');

        expect($exercise->payload['options'])->toContain('incluida', 'incluidos');
    });

    it('builds a unit with no nouns from its phrases and interjections', function () {
        $language = LessonWorld::spanish();
        $unit = Unit::factory()->create(['language_id' => $language->id, 'slug' => 'greetings']);

        foreach ([['hola', 'hello', 'interjection'], ['buenos días', 'good morning', 'phrase'], ['buenas tardes', 'good afternoon', 'phrase'], ['adiós', 'goodbye', 'interjection'], ['bien', 'well', 'adverb']] as [$term, $translation, $pos]) {
            VocabularyItem::factory()->create(['language_id' => $language->id, 'unit_id' => $unit->id, 'term' => $term, 'translation_en' => $translation, 'part_of_speech' => $pos]);
        }

        $content = new class implements UnitContent
        {
            public function languageCode(): string
            {
                return 'es';
            }

            public function unitSlug(): string
            {
                return 'greetings';
            }

            public function words(): array
            {
                return [];
            }

            public function grammarExamples(): array
            {
                return [];
            }

            public function exercises(): array
            {
                return [];
            }

            public function reviews(): array
            {
                return [new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'ai', '2026-10-02')];
            }
        };

        $lessons = [];

        foreach ((new BuildUnitLessons)->handle($unit, $content) as $lesson) {
            $lessons[$lesson->stage->value] = $lesson;
        }

        $choice = collect(originals($lessons['recall']))->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === 'recall.choose_word.hola');
        $dictation = collect(originals($lessons['recall']))->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === 'recall.listen_type.buenas-tardes');

        $substitute = collect($lessons['recall']->exercises)->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === 'recall.listen_type.buenas-tardes.sub');

        expect(array_keys($lessons))->toBe(['meet', 'recall', 'check'])
            ->and($choice->payload['options'])->toContain('adiós')
            ->and($dictation->format)->toBe(Format::ListenType)
            ->and($substitute->format)->toBe(Format::BuildSentence)
            ->and(count($substitute->payload['tiles']))->toBe(2);
    });

    it('never dictates a single word or a noun, only phrases of two to four words', function () {
        expect(formatsOf(builtLessons()['recall']))->not->toContain('listen_type');
    });
});

describe('targets and spans', function () {
    it('computes the span of every target in every accepted answer', function () {
        $sentences = builtLessons()['sentences'];
        $exercise = collect($sentences->exercises)->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === 'sentences.translate.desayuno');
        $keys = array_map(fn ($target): string => $target->ref->key(), $exercise->targets);

        expect($exercise->payload['accepted'][0]['text'])->toBe('El desayuno está incluido.')
            ->and(array_values($exercise->payload['accepted'][0]['spans']))->toBe([[0, 2], [3, 1], [2, 1]])
            ->and($keys)->toHaveCount(3);
    });

    it('fails the build when a target form is missing from an accepted answer', function () {
        $broken = new class implements UnitContent
        {
            public function languageCode(): string
            {
                return 'es';
            }

            public function unitSlug(): string
            {
                return 'checking-into-a-hotel';
            }

            public function words(): array
            {
                return [];
            }

            public function grammarExamples(): array
            {
                return [];
            }

            public function exercises(): array
            {
                return [new AuthoredExercise(LessonStage::Sentences, Format::TranslateSentence, 'broken', ['english' => 'x'], ['El baño está aquí.'], [TargetSpec::word('la habitación')])];
            }

            public function reviews(): array
            {
                return (new HotelContent)->reviews();
            }
        };

        expect(fn () => builtLessons($broken))->toThrow(InvalidLessonContent::class, "a target form is missing from the accepted answer 'El baño está aquí.'");
    });

    it('adds the answer without its subject pronoun as an accepted variant', function () {
        $unit = LessonWorld::hotelUnit();
        $content = new class implements UnitContent
        {
            public function languageCode(): string
            {
                return 'es';
            }

            public function unitSlug(): string
            {
                return 'checking-into-a-hotel';
            }

            public function words(): array
            {
                return [];
            }

            public function grammarExamples(): array
            {
                return [];
            }

            public function exercises(): array
            {
                return [new AuthoredExercise(LessonStage::Sentences, Format::TranslateSentence, 'pronoun', ['english' => 'I have a reservation.'], ['Yo tengo una reserva.'], [TargetSpec::word('la reserva', 'reserva')])];
            }

            public function reviews(): array
            {
                return (new HotelContent)->reviews();
            }
        };

        $exercise = collect(builtLessons($content, $unit)['sentences']->exercises)->first();

        expect(collect($exercise->payload['accepted'])->pluck('text')->all())->toBe(['Yo tengo una reserva.', 'tengo una reserva.'])
            ->and($exercise->payload['accepted'][1]['spans'])->toHaveCount(1);
    });

    it('marks check probes by format, never tiles, and passes contrast through', function () {
        $lessons = builtLessons();
        $check = $lessons['check'];
        $originals = originals($check);
        $translate = collect($originals)->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === 'check.a.translate.0');
        $hay = collect($originals)->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === 'check.a.type_gap.hay');
        $speak = collect($originals)->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === 'check.a.speak_answer');

        expect(array_unique(array_map(fn ($target): bool => $target->isProbe, $translate->targets)))->toBe([true])
            ->and($hay->targets[0]->isContrast)->toBeTrue()
            ->and($speak->targets[0]->isProbe)->toBeFalse();

        $sentences = $lessons['sentences'];

        foreach ($sentences->exercises as $exercise) {
            expect(array_unique(array_map(fn ($target): bool => $target->isProbe, $exercise->targets)))->not->toContain(true);
        }
    });

    it('fails the build for tiles that cannot rebuild the answer, or too few distractor tiles', function () {
        $unit = LessonWorld::hotelUnit();
        $make = fn (array $payload, array $accepted, LessonStage $stage = LessonStage::Sentences) => new class($payload, $accepted, $stage) implements UnitContent
        {
            public function __construct(private array $payload, private array $accepted, private LessonStage $stage) {}

            public function languageCode(): string
            {
                return 'es';
            }

            public function unitSlug(): string
            {
                return 'checking-into-a-hotel';
            }

            public function words(): array
            {
                return [];
            }

            public function grammarExamples(): array
            {
                return [];
            }

            public function exercises(): array
            {
                return [new AuthoredExercise($this->stage, Format::BuildSentence, 'tiles', $this->payload, $this->accepted, [TargetSpec::word('el baño')])];
            }

            public function reviews(): array
            {
                return (new HotelContent)->reviews();
            }
        };

        expect(fn () => builtLessons($make(['distractors' => []], ['El baño está aquí.']), $unit))->toThrow(InvalidLessonContent::class, 'needs 1 distractor tiles');
        expect(fn () => builtLessons($make(['distractors' => ['es']], ['El baño está aquí.', 'Aquí está el baño y la llave.']), $unit))->toThrow(InvalidLessonContent::class, 'cannot rebuild');
    });

    it('fails the build for a choice whose answer is not among its options', function () {
        $unit = LessonWorld::hotelUnit();
        $content = new class implements UnitContent
        {
            public function languageCode(): string
            {
                return 'es';
            }

            public function unitSlug(): string
            {
                return 'checking-into-a-hotel';
            }

            public function words(): array
            {
                return [];
            }

            public function grammarExamples(): array
            {
                return [];
            }

            public function exercises(): array
            {
                return [new AuthoredExercise(LessonStage::Recall, Format::ChooseGap, 'gap', ['options' => ['es', 'hay'], 'answer' => 'está'])];
            }

            public function reviews(): array
            {
                return (new HotelContent)->reviews();
            }
        };

        expect(fn () => builtLessons($content, $unit))->toThrow(InvalidLessonContent::class, 'the answer is not among the options');
    });

    it('fails the build for two items that share a recall cue', function () {
        $unit = LessonWorld::hotelUnit();
        $content = new class implements UnitContent
        {
            public function languageCode(): string
            {
                return 'es';
            }

            public function unitSlug(): string
            {
                return 'checking-into-a-hotel';
            }

            public function words(): array
            {
                return [new WordData('el hotel', cue: 'room')];
            }

            public function grammarExamples(): array
            {
                return [];
            }

            public function exercises(): array
            {
                return [];
            }

            public function reviews(): array
            {
                return (new HotelContent)->reviews();
            }
        };

        expect(fn () => builtLessons($content, $unit))->toThrow(InvalidLessonContent::class, "share the recall cue 'room'");
    });

    it('fails the build for word data that names an item the unit does not have', function () {
        $unit = LessonWorld::hotelUnit();
        $content = new class implements UnitContent
        {
            public function languageCode(): string
            {
                return 'es';
            }

            public function unitSlug(): string
            {
                return 'checking-into-a-hotel';
            }

            public function words(): array
            {
                return [new WordData('el aeropuerto')];
            }

            public function grammarExamples(): array
            {
                return [];
            }

            public function exercises(): array
            {
                return [];
            }

            public function reviews(): array
            {
                return (new HotelContent)->reviews();
            }
        };

        expect(fn () => builtLessons($content, $unit))->toThrow(InvalidLessonContent::class, 'not a vocabulary item');
    });

    it('rejects a probe set on a lesson exercise and a missing one on a check exercise', function () {
        $unit = LessonWorld::hotelUnit();
        $make = fn (AuthoredExercise $exercise) => new class($exercise) implements UnitContent
        {
            public function __construct(private AuthoredExercise $exercise) {}

            public function languageCode(): string
            {
                return 'es';
            }

            public function unitSlug(): string
            {
                return 'checking-into-a-hotel';
            }

            public function words(): array
            {
                return [];
            }

            public function grammarExamples(): array
            {
                return [];
            }

            public function exercises(): array
            {
                return [$this->exercise];
            }

            public function reviews(): array
            {
                return (new HotelContent)->reviews();
            }
        };

        $typed = ['english' => 'x'];

        expect(fn () => builtLessons($make(new AuthoredExercise(LessonStage::Check, Format::TypeGap, 'k', $typed, ['x'])), $unit))->toThrow(InvalidLessonContent::class, 'needs probe set')
            ->and(fn () => builtLessons($make(new AuthoredExercise(LessonStage::Sentences, Format::TypeGap, 'k', $typed, ['x'], probeSet: 'a')), $unit))->toThrow(InvalidLessonContent::class, 'Only check exercises')
            ->and(fn () => builtLessons($make(new AuthoredExercise(LessonStage::Sentences, Format::TypeGap, 'k', $typed, ['x'], block: 'recall')), $unit))->toThrow(InvalidLessonContent::class, 'reserved');
    });
});

describe('substitutes', function () {
    it('gives every listening and speaking exercise a substitute from another family with the same targets', function () {
        foreach (builtLessons() as $lesson) {
            $byKey = [];

            foreach ($lesson->exercises as $exercise) {
                $byKey[$exercise->key] = $exercise;
            }

            foreach (originals($lesson) as $original) {
                if (! $original->format->isSkippable()) {
                    expect($byKey)->not->toHaveKey($original->key.'.sub');

                    continue;
                }

                $substitute = $byKey[$original->key.'.sub'];

                expect($substitute->format->family())->not->toBe($original->format->family())
                    ->and($substitute->substituteForKey)->toBe($original->key)
                    ->and(array_map(fn ($target): string => $target->ref->key(), $substitute->targets))->toBe(array_map(fn ($target): string => $target->ref->key(), $original->targets));
            }
        }
    });

    it('substitutes at the level of the stage', function () {
        $lessons = builtLessons();
        $sub = fn (string $stage, string $key) => collect($lessons[$stage]->exercises)->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === $key.'.sub');

        expect($sub('meet', 'meet.listen_choose.el-hotel')->format)->toBe(Format::ChooseMeaning)
            ->and($sub('meet', 'meet.speak_repeat.la-habitacion')->format)->toBe(Format::TypeWord)
            ->and($sub('meet', 'meet.speak_repeat.la-habitacion')->payload['hint'])->toContain('l')
            ->and($sub('recall', 'recall.speak_answer.la-habitacion')->format)->toBe(Format::TypeWord)
            ->and($sub('sentences', 'sentences.speak_repeat.hay')->format)->toBe(Format::TranslateSentence)
            ->and($sub('sentences', 'sentences.listen_type.reserva')->format)->toBe(Format::TranslateSentence)
            ->and($sub('task', 'task.speak_answer.reserva')->format)->toBe(Format::WriteGuided)
            ->and($sub('task', 'task.listen_passage.reception')->format)->toBe(Format::ReadPassage)
            ->and($sub('task', 'task.listen_passage.reception')->payload['questions'][0]['prompt'])->toBe('Whose room is it?');
    });

    it('makes the substitute of a dictation probe an exact-match probe, and speaking never a probe', function () {
        $check = builtLessons()['check'];
        $dictation = collect($check->exercises)->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === 'check.a.listen_type.es.sub');
        $speaking = collect($check->exercises)->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === 'check.a.speak_answer.sub');

        expect($dictation->format)->toBe(Format::TranslateSentence)
            ->and($dictation->targets[0]->isProbe)->toBeTrue()
            ->and($dictation->probeSet)->toBe('a')
            ->and($speaking->format)->toBe(Format::WriteGuided)
            ->and($speaking->targets[0]->isProbe)->toBeFalse();
    });

    it('needs a second question set for the substitute of a listening passage', function () {
        $unit = LessonWorld::hotelUnit();
        $content = new class implements UnitContent
        {
            public function languageCode(): string
            {
                return 'es';
            }

            public function unitSlug(): string
            {
                return 'checking-into-a-hotel';
            }

            public function words(): array
            {
                return [];
            }

            public function grammarExamples(): array
            {
                return [];
            }

            public function exercises(): array
            {
                return [new AuthoredExercise(LessonStage::Task, Format::ListenPassage, 'p', ['questions' => [['prompt' => 'q', 'options' => ['a', 'b'], 'answer' => 'a']], 'substitute_questions' => []])];
            }

            public function reviews(): array
            {
                return (new HotelContent)->reviews();
            }
        };

        expect(fn () => builtLessons($content, $unit))->toThrow(InvalidLessonContent::class, 'needs its question sets');
    });
});

describe('stable keys and hashes', function () {
    it('builds identical keys and hashes every time', function () {
        $first = builtLessons();
        $second = builtLessons(null, LessonWorld::hotelUnit(slug: 'another-hotel'));

        foreach ($first as $stage => $lesson) {
            $hashes = array_map(fn (ExerciseDefinition $exercise): array => [$exercise->key, $exercise->hash], $lesson->exercises);
            $again = array_map(fn (ExerciseDefinition $exercise): array => [$exercise->key, $exercise->hash], $second[$stage]->exercises);

            expect(array_column($hashes, 0))->toBe(array_column($again, 0))
                ->and(count(array_unique(array_column($hashes, 0))))->toBe(count($hashes));
        }
    });

    it('changes a hash when the content changes', function () {
        $unit = LessonWorld::hotelUnit();
        $before = collect((new BuildUnitLessons)->handle($unit, new HotelContent))->flatMap(fn (LessonDefinition $lesson): array => $lesson->exercises)->mapWithKeys(fn (ExerciseDefinition $exercise): array => [$exercise->key => $exercise->hash]);

        VocabularyItem::query()->where('term', 'la llave')->update(['translation_en' => 'door key']);
        $unit = Unit::query()->findOrFail($unit->id);

        $after = collect((new BuildUnitLessons)->handle($unit, new HotelContent))->flatMap(fn (LessonDefinition $lesson): array => $lesson->exercises)->mapWithKeys(fn (ExerciseDefinition $exercise): array => [$exercise->key => $exercise->hash]);

        expect($after['meet.teach_word.la-llave'])->not->toBe($before['meet.teach_word.la-llave'])
            ->and($after['meet.teach_word.el-hotel'])->toBe($before['meet.teach_word.el-hotel']);
    });
});

it('puts the grammar card first in lesson 2 and keeps its examples', function () {
    $recall = builtLessons()['recall'];
    $first = $recall->exercises[0];

    expect($first->format)->toBe(Format::TeachGrammar)
        ->and($first->block)->toBe('grammar')
        ->and($first->payload['examples'])->toHaveCount(2)
        ->and($first->payload['title'])->toBe('Estar for location');
});
