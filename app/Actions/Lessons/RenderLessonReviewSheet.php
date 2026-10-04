<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Enums\LessonExerciseFormat;
use App\Enums\LessonStage;
use App\Lessons\ExerciseDefinition;
use App\Lessons\InvalidLessonContent;
use App\Lessons\PreviewContent;
use App\Lessons\TargetDefinition;
use App\Lessons\TargetRef;
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
            ...($forOwner ? [''] : $this->checklist($content->languageCode())),
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
    private function checklist(string $language): array
    {
        if ($language === 'fr' || $language === 'it') {
            return $this->romanceChecklist($language);
        }

        if ($language === 'pt') {
            return [
                '',
                '## Checklist for the reviewer',
                '',
                '- Grammar: every Portuguese sentence is correct, and natural in Portugal.',
                '- Level: A1, present tense, only the unit words, the core words and the glossed words.',
                '- Accepted answers: nothing right is missing (dropped subject, free word order, tu and o senhor, synonyms, obrigado and obrigada) and nothing wrong is accepted.',
                '- Distractors: no wrong option or tile is also a correct answer.',
                '- Gender and article: every noun has the right article, and the common-gender noun accepts both.',
                '- European, not Brazilian: no Brazilian word, spelling (1990 agreement as written in Portugal) or form; "estar a" plus the infinitive, never the gerund.',
                '- Pronoun position: the clitic after the verb in a plain sentence (chamo-me), before it after não, nunca, já, também, que and similar words.',
                '- Portuñol: no Spanish word or form is accepted, and every contrast item has exactly one right answer whose why-note is true.',
                '- Dictations: no word that can be heard two ways (há, à, a; cem, sem) without a context that settles it.',
                '- Keyword slots and required words: every right spoken or written answer is covered.',
                '',
            ];
        }

        return [
            '',
            '## Checklist for the reviewer',
            '',
            '- Grammar: every Spanish sentence is correct, and natural in Spain.',
            '- Level: A1, present tense, only the unit words, the core words and the glossed words.',
            '- Accepted answers: nothing right is missing (dropped subject, free word order, tu and usted, synonyms) and nothing wrong is accepted.',
            '- Distractors: no wrong option or tile is also a correct answer.',
            '- Gender and article: every noun has the right article, and the common-gender noun accepts both.',
            '- Spain, not Latin America: no Latin American word or form.',
            '- Contrast items: each one has exactly one right answer, and its why-note is true.',
            '- Dictations: no word that can be heard two ways without a context that settles it.',
            '- Keyword slots and required words: every right spoken or written answer is covered.',
            '',
        ];
    }

    /** @return list<string> */
    private function romanceChecklist(string $language): array
    {
        $name = $language === 'fr' ? 'French' : 'Italian';
        $country = $language === 'fr' ? 'France' : 'Italy';
        $specific = $language === 'fr'
            ? '- Elision and liaison: l\', j\', d\' before a vowel, and the accents (é, è, à, où) are right and the accent-sensitive pairs (a/à, ou/où, la/là) are not accepted for each other.'
            : '- Doubles and accents: double consonants (pizza, anno) and the accents (è/e, dà/da, sì/si) are right and the accent-sensitive pairs are not accepted for each other.';

        return [
            '',
            '## Checklist for the reviewer',
            '',
            "- Grammar: every {$name} sentence is correct, and natural in {$country}.",
            '- Level: A1, present tense, only the unit words, the core words and the glossed words.',
            '- Accepted answers: nothing right is missing (dropped subject where natural, free word order, formal and informal you, synonyms) and nothing wrong is accepted.',
            '- Distractors: no wrong option or tile is also a correct answer.',
            '- Gender and article: every noun has the right article, and the common-gender noun accepts both.',
            $specific,
            '- Contrast items: each one has exactly one right answer, and its why-note is true.',
            '- Dictations: no word that can be heard two ways without a context that settles it.',
            '- Keyword slots and required words: every right spoken or written answer is covered.',
            '',
        ];
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
            $lessons = $this->buildUnitLessons->handle($unit, new PreviewContent($content, withLessons: true));
        } catch (InvalidLessonContent $exception) {
            return ['', '## Generated exercises', '', "The words do not build: {$exception->getMessage()}"];
        }

        $labels = $this->labels($unit);
        $lines = ['', '## Exercises'];

        foreach ($lessons as $lesson) {
            if ($forOwner && $lesson->stage === LessonStage::Check) {
                continue;
            }

            $lines[] = '';
            $lines[] = "### Lesson {$lesson->position}: {$lesson->title}";
            $lines[] = '';

            foreach ($lesson->exercises as $exercise) {
                array_push($lines, ...$this->exercise($exercise, $labels));
            }
        }

        return $lines;
    }

    /** @return array<string, string> a target's name by its key */
    private function labels(Unit $unit): array
    {
        $labels = [];

        foreach ($unit->vocabularyItems as $item) {
            $labels[TargetRef::vocabulary($item->id)->key()] = $item->term;
        }

        foreach ($unit->grammarPoints as $point) {
            $labels[TargetRef::grammar($point->id)->key()] = 'grammar: '.$point->title;
        }

        return $labels;
    }

    /**
     * @param  array<string, string>  $labels
     * @return list<string>
     */
    private function exercise(ExerciseDefinition $exercise, array $labels): array
    {
        $payload = $exercise->payload;
        $lines = [$this->headline($exercise)];

        if ($exercise->format->isTeach()) {
            return $lines;
        }

        $detail = fn (string $name, mixed $value) => is_scalar($value) && (string) $value !== '' ? ["  - {$name}: {$value}"] : [];

        array_push($lines, ...$detail('English', $payload['english'] ?? null), ...$detail('Source', $payload['source'] ?? null), ...$detail('Why', $payload['why'] ?? null), ...$detail('Model answer', $payload['model'] ?? null));

        if (is_array($payload['tiles'] ?? null)) {
            $lines[] = '  - tiles: '.implode(' | ', array_map($this->text(...), $payload['tiles']));
        }

        if (is_array($payload['slots'] ?? null)) {
            $lines[] = '  - keyword slots: '.implode(' ; ', array_map(fn (mixed $slot): string => is_array($slot) ? implode(' / ', array_map($this->text(...), $slot)) : '', $payload['slots']));
        }

        if (is_array($payload['required'] ?? null)) {
            $lines[] = '  - required words: '.implode(' ; ', array_map(fn (mixed $entry): string => is_array($entry) && is_array($entry['forms'] ?? null) ? implode(' / ', array_map($this->text(...), $entry['forms'])) : '', $payload['required']));
        }

        if (is_array($payload['glosses'] ?? null)) {
            $lines[] = '  - glossed: '.implode(', ', array_map(fn (mixed $meaning, string|int $word): string => "{$word} = {$this->text($meaning)}", $payload['glosses'], array_keys($payload['glosses'])));
        }

        array_push($lines, ...$this->passage($payload));

        if ($exercise->targets !== []) {
            $lines[] = '  - targets: '.implode('; ', array_map(fn (TargetDefinition $target): string => ($labels[$target->ref->key()] ?? '?').($target->form === null ? '' : " as '{$target->form}'").($target->isContrast ? ', contrast' : '').($target->isProbe ? ', probe' : ''), $exercise->targets));
        }

        return $lines;
    }

    private function headline(ExerciseDefinition $exercise): string
    {
        $payload = $exercise->payload;
        $suffix = $exercise->substituteForKey === null ? '' : ' [substitute, used when a skip replaces its original]';

        return match ($exercise->format) {
            LessonExerciseFormat::TeachWord => "- teach: {$this->text($payload['term'] ?? '')} = {$this->text($payload['translation'] ?? '')}",
            LessonExerciseFormat::TeachGrammar => "- grammar card: {$this->text($payload['title'] ?? '')}",
            LessonExerciseFormat::MatchPairs => '- match: '.implode(', ', array_map(fn (mixed $pair): string => is_array($pair) ? $this->text($pair['left'] ?? '').' = '.$this->text($pair['right'] ?? '') : '', is_array($payload['pairs'] ?? null) ? $payload['pairs'] : [])),
            LessonExerciseFormat::ChooseMeaning, LessonExerciseFormat::ChooseWord, LessonExerciseFormat::ChooseGap, LessonExerciseFormat::ListenChoose, LessonExerciseFormat::ListenPair => "- {$exercise->format->value}: {$this->text($payload['prompt'] ?? $payload['text'] ?? '')} | options: ".implode(' / ', array_map($this->text(...), is_array($payload['options'] ?? null) ? $payload['options'] : []))." | answer: {$this->text($payload['answer'] ?? '')}{$suffix}",
            LessonExerciseFormat::ReadPassage, LessonExerciseFormat::ListenPassage, LessonExerciseFormat::WriteGuided, LessonExerciseFormat::SpeakAnswer => "- {$exercise->format->value}".($exercise->probeSet === null ? '' : " (check set {$exercise->probeSet})").": {$this->text($payload['prompt'] ?? '')}{$suffix}",
            default => "- {$exercise->format->value}".($exercise->probeSet === null ? '' : " (check set {$exercise->probeSet})").": {$this->text($payload['prompt'] ?? $payload['text'] ?? '')} | accepted: ".implode(' / ', array_map(fn (mixed $entry): string => is_array($entry) ? $this->text($entry['text'] ?? '') : '', is_array($payload['accepted'] ?? null) ? $payload['accepted'] : []))."{$suffix}",
        };
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return list<string>
     */
    private function passage(array $payload): array
    {
        $lines = [];

        foreach (is_array($payload['dialogue'] ?? null) ? $payload['dialogue'] : [] as $line) {
            if (is_array($line)) {
                $lines[] = "  - {$this->text($line['speaker'] ?? '')}: {$this->text($line['text'] ?? '')}";
            }
        }

        foreach (['questions' => 'question', 'substitute_questions' => 'question if skipped'] as $key => $label) {
            foreach (is_array($payload[$key] ?? null) ? $payload[$key] : [] as $question) {
                if (is_array($question)) {
                    $lines[] = "  - {$label}: {$this->text($question['prompt'] ?? '')} | ".implode(' / ', array_map($this->text(...), is_array($question['options'] ?? null) ? $question['options'] : []))." | answer: {$this->text($question['answer'] ?? '')}";
                }
            }
        }

        return $lines;
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
