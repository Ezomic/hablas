<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Enums\ExerciseFamily;
use App\Enums\LessonExerciseFormat;
use App\Enums\LessonStage;
use App\Lessons\ArticleSwapper;
use App\Lessons\AuthoredExercise;
use App\Lessons\AuthoredExercises;
use App\Lessons\BuildContext;
use App\Lessons\ExerciseDefinition;
use App\Lessons\ExerciseSink;
use App\Lessons\InvalidLessonContent;
use App\Lessons\ItemInfo;
use App\Lessons\LessonDefinition;
use App\Lessons\ReviewGate;
use App\Lessons\SubstituteBuilder;
use App\Lessons\TargetDefinition;
use App\Lessons\TargetRef;
use App\Lessons\UnitContent;
use App\Lessons\WordData;
use App\Lessons\WordExercises;
use App\Models\Unit;
use App\Services\TextNormalizerResolver;
use Illuminate\Support\Str;

final class BuildUnitLessons
{
    public function __construct(
        private readonly TextNormalizerResolver $textNormalizerResolver = new TextNormalizerResolver,
        private readonly WordExercises $wordExercises = new WordExercises,
        private readonly AuthoredExercises $authoredExercises = new AuthoredExercises,
        private readonly SubstituteBuilder $substituteBuilder = new SubstituteBuilder,
        private readonly ArticleSwapper $articleSwapper = new ArticleSwapper,
    ) {}

    /**
     * Lays out a unit's lessons from one ramp, the part-of-speech rules and
     * the unit's reviewed content, so no unit can be built differently from
     * another. Pure: nothing is written, and nothing is built for content that
     * has not passed its review.
     *
     * @param  list<ExerciseFamily>  $families  the exercise families to include
     * @return list<LessonDefinition>
     */
    public function handle(Unit $unit, UnitContent $content, array $families = [ExerciseFamily::Choice, ExerciseFamily::Writing, ExerciseFamily::Listening, ExerciseFamily::Speaking]): array
    {
        if (! ReviewGate::wordsReleased($content)) {
            return [];
        }

        $unit->loadMissing(['language', 'vocabularyItems', 'grammarPoints']);

        $language = $unit->language ?? throw new InvalidLessonContent("Unit {$unit->slug} has no language.");

        $context = new BuildContext(
            $unit,
            $content,
            $this->textNormalizerResolver->forLanguage($language),
            $this->items($unit, $content),
            $unit->grammarPoints->first(),
            $families,
        );

        $authored = ReviewGate::lessonsReleased($content) ? $this->authored($context) : [];

        $lessons = [
            $this->lesson($context, LessonStage::Meet, $authored),
            $this->lesson($context, LessonStage::Recall, $authored),
            $this->lesson($context, LessonStage::Sentences, $authored),
            $this->lesson($context, LessonStage::Task, $authored),
            $this->lesson($context, LessonStage::Check, $authored),
        ];

        return array_values(array_filter($lessons, fn (?LessonDefinition $lesson): bool => $lesson !== null));
    }

    /**
     * @param  array<string, list<AuthoredExercise>>  $authored  by stage value
     */
    private function lesson(BuildContext $context, LessonStage $stage, array $authored): ?LessonDefinition
    {
        $sink = new ExerciseSink($stage, $this->substituteBuilder);

        match ($stage) {
            LessonStage::Meet => $this->wordExercises->meet($context, $sink),
            LessonStage::Recall => $this->wordExercises->recall($context, $sink),
            LessonStage::Sentences => $this->teachGrammar($context, $sink, $authored),
            LessonStage::Check => $this->check($context, $sink),
            default => null,
        };

        foreach ($authored[$stage->value] ?? [] as $exercise) {
            $this->authoredExercises->add($context, $exercise, $sink);
        }

        if ($sink->isEmpty()) {
            return null;
        }

        $exercises = $sink->all();

        if ($stage === LessonStage::Meet) {
            $this->assertTeachFirst($exercises);
        }

        return new LessonDefinition($stage, $stage->title(), $stage->position(), $exercises);
    }

    /**
     * The rule is taught at the start of the lesson that practises it, so it is
     * followed at once by its exercises. A unit with no authored sentences has
     * nothing to practise it with, so it teaches no rule.
     *
     * @param  array<string, list<AuthoredExercise>>  $authored  by stage value
     */
    private function teachGrammar(BuildContext $context, ExerciseSink $sink, array $authored): void
    {
        if ($context->grammar === null || ($authored[LessonStage::Sentences->value] ?? []) === []) {
            return;
        }

        $sink->add('sentences.teach_grammar.'.Str::slug($context->grammar->title), 'grammar', LessonExerciseFormat::TeachGrammar, [
            'title' => $context->grammar->title,
            'explanation' => $context->grammar->explanation,
            'examples' => $context->content->grammarExamples(),
        ], [new TargetDefinition(TargetRef::for($context->grammar), false, false, null)]);
    }

    private function check(BuildContext $context, ExerciseSink $sink): void
    {
        foreach (['a', 'b'] as $set) {
            $this->wordExercises->checkRecall($context, $sink, $set);
        }
    }

    /**
     * @return array<string, list<AuthoredExercise>>
     */
    private function authored(BuildContext $context): array
    {
        $byStage = [];

        foreach ($context->content->exercises() as $exercise) {
            $family = $exercise->format->family();

            if ($family !== null && ! $context->builds($family)) {
                continue;
            }

            $byStage[$exercise->stage->value][] = $exercise;
        }

        return $byStage;
    }

    /**
     * @return list<ItemInfo>
     */
    private function items(Unit $unit, UnitContent $content): array
    {
        $data = [];

        foreach ($content->words() as $word) {
            $data[$word->term] = $word;
        }

        $terms = $unit->vocabularyItems->pluck('term')->all();

        foreach (array_keys($data) as $term) {
            if (! in_array($term, $terms, true)) {
                throw new InvalidLessonContent("Word data names '{$term}', which is not a vocabulary item of {$unit->slug}.");
            }
        }

        $infos = [];
        $cues = [];

        foreach ($unit->vocabularyItems->sortBy('id')->values() as $index => $item) {
            $word = $data[$item->term] ?? new WordData($item->term);
            $cue = $word->cue ?? $item->translation_en;

            if (isset($cues[mb_strtolower($cue)])) {
                throw new InvalidLessonContent("Two items of {$unit->slug} share the recall cue '{$cue}'.");
            }

            $cues[mb_strtolower($cue)] = true;
            $infos[] = new ItemInfo($item, $word, $cue, $this->accepted($unit, $item->term, $word), $index);
        }

        return $infos;
    }

    /**
     * @return list<string>
     */
    private function accepted(Unit $unit, string $term, WordData $word): array
    {
        $answers = [$term, ...$word->accepted];

        if (! $word->commonGender) {
            return $answers;
        }

        $language = $unit->language ?? throw new InvalidLessonContent("Unit {$unit->slug} has no language.");
        $normalizer = $this->textNormalizerResolver->forLanguage($language);

        foreach ([$term, ...$word->accepted] as $answer) {
            $swapped = $this->articleSwapper->swap($normalizer, $answer);

            if ($swapped !== null) {
                $answers[] = $swapped;
            }
        }

        return array_values(array_unique($answers));
    }

    /**
     * No exercise on an item comes before its teach card and its first
     * recognition exercise, so a learner is never tested on a word he has
     * not been shown.
     *
     * @param  list<ExerciseDefinition>  $exercises
     */
    private function assertTeachFirst(array $exercises): void
    {
        $state = [];

        foreach ($exercises as $exercise) {
            if ($exercise->substituteForKey !== null || $exercise->format === LessonExerciseFormat::MatchPairs) {
                continue;
            }

            foreach ($exercise->targets as $target) {
                $key = $target->ref->key();
                $seen = $state[$key] ?? 0;

                if ($exercise->format === LessonExerciseFormat::TeachWord) {
                    $state[$key] = 1;

                    continue;
                }

                $recognition = in_array($exercise->format, [LessonExerciseFormat::ChooseMeaning, LessonExerciseFormat::ListenChoose], true);

                if ($seen === 0 || ($seen === 1 && ! $recognition)) {
                    throw new InvalidLessonContent("Lesson 1 tests '{$exercise->key}' before its teach card and first recognition exercise.");
                }

                $state[$key] = 2;
            }
        }
    }
}
