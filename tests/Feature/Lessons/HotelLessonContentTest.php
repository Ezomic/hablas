<?php

declare(strict_types=1);

use App\Actions\Lessons\BuildUnitLessons;
use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonStage;
use App\Lessons\AuthoredExercise;
use App\Lessons\AuthoredTexts;
use App\Lessons\ExerciseDefinition;
use App\Lessons\LessonDefinition;
use App\Lessons\PreviewContent;
use App\Lessons\ReviewGate;
use App\Lessons\SpokenTexts;
use App\Models\Lesson;
use App\Models\Unit;
use App\Services\SpanishTextNormalizer;
use App\Services\UnitContentRegistry;
use App\Speech\SpeechCorpus;
use App\Speech\SpeechText;
use Database\Content\Lessons\Es\CheckingIntoAHotel;
use Database\Content\Lessons\Es\CoreWords;
use Database\Seeders\ContentSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\SpanishA1Seeder;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(SpanishA1Seeder::class);
    $this->content = new CheckingIntoAHotel;
    $this->unit = Unit::query()->where('slug', 'checking-into-a-hotel')->firstOrFail();
    $this->normalizer = new SpanishTextNormalizer;
    $this->lessons = collect((new BuildUnitLessons)->handle($this->unit, new PreviewContent($this->content, withLessons: true)))
        ->keyBy(fn (LessonDefinition $lesson): string => $lesson->stage->value);
    $this->authored = collect($this->content->exercises());
});

/** @return array<string, int> graded answers per family of the original exercises of a lesson */
function gradedByFamily(LessonDefinition $lesson): array
{
    $counts = ['choice' => 0, 'writing' => 0, 'listening' => 0, 'speaking' => 0];

    foreach ($lesson->exercises as $exercise) {
        $family = $exercise->format->family();

        if ($exercise->substituteForKey !== null || $family === null) {
            continue;
        }

        $questions = $exercise->format->isPassage() ? count($exercise->payload['questions']) : 1;
        $counts[$family->value] += $questions;
    }

    return $counts;
}

/** @return list<string> the complete sentences an authored exercise shows or accepts, as comparable keys */
function sentencesOf(AuthoredExercise $exercise, SpanishTextNormalizer $normalizer): array
{
    $payload = $exercise->payload;
    $texts = match ($exercise->format) {
        Format::TypeGap, Format::ChooseGap => [str_replace('___', (string) ($exercise->accepted[0] ?? $payload['answer'] ?? ''), (string) $payload['prompt'])],
        Format::TranslateSentence, Format::BuildSentence, Format::ListenType => [$exercise->accepted[0]],
        Format::TransformSentence => [(string) $payload['source'], $exercise->accepted[0]],
        Format::SpeakRepeat, Format::ListenChoose => [(string) $payload['text']],
        Format::SpeakAnswer => [(string) $payload['prompt']],
        Format::ReadPassage, Format::ListenPassage => array_merge(...array_map(fn (string $line): array => preg_split('/(?<=[.?!])\s+/u', $line) ?: [], SpokenTexts::dialogue($payload))),
        default => [],
    };

    $keys = [];

    foreach ($texts as $text) {
        $key = $normalizer->answerKey($text);

        if (count(explode(' ', $key)) > 2) {
            $keys[] = $key;
        }
    }

    return $keys;
}

function stageSentences(object $test, LessonStage $stage, ?string $set = null): array
{
    return $test->authored
        ->filter(fn (AuthoredExercise $exercise): bool => $exercise->stage === $stage && $exercise->probeSet === $set)
        ->flatMap(fn (AuthoredExercise $exercise): array => sentencesOf($exercise, $test->normalizer))
        ->unique()->values()->all();
}

describe('the authored hotel content and the review gate', function () {
    it('is released: it has the independent AI review and the owner approval of the lessons', function () {
        expect(ReviewGate::wordsReleased($this->content))->toBeTrue()
            ->and(ReviewGate::lessonsReleased($this->content))->toBeTrue()
            ->and($this->content->exercises())->not->toBeEmpty();
    });

    it('seeds every authored lesson through ContentSeeder', function () {
        $this->seed(ContentSeeder::class);

        $stages = Lesson::query()->where('unit_id', $this->unit->id)->orderBy('position')->pluck('stage')->map(fn (LessonStage $stage): string => $stage->value)->all();

        expect($stages)->toBe(['meet', 'recall', 'sentences', 'task', 'check']);
    });

    it('builds, with the lessons treated as released, all five lessons', function () {
        expect($this->lessons->keys()->all())->toBe(['meet', 'recall', 'sentences', 'task', 'check']);
    });
});

describe('the ramp', function () {
    it('gives lesson 3 about thirty graded answers and lesson 4 about twenty-seven, spread over the four types', function () {
        $three = gradedByFamily($this->lessons['sentences']);
        $four = gradedByFamily($this->lessons['task']);

        expect($three)->toBe(['choice' => 6, 'writing' => 11, 'listening' => 7, 'speaking' => 7])
            ->and($four)->toBe(['choice' => 5, 'writing' => 10, 'listening' => 6, 'speaking' => 6]);
    });

    it('keeps sentences within the stage caps', function () {
        $caps = [LessonStage::Sentences->value => 7, LessonStage::Task->value => 12, LessonStage::Check->value => 10];

        foreach ($this->authored as $exercise) {
            $texts = $exercise->format->isPassage() ? SpokenTexts::dialogue($exercise->payload) : [...$exercise->accepted, ...(isset($exercise->payload['text']) ? [$exercise->payload['text']] : []), ...(isset($exercise->payload['prompt']) && in_array($exercise->format, [Format::SpeakAnswer, Format::ChooseGap], true) ? [$exercise->payload['prompt']] : [])];

            foreach ($texts as $text) {
                foreach (preg_split('/(?<=[.?!])\s+/u', (string) $text) ?: [] as $sentence) {
                    $words = count(explode(' ', $this->normalizer->exactKey($sentence)));

                    expect($words)->toBeLessThanOrEqual($caps[$exercise->stage->value], "{$exercise->key}: {$sentence}");
                }
            }
        }
    });

    it('puts the right number of distractor tiles in tile exercises', function () {
        foreach ($this->lessons as $lesson) {
            foreach ($lesson->exercises as $exercise) {
                if ($exercise->format !== Format::BuildSentence || $exercise->substituteForKey !== null) {
                    continue;
                }

                $answer = $this->normalizer->exactKey($exercise->payload['accepted'][0]['text']);
                $extra = count($exercise->payload['tiles']) - count(explode(' ', $answer));

                expect($extra)->toBe($lesson->stage === LessonStage::Sentences ? 1 : 2, $exercise->key);
            }
        }
    });

    it('never builds a hint or a feedback into a check', function () {
        foreach ($this->authored->filter(fn (AuthoredExercise $exercise): bool => $exercise->stage === LessonStage::Check) as $exercise) {
            expect($exercise->payload)->not->toHaveKeys(['why', 'glosses', 'chips'], $exercise->key);
        }
    });
});

describe('the coverage rules', function () {
    it('practises every item in at least two phrases or sentences in lesson 3 and in lesson 4', function () {
        foreach ([LessonStage::Sentences, LessonStage::Task] as $stage) {
            $counts = [];

            foreach ($this->lessons[$stage->value]->exercises as $exercise) {
                if ($exercise->substituteForKey !== null) {
                    continue;
                }

                foreach ($exercise->targets as $target) {
                    if (! $target->ref->isGrammar()) {
                        $counts[$target->ref->key()] = ($counts[$target->ref->key()] ?? 0) + 1;
                    }
                }
            }

            expect($counts)->toHaveCount(10);

            foreach ($counts as $key => $count) {
                expect($count)->toBeGreaterThanOrEqual(2, "{$stage->value} {$key}");
            }
        }
    });

    it('practises the grammar point in at least six exercises of lesson 3, three of them production over three forms, and in four production exercises of lesson 4', function () {
        $production = [Format::TypeGap, Format::TranslateSentence, Format::TransformSentence, Format::BuildSentence, Format::ListenType];

        $grammar = fn (LessonStage $stage): Collection => collect($this->lessons[$stage->value]->exercises)
            ->filter(fn (ExerciseDefinition $exercise): bool => $exercise->substituteForKey === null && collect($exercise->targets)->contains(fn ($target): bool => $target->ref->isGrammar()));

        $three = $grammar(LessonStage::Sentences);
        $threeProduction = $three->filter(fn (ExerciseDefinition $exercise): bool => in_array($exercise->format, $production, true));
        $forms = $three->flatMap(fn (ExerciseDefinition $exercise): array => collect($exercise->targets)->filter(fn ($target): bool => $target->ref->isGrammar())->map(fn ($target): string => mb_strtolower((string) $target->form))->all())->unique();

        expect($three->count())->toBeGreaterThanOrEqual(6)
            ->and($threeProduction->count())->toBeGreaterThanOrEqual(3)
            ->and($forms->count())->toBeGreaterThanOrEqual(3)
            ->and($grammar(LessonStage::Task)->filter(fn (ExerciseDefinition $exercise): bool => in_array($exercise->format, $production, true))->count())->toBeGreaterThanOrEqual(4);
    });

    it('has at least three contrast items in the lessons', function () {
        $contrast = $this->authored
            ->filter(fn (AuthoredExercise $exercise): bool => $exercise->stage !== LessonStage::Check)
            ->filter(fn (AuthoredExercise $exercise): bool => collect($exercise->targets)->contains(fn ($spec): bool => $spec->isGrammar() && $spec->contrast));

        expect($contrast->count())->toBeGreaterThanOrEqual(3);

        foreach ($this->authored->filter(fn ($exercise): bool => in_array($exercise->format, [Format::ChooseGap, Format::TypeGap], true) && $exercise->stage !== LessonStage::Check && collect($exercise->targets)->contains(fn ($spec): bool => $spec->isGrammar())) as $gap) {
            expect($gap->payload['why'] ?? '')->not->toBe('', $gap->key);
        }
    });

    it('gives every item all four types across the unit and at least twelve graded exercises', function () {
        $families = [];
        $graded = [];

        foreach ($this->lessons as $lesson) {
            foreach ($lesson->exercises as $exercise) {
                $family = $exercise->format->family();

                if ($exercise->substituteForKey !== null || $family === null) {
                    continue;
                }

                foreach ($exercise->targets as $target) {
                    if ($target->ref->isGrammar()) {
                        continue;
                    }

                    $families[$target->ref->key()][$family->value] = true;
                    $graded[$target->ref->key()] = ($graded[$target->ref->key()] ?? 0) + 1;
                }
            }
        }

        expect($families)->toHaveCount(10);

        foreach ($families as $key => $kinds) {
            expect(array_keys($kinds))->toContain('choice', 'writing', 'listening', 'speaking')
                ->and($graded[$key])->toBeGreaterThanOrEqual(12);
        }
    });

    it('gives each check set two probes per word and six for the grammar point, two of them contrast, over three forms', function () {
        foreach (['a', 'b'] as $set) {
            $words = [];
            $grammar = [];

            foreach ($this->lessons['check']->exercises as $exercise) {
                if ($exercise->probeSet !== $set || $exercise->substituteForKey !== null) {
                    continue;
                }

                foreach ($exercise->targets as $target) {
                    if (! $target->isProbe) {
                        continue;
                    }

                    if ($target->ref->isGrammar()) {
                        $grammar[] = $target;
                    } else {
                        $words[$target->ref->key()] = ($words[$target->ref->key()] ?? 0) + 1;
                    }
                }
            }

            expect($words)->toHaveCount(10)
                ->and(min($words))->toBeGreaterThanOrEqual(2)
                ->and($grammar)->toHaveCount(6)
                ->and(count(array_filter($grammar, fn ($target): bool => $target->isContrast)))->toBeGreaterThanOrEqual(2)
                ->and(count(array_unique(array_map(fn ($target): string => mb_strtolower((string) $target->form), $grammar))))->toBeGreaterThanOrEqual(3);
        }
    });

    it('keeps check sentences out of lessons 3 and 4 and set B apart from set A', function () {
        $lessons = [...stageSentences($this, LessonStage::Sentences), ...stageSentences($this, LessonStage::Task)];
        $a = stageSentences($this, LessonStage::Check, 'a');
        $b = stageSentences($this, LessonStage::Check, 'b');

        expect($a)->not->toBeEmpty()->and($b)->not->toBeEmpty()
            ->and(array_intersect($a, $lessons))->toBe([])
            ->and(array_intersect($b, $lessons))->toBe([])
            ->and(array_intersect($a, $b))->toBe([]);
    });

    it('gives every listening and speaking exercise a substitute with the same targets in another family and a prompt', function () {
        foreach ($this->lessons as $lesson) {
            $substitutes = collect($lesson->exercises)->filter(fn (ExerciseDefinition $exercise): bool => $exercise->substituteForKey !== null)->keyBy('substituteForKey');

            foreach ($lesson->exercises as $exercise) {
                if ($exercise->substituteForKey !== null || ! $exercise->format->isSkippable()) {
                    continue;
                }

                $substitute = $substitutes->get($exercise->key);

                expect($substitute)->not->toBeNull($exercise->key)
                    ->and($substitute->format->family())->not->toBe($exercise->format->family())
                    ->and(array_map(fn ($target): string => $target->ref->key(), $substitute->targets))->toBe(array_map(fn ($target): string => $target->ref->key(), $exercise->targets));

                if ($substitute->format !== Format::ReadPassage) {
                    expect(trim((string) ($substitute->payload['prompt'] ?? '')))->not->toBe('', $exercise->key);
                }
            }
        }
    });
});

describe('the language', function () {
    it('uses only the unit words, the core words, the forms the content declares and the glossed words', function () {
        $known = [];

        foreach ([...CoreWords::words()] as $word) {
            foreach (explode(' ', $word) as $part) {
                $known[$part] = true;
            }
        }

        foreach ($this->content->words() as $word) {
            foreach ([$word->term, ...$word->accepted, ...$word->forms] as $text) {
                foreach (explode(' ', $this->normalizer->exactKey($text)) as $part) {
                    $known[$part] = true;
                }
            }
        }

        foreach ($this->authored as $exercise) {
            foreach ($exercise->targets as $spec) {
                foreach (explode(' ', $this->normalizer->exactKey($spec->form)) as $part) {
                    $known[$part] = true;
                }
            }
        }

        $unknown = [];

        foreach ($this->authored as $exercise) {
            $glossed = array_map($this->normalizer->exactKey(...), array_keys(is_array($exercise->payload['glosses'] ?? null) ? $exercise->payload['glosses'] : []));

            foreach (AuthoredTexts::of($exercise) as $text) {
                foreach (explode(' ', $this->normalizer->exactKey($text)) as $word) {
                    if ($word !== '' && ! isset($known[$word]) && ! in_array($word, $glossed, true)) {
                        $unknown[$exercise->key][] = $word;
                    }
                }
            }
        }

        expect($unknown)->toBe([]);
    });

    it('keeps Latin American forms out', function () {
        $guard = require base_path('tests/Fixtures/Lessons/es-latam-forms.php');

        foreach ($this->authored as $exercise) {
            $words = collect(AuthoredTexts::of($exercise))->flatMap(fn (string $text): array => explode(' ', $this->normalizer->exactKey($text)))->all();

            expect(array_intersect($words, $guard))->toBe([], $exercise->key);
        }
    });

    it('marks every dictation that holds a word with a homophone', function () {
        $homophones = ['hola', 'ola', 'hay', 'ay', 'echo', 'hecho', 'tuvo', 'tubo', 'vaya', 'valla', 'a', 'ha', 'e', 'he'];

        foreach ($this->authored->filter(fn (AuthoredExercise $exercise): bool => $exercise->format === Format::ListenType) as $exercise) {
            $words = explode(' ', $this->normalizer->exactKey($exercise->accepted[0]));

            if (array_intersect($words, $homophones) !== []) {
                expect($exercise->payload['homophone_note'] ?? '')->not->toBe('', $exercise->key);
            }
        }

        expect(true)->toBeTrue();
    });

    it('writes no dash as punctuation anywhere in the content', function () {
        foreach ($this->authored as $exercise) {
            $all = json_encode([$exercise->payload, $exercise->accepted], JSON_UNESCAPED_UNICODE);

            expect($all)->not->toMatch('/[—–]| -- /u');
        }
    });

    it('has no two exercises with the same key', function () {
        $keys = $this->authored->map(fn (AuthoredExercise $exercise): string => $exercise->key);

        expect($keys->unique()->count())->toBe($keys->count());
    });
});

describe('the speech clips', function () {
    it('are asked for every text the player may play, so speech:generate covers them', function () {
        app()->instance(UnitContentRegistry::class, new UnitContentRegistry([$this->content]));
        $corpus = app(SpeechCorpus::class)->texts('es');
        $speech = app(SpeechText::class);
        $missing = [];

        foreach ($this->lessons as $lesson) {
            foreach ($lesson->exercises as $exercise) {
                foreach (SpokenTexts::ofPayload($exercise->format, $exercise->payload) as $text) {
                    if (! in_array($speech->normalise($text), $corpus, true)) {
                        $missing[] = $text;
                    }
                }
            }
        }

        expect($missing)->toBe([])
            ->and($corpus)->toContain('Su habitación es la tres.', 'Buenas noches. Su habitación es la cinco.', 'La llave está aquí.', 'Estoy en el baño.', '¿Tiene usted una reserva?', '¿Cuántas noches?');
    });
});
