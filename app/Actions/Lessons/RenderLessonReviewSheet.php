<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Enums\LessonExerciseFormat;
use App\Enums\LessonStage;
use App\Lessons\ExerciseDefinition;
use App\Lessons\InvalidLessonContent;
use App\Lessons\PreviewContent;
use App\Lessons\UnitContent;
use App\Lessons\WordData;
use App\Models\Unit;

final class RenderLessonReviewSheet
{
    public function __construct(
        private readonly BuildUnitLessons $buildUnitLessons = new BuildUnitLessons,
    ) {}

    /**
     * A unit's content as a Markdown sheet for a reviewer: every word with its
     * cue, accepted answers, forms and open questions, the grammar card, and
     * every exercise the words generate, with the options and answers a
     * learner is graded against. The owner's sheet leaves out the check, so
     * he never reads an answer key before taking it.
     */
    public function handle(Unit $unit, UnitContent $content, bool $forOwner = false): string
    {
        $unit->loadMissing(['vocabularyItems', 'grammarPoints']);

        $lines = [
            "# Review sheet: {$unit->title}",
            '',
            "Language: {$content->languageCode()}. Unit: {$unit->slug}. Level: {$unit->cefr_level->value}.",
            '',
            "Task: {$unit->task_description}",
            '',
            '## Words',
            '',
            '| Term | Part of speech | Gloss | Recall cue | Also accepted | Forms | Common gender |',
            '|---|---|---|---|---|---|---|',
        ];

        $data = [];

        foreach ($content->words() as $word) {
            $data[$word->term] = $word;
        }

        foreach ($unit->vocabularyItems->sortBy('id') as $item) {
            $word = $data[$item->term] ?? new WordData($item->term);
            $lines[] = '| '.implode(' | ', [
                $item->term,
                $item->part_of_speech,
                $item->translation_en,
                $word->cue ?? $item->translation_en,
                $this->cell($word->accepted),
                $this->cell($word->forms),
                $word->commonGender ? 'yes' : 'no',
            ]).' |';
        }

        array_push($lines, ...$this->questions($content));
        array_push($lines, ...$this->grammar($unit, $content));
        array_push($lines, ...$this->exercises($unit, $content, $forOwner));

        return implode("\n", $lines)."\n";
    }

    /** @return list<string> */
    private function questions(UnitContent $content): array
    {
        $lines = [];

        foreach ($content->words() as $word) {
            foreach ($word->questions as $question) {
                $lines[] = "- {$word->term}: {$question}";
            }
        }

        return $lines === [] ? [] : ['', '## Questions for the reviewer', '', ...$lines];
    }

    /** @return list<string> */
    private function grammar(Unit $unit, UnitContent $content): array
    {
        $lines = ['', '## Grammar'];

        foreach ($unit->grammarPoints as $point) {
            array_push($lines, '', "### {$point->title}", '', $point->explanation);
        }

        foreach ($content->grammarExamples() as $example) {
            $lines[] = "- {$example['text']} ({$example['english']})";
        }

        return $lines;
    }

    /** @return list<string> */
    private function exercises(Unit $unit, UnitContent $content, bool $forOwner): array
    {
        try {
            $lessons = $this->buildUnitLessons->handle($unit, new PreviewContent($content));
        } catch (InvalidLessonContent $exception) {
            return ['', '## Generated exercises', '', "The words do not build: {$exception->getMessage()}"];
        }

        $lines = ['', '## Generated exercises'];

        foreach ($lessons as $lesson) {
            if ($forOwner && $lesson->stage === LessonStage::Check) {
                continue;
            }

            $lines[] = '';
            $lines[] = "### Lesson {$lesson->position}: {$lesson->title}";
            $lines[] = '';

            foreach ($lesson->exercises as $exercise) {
                $lines[] = $this->exercise($exercise);
            }
        }

        return $lines;
    }

    private function exercise(ExerciseDefinition $exercise): string
    {
        $payload = $exercise->payload;

        return match ($exercise->format) {
            LessonExerciseFormat::TeachWord => "- teach: {$this->text($payload['term'] ?? '')} = {$this->text($payload['translation'] ?? '')}",
            LessonExerciseFormat::TeachGrammar => "- grammar card: {$this->text($payload['title'] ?? '')}",
            LessonExerciseFormat::MatchPairs => '- match: '.implode(', ', array_map(fn (mixed $pair): string => is_array($pair) ? $this->text($pair['left'] ?? '').' = '.$this->text($pair['right'] ?? '') : '', is_array($payload['pairs'] ?? null) ? $payload['pairs'] : [])),
            LessonExerciseFormat::ChooseMeaning, LessonExerciseFormat::ChooseWord, LessonExerciseFormat::ChooseGap, LessonExerciseFormat::ListenChoose, LessonExerciseFormat::ListenPair => "- {$exercise->format->value}: {$this->text($payload['prompt'] ?? $payload['text'] ?? '')} | options: ".implode(' / ', array_map($this->text(...), is_array($payload['options'] ?? null) ? $payload['options'] : []))." | answer: {$this->text($payload['answer'] ?? '')}",
            default => "- {$exercise->format->value}".($exercise->probeSet === null ? '' : " (check set {$exercise->probeSet})").": {$this->text($payload['prompt'] ?? $payload['text'] ?? '')} | accepted: ".implode(' / ', array_map(fn (mixed $entry): string => is_array($entry) ? $this->text($entry['text'] ?? '') : '', is_array($payload['accepted'] ?? null) ? $payload['accepted'] : [])),
        };
    }

    private function text(mixed $value): string
    {
        return is_scalar($value) ? (string) $value : '';
    }

    /** @param  list<string>  $values */
    private function cell(array $values): string
    {
        return $values === [] ? '' : implode(', ', $values);
    }
}
