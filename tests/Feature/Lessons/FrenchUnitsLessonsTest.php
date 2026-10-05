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
use App\Lessons\TileSplitter;
use App\Lessons\UnitContent;
use App\Models\Lesson;
use App\Models\LessonExercise;
use App\Models\Unit;
use App\Services\FrenchTextNormalizer;
use App\Services\UnitContentRegistry;
use App\Speech\SpeechCorpus;
use App\Speech\SpeechText;
use Database\Content\Lessons\Fr\CoreWords;
use Database\Seeders\ContentSeeder;
use Database\Seeders\FrenchA1Seeder;
use Database\Seeders\LanguageSeeder;
use Illuminate\Support\Collection;
use Tests\Support\AuthoredContent;

/** @return array<string, UnitContent> */
function frenchUnits(): array
{
    $units = [];

    foreach (glob(__DIR__.'/../../../database/content/Lessons/Fr/*.php') ?: [] as $file) {
        $class = 'Database\\Content\\Lessons\\Fr\\'.basename($file, '.php');

        if ($class === CoreWords::class) {
            continue;
        }

        $content = new $class;

        if (getenv('FR_UNIT') !== false && getenv('FR_UNIT') !== $content->unitSlug()) {
            continue;
        }

        if ($content->exercises() !== []) {
            $units[$content->unitSlug()] = $content;
        }
    }

    return $units;
}

describe('every French unit with authored lessons stays behind the review gate', function () {
    beforeEach(function () {
        $this->seed(LanguageSeeder::class);
        $this->seed(FrenchA1Seeder::class);
    });

    it('is written but not released: it needs the independent AI review and the owner approval of the lessons', function () {
        foreach (frenchUnits() as $slug => $content) {
            expect(ReviewGate::wordsReleased($content))->toBeFalse($slug)
                ->and(ReviewGate::lessonsReleased($content))->toBeFalse($slug)
                ->and($content->exercises())->not->toBeEmpty($slug)
                ->and($content->reviews())->toBe([], $slug);
        }
    });

    it('seeds no authored lesson through ContentSeeder, so learners keep lessons 1 and 2 and the words check', function () {
        $this->seed(ContentSeeder::class);

        foreach (frenchUnits() as $slug => $content) {
            $unit = Unit::query()->where('slug', $slug)->firstOrFail();
            $lessons = Lesson::query()->where('unit_id', $unit->id)->orderBy('position')->get();
            $exercises = LessonExercise::query()->whereIn('lesson_id', $lessons->pluck('id'))->get();
            $authoredKeys = collect($content->exercises())->map(fn (AuthoredExercise $exercise): string => $exercise->key);

            expect($lessons->map(fn (Lesson $lesson): string => $lesson->stage->value)->all())->toBe([], $slug)
                ->and($exercises->pluck('key')->intersect($authoredKeys)->all())->toBe([], $slug)
                ->and($exercises->filter(fn (LessonExercise $exercise): bool => $exercise->lesson->stage === LessonStage::Check && $exercise->block !== 'recall')->count())->toBe(0, $slug);
        }
    });
});

foreach (frenchUnits() as $slug => $unitContent) {
    describe("the authored content of {$slug}", function () use ($slug, $unitContent) {
        beforeEach(function () use ($slug, $unitContent) {
            $this->seed(LanguageSeeder::class);
            $this->seed(FrenchA1Seeder::class);
            $this->content = $unitContent;
            $this->unit = Unit::query()->where('slug', $slug)->firstOrFail();
            $this->normalizer = new FrenchTextNormalizer;
            $this->lessons = collect((new BuildUnitLessons)->handle($this->unit, new PreviewContent($unitContent, withLessons: true)))
                ->keyBy(fn (LessonDefinition $lesson): string => $lesson->stage->value);
            $this->authored = collect($unitContent->exercises());
        });

        it('builds, with the lessons treated as released, all five lessons', function () {
            expect($this->lessons->keys()->all())->toBe(['meet', 'recall', 'sentences', 'task', 'check']);
        });

        it('gives lesson 3 thirty graded answers and lesson 4 twenty-seven, spread over the four types', function () {
            expect(AuthoredContent::graded($this->lessons['sentences']))->toBe(['choice' => 6, 'writing' => 11, 'listening' => 7, 'speaking' => 7])
                ->and(AuthoredContent::graded($this->lessons['task']))->toBe(['choice' => 5, 'writing' => 10, 'listening' => 6, 'speaking' => 6]);
        });

        it('gives each check set the same shape: nine sentence and dictation probes, and set A also two passages and three spoken answers', function () {
            $count = fn (string $set, Format ...$formats): int => $this->authored->filter(fn (AuthoredExercise $exercise): bool => $exercise->probeSet === $set && in_array($exercise->format, $formats, true))->count();

            foreach (['a', 'b'] as $set) {
                expect($count($set, Format::TranslateSentence))->toBe(4, $set)
                    ->and($count($set, Format::TypeGap))->toBe(2, $set)
                    ->and($count($set, Format::ListenType))->toBe(3, $set);
            }

            expect($count('a', Format::ListenPassage))->toBe(1)
                ->and($count('a', Format::ReadPassage))->toBe(1)
                ->and($count('a', Format::SpeakAnswer))->toBe(3)
                ->and($count('b', Format::ListenPassage, Format::ReadPassage, Format::SpeakAnswer))->toBe(0);
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

        it('names each word and the grammar point at most once as a target of an exercise', function () {
            foreach ($this->lessons as $lesson) {
                foreach ($lesson->exercises as $exercise) {
                    $keys = array_map(fn ($target): string => $target->ref->key(), $exercise->targets);

                    expect(count($keys))->toBe(count(array_unique($keys)), $exercise->key);
                }
            }
        });

        it('keeps keyword slots and required forms to single whole words, which is what the graders match', function () {
            foreach ($this->authored as $exercise) {
                $forms = $exercise->format === Format::SpeakAnswer
                    ? collect($exercise->payload['slots'])->flatten()->all()
                    : ($exercise->format === Format::WriteGuided ? collect($exercise->payload['required'])->pluck('forms')->flatten()->all() : []);

                foreach ($forms as $form) {
                    expect($form)->not->toContain(' ', $exercise->key);
                }
            }
        });

        it('puts the right number of distractor tiles in tile exercises', function () {
            foreach ($this->lessons as $lesson) {
                foreach ($lesson->exercises as $exercise) {
                    if ($exercise->format !== Format::BuildSentence || $exercise->substituteForKey !== null) {
                        continue;
                    }

                    $answer = $exercise->payload['accepted'][0]['text'];
                    $extra = count($exercise->payload['tiles']) - count(TileSplitter::split($answer));

                    expect($extra)->toBe($lesson->stage === LessonStage::Sentences ? 1 : 2, $exercise->key);
                }
            }
        });

        it('never builds a hint or a feedback into a check', function () {
            foreach ($this->authored->filter(fn (AuthoredExercise $exercise): bool => $exercise->stage === LessonStage::Check) as $exercise) {
                expect($exercise->payload)->not->toHaveKeys(['why', 'glosses', 'chips'], $exercise->key);
            }
        });

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

        it('practises the grammar point in six exercises of lesson 3, three of them production over three forms, and in four production exercises of lesson 4', function () {
            $production = [Format::TypeGap, Format::TranslateSentence, Format::TransformSentence, Format::BuildSentence, Format::ListenType];

            $grammar = fn (LessonStage $stage): Collection => collect($this->lessons[$stage->value]->exercises)
                ->filter(fn (ExerciseDefinition $exercise): bool => $exercise->substituteForKey === null && collect($exercise->targets)->contains(fn ($target): bool => $target->ref->isGrammar()));

            $three = $grammar(LessonStage::Sentences);
            $forms = $three->flatMap(fn (ExerciseDefinition $exercise): array => collect($exercise->targets)->filter(fn ($target): bool => $target->ref->isGrammar())->map(fn ($target): string => mb_strtolower((string) $target->form))->all())->unique();

            expect($three->count())->toBeGreaterThanOrEqual(6)
                ->and($three->filter(fn (ExerciseDefinition $exercise): bool => in_array($exercise->format, $production, true))->count())->toBeGreaterThanOrEqual(3)
                ->and($forms->count())->toBeGreaterThanOrEqual(3)
                ->and($grammar(LessonStage::Task)->filter(fn (ExerciseDefinition $exercise): bool => in_array($exercise->format, $production, true))->count())->toBeGreaterThanOrEqual(4);
        });

        it('has at least three contrast items in the lessons, and a why-note on every grammar gap', function () {
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
            $lessons = [...AuthoredContent::stageSentences($this->authored, $this->normalizer, LessonStage::Sentences), ...AuthoredContent::stageSentences($this->authored, $this->normalizer, LessonStage::Task)];
            $a = AuthoredContent::stageSentences($this->authored, $this->normalizer, LessonStage::Check, 'a');
            $b = AuthoredContent::stageSentences($this->authored, $this->normalizer, LessonStage::Check, 'b');

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

        it('uses only the unit words, the core words, the forms the content declares and the glossed words', function () {
            $known = [];

            foreach (CoreWords::words() as $word) {
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

        it('shows a gloss only where the player can show it', function () {
            $glossable = [Format::ChooseGap, Format::TypeGap, Format::TranslateSentence, Format::BuildSentence, Format::TransformSentence, Format::WriteGuided, Format::ReadPassage];

            foreach ($this->authored->filter(fn (AuthoredExercise $exercise): bool => isset($exercise->payload['glosses'])) as $exercise) {
                expect(in_array($exercise->format, $glossable, true))->toBeTrue($exercise->key)
                    ->and($exercise->stage)->not->toBe(LessonStage::Check, $exercise->key);
            }
        });

        it('marks every dictation that holds a word with a homophone', function () {
            $homophones = ['a', 'à', 'ou', 'où', 'est', 'et', 'ça', 'sa', 'ces', 'ses', 'mes', 'mais'];

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

        it('asks for every text the player may play, so speech:generate covers them', function () {
            app()->instance(UnitContentRegistry::class, new UnitContentRegistry([$this->content]));
            $corpus = app(SpeechCorpus::class)->texts('fr');
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

            expect($missing)->toBe([]);
        });
    });
}
