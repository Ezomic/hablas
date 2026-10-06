<?php

declare(strict_types=1);

use App\Actions\Lessons\BuildUnitLessons;
use App\Actions\Lessons\GetUnitLessonOverview;
use App\Actions\Lessons\RenderLessonReviewSheet;
use App\Actions\Lessons\StartLessonRun;
use App\Actions\Lessons\SyncUnitLessons;
use App\Enums\ExerciseFamily;
use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Enums\MasteryScope;
use App\Lessons\ExerciseDefinition;
use App\Lessons\LessonDefinition;
use App\Lessons\PreviewContent;
use App\Lessons\ReviewGate;
use App\Lessons\UnitContent;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\Lesson;
use App\Models\LessonExercise;
use App\Models\SrsCard;
use App\Models\Unit;
use App\Models\UnitItemMastery;
use App\Models\VocabularyItem;
use App\Services\PortugueseTextNormalizer;
use App\Services\UnitContentRegistry;
use Database\Seeders\ContentSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\PortugueseA1Seeder;
use Tests\Fixtures\Lessons\LessonWorld;
use Tests\Support\PortugalGuard;

const PORTUGUESE_FAMILIES = [ExerciseFamily::Choice, ExerciseFamily::Writing];

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(PortugueseA1Seeder::class);
    $this->contents = array_values(array_filter((new UnitContentRegistry)->all(), fn (UnitContent $content): bool => $content->languageCode() === 'pt'));
    $this->unitOf = fn (UnitContent $content): Unit => Unit::query()->where('slug', $content->unitSlug())->whereHas('language', fn ($query) => $query->where('code', 'pt'))->firstOrFail();
    $this->build = fn (UnitContent $content): array => (new BuildUnitLessons)->handle(($this->unitOf)($content), new PreviewContent($content), PORTUGUESE_FAMILIES);
    $this->guard = require base_path('tests/Fixtures/Lessons/pt-br-forms.php');
});

/** @return array<string, int> the number of originals per format */
function portugueseFormatCounts(LessonDefinition $lesson): array
{
    $counts = [];

    foreach ($lesson->exercises as $exercise) {
        if ($exercise->substituteForKey === null) {
            $counts[$exercise->format->value] = ($counts[$exercise->format->value] ?? 0) + 1;
        }
    }

    return $counts;
}

describe('the Portuguese word data', function () {
    it('has a class for every Portuguese unit, all of them Portuguese', function () {
        $slugs = collect($this->contents)->map(fn (UnitContent $content): string => $content->unitSlug())->sort()->values()->all();

        expect($slugs)->toBe(Unit::query()->whereHas('language', fn ($query) => $query->where('code', 'pt'))->orderBy('slug')->pluck('slug')->all())
            ->and($this->contents)->toHaveCount(8)
            ->and(collect($this->contents)->every(fn (UnitContent $content): bool => $content->languageCode() === 'pt'))->toBeTrue();
    });

    it('describes every vocabulary item of its unit, ten of them, and no other word', function () {
        foreach ($this->contents as $content) {
            $terms = ($this->unitOf)($content)->vocabularyItems()->orderBy('id')->pluck('term')->all();
            $described = array_map(fn ($word): string => $word->term, $content->words());

            expect($described)->toBe($terms)
                ->and($terms)->toHaveCount(10);
        }
    });

    it('gives every word a recall cue, and no two words of a unit the same one', function () {
        foreach ($this->contents as $content) {
            $cues = array_map(fn ($word): string => mb_strtolower((string) $word->cue), $content->words());

            expect($cues)->not->toContain('')
                ->and(array_unique($cues))->toHaveCount(10);
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

        expect($flagged)->toBe(['o rececionista']);
    });

    it('keeps Brazilian forms and Brazilian pronoun placement out of the data', function () {
        foreach ($this->contents as $content) {
            $texts = [];

            foreach ($content->words() as $word) {
                array_push($texts, $word->term, ...$word->accepted, ...$word->forms);
            }

            foreach ($content->grammarExamples() as $example) {
                $texts[] = $example['text'];
            }

            foreach ($texts as $text) {
                expect(PortugalGuard::violations($text, $this->guard))->toBe([], $text);
            }
        }
    });

    it('puts an article before every accepted answer of a noun', function () {
        foreach ($this->contents as $content) {
            $nouns = ($this->unitOf)($content)->vocabularyItems->where('part_of_speech', 'noun')->pluck('term')->all();

            foreach ($content->words() as $word) {
                if (! in_array($word->term, $nouns, true)) {
                    continue;
                }

                foreach ([$word->term, ...$word->accepted] as $answer) {
                    expect((new PortugueseTextNormalizer)->articles())->toContain(explode(' ', $answer)[0]);
                }
            }
        }
    });

    it('declares Spanish slips that are Spanish, never accepted as an answer', function () {
        $normalizer = new PortugueseTextNormalizer;
        $declared = 0;

        foreach ($this->contents as $content) {
            foreach ($content->words() as $word) {
                $accepted = array_map($normalizer->answerKey(...), [$word->term, ...$word->accepted, ...$word->forms]);

                foreach ($word->portunolSlips as $slip) {
                    $declared++;

                    expect($accepted)->not->toContain($normalizer->answerKey($slip), $slip);
                }
            }
        }

        expect($declared)->toBeGreaterThanOrEqual(8);
    });

    it('carries every open question to the review sheet, in plain words', function () {
        foreach ($this->contents as $content) {
            foreach ($content->words() as $word) {
                foreach ($word->questions as $question) {
                    expect($question)->not->toMatch('/[—–]| -- /u')->and(mb_strlen($question))->toBeGreaterThan(20);
                }
            }
        }
    });

    it('prints the open questions of every unit on its review sheet', function () {
        $asked = 0;

        foreach ($this->contents as $content) {
            $sheet = (new RenderLessonReviewSheet)->handle(($this->unitOf)($content), $content);

            foreach ($content->words() as $word) {
                foreach ($word->questions as $question) {
                    $asked++;

                    expect($sheet)->toContain("- {$word->term}: {$question}");
                }
            }
        }

        expect($asked)->toBeGreaterThanOrEqual(8);
    });

    it('gives the reviewer a checklist about European Portuguese, not the Spanish one', function () {
        $content = $this->contents[0];
        $sheet = (new RenderLessonReviewSheet)->handle(($this->unitOf)($content), $content);

        expect($sheet)->toContain('natural in Portugal')->toContain('European, not Brazilian')->toContain('Portuñol')
            ->not->toContain('Spain, not Latin America');
    });
});

describe('the Portuguese lesson text', function () {
    it('spells the receptionist with the 1990 agreement, and glosses the airport exit as an exit', function () {
        expect(VocabularyItem::query()->where('term', 'o rececionista')->count())->toBe(1)
            ->and(VocabularyItem::query()->where('term', 'o recepcionista')->count())->toBe(0)
            ->and(VocabularyItem::query()->where('term', 'a saída')->sole()->translation_en)->toBe('exit')
            ->and(VocabularyItem::query()->where('term', 'as calças')->sole()->translation_en)->toBe('trousers');
    });

    it('writes no dash as punctuation in a unit note, a word note or a grammar card', function () {
        $portuguese = Language::query()->where('code', 'pt')->sole();
        $texts = [
            ...GrammarPoint::query()->where('language_id', $portuguese->id)->pluck('explanation')->all(),
            ...Unit::query()->where('language_id', $portuguese->id)->whereNotNull('contrast_note')->pluck('contrast_note')->all(),
            ...VocabularyItem::query()->where('language_id', $portuguese->id)->whereNotNull('contrast_note')->pluck('contrast_note')->all(),
        ];

        expect($texts)->not->toBeEmpty();

        foreach ($texts as $text) {
            expect($text)->not->toMatch('/[—–]| -- /u');
        }
    });

    it('keeps the Brazilian forms out of every grammar card, unit note and word note', function () {
        $guard = require base_path('tests/Fixtures/Lessons/pt-br-forms.php');
        $portuguese = Language::query()->where('code', 'pt')->sole();
        $terms = VocabularyItem::query()->where('language_id', $portuguese->id)->pluck('term')->all();

        foreach ($terms as $term) {
            expect(PortugalGuard::violations($term, $guard))->toBe([], $term);
        }
    });
});

describe('the Portuguese review gate', function () {
    it('releases nothing: no unit has its words released, so the course stays as it is today', function () {
        foreach ($this->contents as $content) {
            expect(ReviewGate::wordsReleased($content))->toBeFalse()
                ->and(ReviewGate::lessonsReleased($content))->toBeFalse()
                ->and($content->reviews())->toBe([]);
        }
    });

    it('seeds no lesson for a Portuguese unit and leaves Spanish alone', function () {
        $this->seed(ContentSeeder::class);

        $portuguese = Unit::query()->whereHas('language', fn ($query) => $query->where('code', 'pt'))->pluck('id');

        expect(Lesson::query()->whereIn('unit_id', $portuguese)->count())->toBe(0)
            ->and(Lesson::query()->whereNotIn('unit_id', $portuguese)->count())->toBe(120);
    });

    it('shows a Portuguese learner every lesson of every Portuguese unit as still coming, with nothing to start', function () {
        $this->seed(ContentSeeder::class);
        $user = LessonWorld::learner(language: LessonWorld::portuguese());

        foreach (Unit::query()->whereHas('language', fn ($query) => $query->where('code', 'pt'))->get() as $unit) {
            $overview = (new GetUnitLessonOverview)->handle($user, $unit);

            expect(array_column($overview['lessons'], 'state'))->toBe(['coming', 'coming', 'coming', 'coming', 'coming'], $unit->slug)
                ->and(array_column($overview['lessons'], 'lessonId'))->toBe([null, null, null, null, null], $unit->slug)
                ->and($overview['contentPending'])->toBeFalse()
                ->and($overview['canTestOut'])->toBeFalse();
        }
    });

    it('seeds every Spanish exercise to the same row and hash with the Portuguese content present as without it', function () {
        $rows = fn (): array => LessonExercise::query()->whereHas('lesson.unit.language', fn ($query) => $query->where('code', 'es'))->orderBy('id')->get(['key', 'content_hash'])->map(fn (LessonExercise $exercise): string => $exercise->key.'#'.$exercise->content_hash)->all();

        $spanishOnly = array_values(array_filter((new UnitContentRegistry)->all(), fn (UnitContent $content): bool => $content->languageCode() === 'es'));
        app()->instance(UnitContentRegistry::class, new UnitContentRegistry($spanishOnly));
        $this->seed(ContentSeeder::class);
        $before = $rows();

        app()->instance(UnitContentRegistry::class, new UnitContentRegistry((new UnitContentRegistry)->all()));
        $this->seed(ContentSeeder::class);

        expect($before)->not->toBeEmpty()
            ->and($rows())->toBe($before);
    });

    it('renames a corrected term in place, so its cards and its id stay', function () {
        $unit = Unit::query()->where('slug', 'checking-into-a-hotel')->whereHas('language', fn ($query) => $query->where('code', 'pt'))->firstOrFail();
        $item = VocabularyItem::query()->where('unit_id', $unit->id)->where('term', 'o rececionista')->sole();
        $item->forceFill(['term' => 'o recepcionista'])->save();
        $user = LessonWorld::learner(language: LessonWorld::portuguese());
        $card = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $item->language_id, 'cardable_type' => VocabularyItem::class, 'cardable_id' => $item->id]);

        $this->seed(PortugueseA1Seeder::class);

        expect(VocabularyItem::query()->where('unit_id', $unit->id)->count())->toBe(10)
            ->and($item->fresh()?->term)->toBe('o rececionista')
            ->and(SrsCard::query()->whereKey($card->id)->value('cardable_id'))->toBe($item->id);
    });
});

describe('the Portuguese word lessons once reviewed', function () {
    it('build lessons 1 and 2 and a words-only check for every unit', function () {
        foreach ($this->contents as $content) {
            $lessons = collect(($this->build)($content))->keyBy(fn (LessonDefinition $lesson): string => $lesson->stage->value);

            expect($lessons->keys()->all())->toBe(['meet', 'recall', 'check'])
                ->and(portugueseFormatCounts($lessons['meet']))->toBe(['teach_word' => 10, 'choose_meaning' => 10, 'type_word' => 10, 'match_pairs' => 2])
                ->and(portugueseFormatCounts($lessons['recall']))->toBe(['choose_word' => 10, 'type_word' => 10])
                ->and(portugueseFormatCounts($lessons['check']))->toBe(['type_word' => 20]);
        }
    });

    it('offer four distinct options with the answer among them, and no other option is also accepted', function () {
        $normalizer = new PortugueseTextNormalizer;

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

                        expect(array_intersect($others, $accepted[$exercise->payload['answer']] ?? []))->toBe([]);
                    }
                }
            }
        }
    });

    it('never offer the wrong article of the common-gender noun', function () {
        $content = collect($this->contents)->first(fn (UnitContent $content): bool => $content->unitSlug() === 'checking-into-a-hotel');
        $options = collect(($this->build)($content))->flatMap(fn (LessonDefinition $lesson): array => $lesson->exercises)->flatMap(fn (ExerciseDefinition $exercise): array => $exercise->payload['options'] ?? [])->all();

        expect($options)->not->toContain('a rececionista');
    });

    it('are graded right when answered with their own accepted answers, and prove every word', function () {
        $user = LessonWorld::learner(language: LessonWorld::portuguese());

        foreach ($this->contents as $content) {
            $unit = ($this->unitOf)($content);
            (new SyncUnitLessons)->handle($unit, ($this->build)($content));

            LessonWorld::finishTeachingLessons($user, $unit);
            $check = LessonWorld::play($user, (new StartLessonRun)->handle($user, LessonWorld::lesson($unit, LessonStage::Check), LessonRunKind::Check));

            expect($check->result['missing'])->toBe([])
                ->and($check->result['mastered'])->toHaveCount(10)
                ->and($check->result['unit_completed'])->toBeFalse()
                ->and(UnitItemMastery::query()->where('user_id', $user->id)->where('unit_id', $unit->id)->where('scope', MasteryScope::Words)->count())->toBe(10);
        }

        expect(SrsCard::query()->where('user_id', $user->id)->count())->toBe(10);
    });

    it('make the unit show its words, with the sentence and grammar lessons still to come', function () {
        $content = collect($this->contents)->first(fn (UnitContent $content): bool => $content->unitSlug() === 'at-the-airport');
        $unit = ($this->unitOf)($content);
        (new SyncUnitLessons)->handle($unit, ($this->build)($content));

        expect(VocabularyItem::query()->where('unit_id', $unit->id)->count())->toBe(10)
            ->and(Lesson::query()->where('unit_id', $unit->id)->orderBy('position')->pluck('stage')->map(fn (LessonStage $stage): string => $stage->value)->all())->toBe(['meet', 'recall', 'check']);
    });
});

describe('the Portugal guard', function () {
    it('fails on a Brazilian word, a Brazilian phrase and a Brazilian gerund', function (string $text) {
        expect(PortugalGuard::violations($text, require base_path('tests/Fixtures/Lessons/pt-br-forms.php')))->not->toBe([]);
    })->with([
        'ônibus' => ['O ônibus está atrasado.'],
        'trem' => ['O trem chega cedo.'],
        'banheiro' => ['Onde fica o banheiro?'],
        'café da manhã' => ['O café da manhã está incluído.'],
        'celular' => ['O meu celular está aqui.'],
        'recepcionista' => ['O recepcionista é simpático.'],
        'recepção' => ['A recepção fica aqui.'],
        'estou falando' => ['Estou falando com a Ana.'],
        'está comendo' => ['Ele está comendo.'],
        'você' => ['Você é a Ana?'],
        'Me chamo' => ['Me chamo Rui.'],
        'Eu me levanto' => ['Eu me levanto cedo.'],
        'Ele se chama' => ['Ele se chama Rui.'],
        'Não levanto-me' => ['Não levanto-me cedo.'],
        'Nunca deito-me' => ['Nunca deito-me tarde.'],
    ]);

    it('passes European Portuguese, including the clitics it requires', function (string $text) {
        expect(PortugalGuard::violations($text, require base_path('tests/Fixtures/Lessons/pt-br-forms.php')))->toBe([]);
    })->with([
        'autocarro' => ['O autocarro está atrasado.'],
        'pequeno-almoço' => ['O pequeno-almoço está incluído.'],
        'casa de banho' => ['A casa de banho fica aqui.'],
        'estar a' => ['Estou a falar com a Ana.'],
        'quando' => ['Está quando?'],
        'Chamo-me' => ['Chamo-me Rui.'],
        'Levanto-me' => ['Levanto-me cedo.'],
        'Não me levanto' => ['Não me levanto cedo.'],
        'Eu não me levanto' => ['Eu não me levanto cedo.'],
        'Ela diz que se chama Ana' => ['Ela diz que ela se chama Ana.'],
        'Todos os dias levanto-me' => ['Todos os dias levanto-me cedo.'],
        'infinitive with clitic after não' => ['Não vou levantar-me cedo.'],
        'Como te chamas' => ['Como te chamas?'],
        'a lone slot word' => ['me'],
    ]);
});
