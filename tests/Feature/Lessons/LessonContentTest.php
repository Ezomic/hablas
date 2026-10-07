<?php

declare(strict_types=1);

use App\Actions\Lessons\BuildUnitLessons;
use App\Actions\Lessons\RenderLessonReviewSheet;
use App\Actions\Lessons\StartLessonRun;
use App\Actions\Lessons\SyncUnitLessons;
use App\Enums\ExerciseFamily;
use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Enums\MasteryScope;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\ContentReview;
use App\Lessons\ExerciseDefinition;
use App\Lessons\LessonDefinition;
use App\Lessons\PreviewContent;
use App\Lessons\ReviewGate;
use App\Lessons\UnitContent;
use App\Models\GrammarPoint;
use App\Models\Lesson;
use App\Models\SrsCard;
use App\Models\Unit;
use App\Models\UnitItemMastery;
use App\Models\VocabularyItem;
use App\Services\SpanishTextNormalizer;
use App\Services\UnitContentRegistry;
use Database\Seeders\ContentSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\SpanishA1Seeder;
use Tests\Fixtures\Lessons\ArrayContent;
use Tests\Fixtures\Lessons\LessonWorld;

const SPANISH_FAMILIES = [ExerciseFamily::Choice, ExerciseFamily::Writing];

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(SpanishA1Seeder::class);
    $this->contents = array_values(array_filter((new UnitContentRegistry)->all(), fn (UnitContent $content): bool => $content->languageCode() === 'es'));
    $this->unitOf = fn (UnitContent $content): Unit => Unit::query()->where('slug', $content->unitSlug())->whereHas('language', fn ($query) => $query->where('code', 'es'))->firstOrFail();
    $this->build = fn (UnitContent $content): array => (new BuildUnitLessons)->handle(($this->unitOf)($content), new PreviewContent($content), SPANISH_FAMILIES);
});

/** @return array<string, int> the number of originals per format */
function formatCounts(LessonDefinition $lesson): array
{
    $counts = [];

    foreach ($lesson->exercises as $exercise) {
        if ($exercise->substituteForKey === null) {
            $counts[$exercise->format->value] = ($counts[$exercise->format->value] ?? 0) + 1;
        }
    }

    return $counts;
}

describe('the Spanish word data', function () {
    it('has a class for every Spanish unit and for no other', function () {
        $slugs = collect($this->contents)->map(fn (UnitContent $content): string => $content->unitSlug())->sort()->values()->all();

        expect($slugs)->toBe(Unit::query()->whereHas('language', fn ($query) => $query->where('code', 'es'))->orderBy('slug')->pluck('slug')->all())
            ->and($this->contents)->toHaveCount(24)
            ->and(collect($this->contents)->every(fn (UnitContent $content): bool => $content->languageCode() === 'es'))->toBeTrue();
    });

    it('describes every vocabulary item of its unit, at least ten of them, and no other word', function () {
        foreach ($this->contents as $content) {
            $terms = ($this->unitOf)($content)->vocabularyItems()->orderBy('id')->pluck('term')->all();
            $described = array_map(fn ($word): string => $word->term, $content->words());

            expect($described)->toBe($terms)
                ->and(count($terms))->toBeGreaterThanOrEqual(10);
        }
    });

    it('gives every word a recall cue, and no two words of a unit the same one', function () {
        foreach ($this->contents as $content) {
            $cues = array_map(fn ($word): string => mb_strtolower((string) $word->cue), $content->words());

            expect($cues)->not->toContain('')
                ->and(array_unique($cues))->toHaveCount(count($content->words()));
        }
    });

    it('gives every unit two example sentences for its grammar card', function () {
        foreach ($this->contents as $content) {
            expect($content->grammarExamples())->toHaveCount(2);

            foreach ($content->grammarExamples() as $example) {
                expect($example['text'])->not->toBe('')->and($example['english'])->not->toBe('');
            }
        }
    });

    it('flags the common-gender noun, and only that one, with both articles accepted', function () {
        $flagged = [];

        foreach ($this->contents as $content) {
            foreach ($content->words() as $word) {
                if ($word->commonGender) {
                    $flagged[] = $word->term;
                }
            }
        }

        expect($flagged)->toBe(['el recepcionista']);
    });

    it('keeps Latin American forms out of the data', function () {
        $guard = require base_path('tests/Fixtures/Lessons/es-latam-forms.php');
        $normalizer = new SpanishTextNormalizer;

        foreach ($this->contents as $content) {
            $texts = [];

            foreach ($content->words() as $word) {
                array_push($texts, $word->term, ...$word->accepted, ...$word->forms);
            }

            foreach ($content->grammarExamples() as $example) {
                $texts[] = $example['text'];
            }

            $words = collect($texts)->flatMap(fn (string $text): array => explode(' ', $normalizer->exactKey($text)))->all();

            expect(array_intersect($words, $guard))->toBe([]);
        }
    });

    it('puts an article before every accepted answer of a noun', function () {
        foreach ($this->contents as $content) {
            $unit = ($this->unitOf)($content);
            $nouns = $unit->vocabularyItems->where('part_of_speech', 'noun')->pluck('term')->all();

            foreach ($content->words() as $word) {
                if (! in_array($word->term, $nouns, true)) {
                    continue;
                }

                foreach ([$word->term, ...$word->accepted] as $answer) {
                    expect((new SpanishTextNormalizer)->articles())->toContain(explode(' ', $answer)[0]);
                }
            }
        }
    });

    it('carries every open question to the review sheet and names the word it belongs to', function () {
        foreach ($this->contents as $content) {
            $terms = array_map(fn ($word): string => $word->term, $content->words());

            foreach ($content->words() as $word) {
                expect($terms)->toContain($word->term);

                foreach ($word->questions as $question) {
                    expect($question)->not->toContain('—')->and(mb_strlen($question))->toBeGreaterThan(20);
                }
            }
        }
    });
});

describe('the Spanish lesson text', function () {
    it('glosses los pantalones as trousers, which is not underwear in British English', function () {
        $item = VocabularyItem::query()->where('term', 'los pantalones')->sole();
        $content = collect($this->contents)->first(fn (UnitContent $content): bool => $content->unitSlug() === 'shopping-for-clothes');
        $word = collect($content->words())->first(fn ($word): bool => $word->term === 'los pantalones');

        expect($item->translation_en)->toBe('trousers')
            ->and($word->cue)->toBe('trousers');
    });

    it('tells the learner on the teach card that el menú is the list of dishes and la carta is also accepted', function () {
        $content = collect($this->contents)->first(fn (UnitContent $content): bool => $content->unitSlug() === 'ordering-food-at-a-restaurant');
        $lessons = ($this->build)($content);
        $teach = collect($lessons[0]->exercises)->first(fn (ExerciseDefinition $exercise): bool => $exercise->key === 'meet.teach_word.el-menu');

        expect($teach->payload['contrast_note'])->toContain('list of dishes')->toContain('la carta')
            ->and(collect($content->words())->first(fn ($word): bool => $word->term === 'el menú')->accepted)->toContain('la carta');
    });

    it('explains reflexive verbs without saying Dutch has none', function () {
        $card = Unit::query()->where('slug', 'describing-your-daily-routine')->whereHas('language', fn ($query) => $query->where('code', 'es'))->firstOrFail()->grammarPoints->sole();

        expect($card->explanation)->toContain('zich')->not->toContain('Dutch phrasing');
    });

    it('writes no dash as punctuation in a grammar card', function () {
        $cards = GrammarPoint::query()->whereHas('language', fn ($query) => $query->where('code', 'es'))->pluck('explanation');

        expect($cards)->toHaveCount(24);

        foreach ($cards as $explanation) {
            expect($explanation)->not->toMatch('/[—–]| -- /u');
        }
    });

    it('prints a phrase ending in three dots with all three on the review sheet', function () {
        $unit = Unit::query()->where('slug', 'asking-for-directions')->whereHas('language', fn ($query) => $query->where('code', 'es'))->firstOrFail();
        $content = collect($this->contents)->first(fn (UnitContent $content): bool => $content->unitSlug() === 'asking-for-directions');

        $sheet = (new RenderLessonReviewSheet)->handle($unit, $content);

        expect($sheet)->toContain('| ¿dónde está...? | phrase | where is...? |')->not->toContain('está..?');
    });
});

describe('the review gate', function () {
    it('releases the words and the lessons of every unit on its two reviews', function () {
        foreach ($this->contents as $content) {
            expect(ReviewGate::wordsReleased($content))->toBeTrue()
                ->and(ReviewGate::lessonsReleased($content))->toBeTrue()
                ->and(collect($content->reviews())->contains(fn ($review): bool => $review->kind === ReviewKind::IndependentAi && $review->scope === ReviewScope::Words))->toBeTrue();
        }
    });

    it('releases nothing on any review other than the independent AI review of the words', function (array $reviews) {
        $content = new ArrayContent(reviews: $reviews);

        expect(ReviewGate::wordsReleased($content))->toBeFalse()
            ->and(ReviewGate::lessonsReleased($content))->toBeFalse();
    })->with([
        'no review' => [[]],
        'the owner on the words' => [[new ContentReview(ReviewKind::Owner, ReviewScope::Words, 'owner', '2026-10-01')]],
        'the independent AI on the lessons' => [[new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'ai', '2026-10-01')]],
    ]);

    it('seeds every lesson of the released Spanish, French and Italian units', function () {
        $this->seed(ContentSeeder::class);

        expect(Lesson::query()->count())->toBe(200)
            ->and(Lesson::query()->pluck('stage')->map(fn (LessonStage $stage): string => $stage->value)->unique()->sort()->values()->all())->toBe(['check', 'meet', 'recall', 'sentences', 'task']);
    });

    it('seeds nothing for a unit that has no review recorded, and it stays unreachable', function () {
        app()->instance(UnitContentRegistry::class, new UnitContentRegistry([new ArrayContent(words: [], reviews: [])]));

        $this->seed(ContentSeeder::class);

        expect(Lesson::query()->count())->toBe(0)
            ->and(Lesson::query()->playable()->count())->toBe(0);
    });
});

describe('the word lessons once reviewed', function () {
    it('build lessons 1 and 2 and a words-only check for every unit', function () {
        foreach ($this->contents as $content) {
            $lessons = collect(($this->build)($content))->keyBy(fn (LessonDefinition $lesson): string => $lesson->stage->value);

            $words = count($content->words());

            expect($lessons->keys()->all())->toBe(['meet', 'recall', 'check'])
                ->and(formatCounts($lessons['meet']))->toBe(['teach_word' => $words, 'choose_meaning' => $words, 'type_word' => $words, 'match_pairs' => (int) ceil($words / 5) - ($words % 5 === 1 ? 1 : 0)])
                ->and(formatCounts($lessons['recall']))->toBe(['choose_word' => $words, 'type_word' => $words])
                ->and(formatCounts($lessons['check']))->toBe(['type_word' => $words * 2]);
        }
    });

    it('practise every word in at least three graded exercises of two kinds in lesson 1', function () {
        foreach ($this->contents as $content) {
            $meet = collect(($this->build)($content))->first(fn (LessonDefinition $lesson): bool => $lesson->stage === LessonStage::Meet);
            $byItem = [];

            foreach ($meet->exercises as $exercise) {
                if ($exercise->format->isTeach()) {
                    continue;
                }

                foreach ($exercise->targets as $target) {
                    $byItem[$target->ref->key()][$exercise->format->family()?->value][] = $exercise->key;
                }
            }

            expect($byItem)->toHaveCount(count($content->words()));

            foreach ($byItem as $families) {
                expect(array_sum(array_map('count', $families)))->toBeGreaterThanOrEqual(3)
                    ->and(count($families))->toBeGreaterThanOrEqual(2);
            }
        }
    });

    it('offer four distinct options with the answer among them, and no other option is also accepted', function () {
        $normalizer = new SpanishTextNormalizer;

        foreach ($this->contents as $content) {
            $accepted = [];

            foreach ($content->words() as $word) {
                $accepted[$word->term] = array_map($normalizer->answerKey(...), [$word->term, ...$word->accepted]);
            }

            foreach (($this->build)($content) as $lesson) {
                foreach ($lesson->exercises as $exercise) {
                    if (! $exercise->format->isChoice() || $exercise->format === Format::ChooseGap) {
                        continue;
                    }

                    $options = $exercise->payload['options'];
                    $keys = array_map($normalizer->answerKey(...), $options);

                    expect($options)->toHaveCount(4)
                        ->and(array_unique($keys))->toHaveCount(4)
                        ->and($options)->toContain($exercise->payload['answer']);

                    if ($exercise->format === Format::ChooseWord) {
                        $others = array_values(array_diff($keys, [$normalizer->answerKey($exercise->payload['answer'])]));
                        $term = collect(array_keys($accepted))->first(fn (string $term): bool => $term === $exercise->payload['answer']);

                        expect(array_intersect($others, $accepted[$term] ?? []))->toBe([]);
                    }
                }
            }
        }
    });

    it('never offer the wrong article of a common-gender noun', function () {
        $content = collect($this->contents)->first(fn (UnitContent $content): bool => $content->unitSlug() === 'checking-into-a-hotel');
        $options = collect(($this->build)($content))->flatMap(fn (LessonDefinition $lesson): array => $lesson->exercises)->flatMap(fn (ExerciseDefinition $exercise): array => $exercise->payload['options'] ?? [])->all();

        expect($options)->not->toContain('la recepcionista');
    });

    it('are graded right when answered with their own accepted answers, and prove every word (the daily cap holds the cards back)', function () {
        $user = LessonWorld::learner();

        foreach ($this->contents as $content) {
            $unit = ($this->unitOf)($content);
            (new SyncUnitLessons)->handle($unit, ($this->build)($content));

            LessonWorld::finishTeachingLessons($user, $unit);
            $check = LessonWorld::play($user, (new StartLessonRun)->handle($user, LessonWorld::lesson($unit, LessonStage::Check), LessonRunKind::Check));

            expect($check->result['missing'])->toBe([])
                ->and($check->result['mastered'])->toHaveCount(count($content->words()))
                ->and($check->result['unit_completed'])->toBeFalse()
                ->and(UnitItemMastery::query()->where('user_id', $user->id)->where('unit_id', $unit->id)->where('scope', MasteryScope::Words)->count())->toBe(count($content->words()));
        }

        expect(SrsCard::query()->where('user_id', $user->id)->count())->toBe(10);
    });

    it('make the unit show its words, with the sentence and grammar lessons still to come', function () {
        $content = collect($this->contents)->first(fn (UnitContent $content): bool => $content->unitSlug() === 'at-the-airport');
        $unit = ($this->unitOf)($content);
        (new SyncUnitLessons)->handle($unit, ($this->build)($content));

        expect(VocabularyItem::query()->where('unit_id', $unit->id)->count())->toBe(count($content->words()))
            ->and(Lesson::query()->where('unit_id', $unit->id)->orderBy('position')->pluck('stage')->map(fn (LessonStage $stage): string => $stage->value)->all())->toBe(['meet', 'recall', 'check']);
    });
});
