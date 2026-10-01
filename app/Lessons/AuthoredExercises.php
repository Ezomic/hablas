<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\LessonExerciseFormat;
use App\Enums\LessonStage;

/**
 * Turns an authored exercise into a definition: the accepted answers get the
 * mechanical variants and the spans of their targets, and the exercise is
 * linted, because a wrong answer key is the most expensive mistake a lesson
 * can contain.
 */
final class AuthoredExercises
{
    private const PRONOUNS = [
        'es' => ['yo', 'tú', 'él', 'ella', 'usted', 'nosotros', 'nosotras', 'vosotros', 'vosotras', 'ellos', 'ellas', 'ustedes'],
        'pt' => ['eu', 'tu', 'ele', 'ela', 'você', 'nós', 'vós', 'eles', 'elas', 'vocês'],
    ];

    public function __construct(
        private readonly AnswerSpans $answerSpans = new AnswerSpans,
    ) {}

    public function add(BuildContext $context, AuthoredExercise $exercise, ExerciseSink $sink): void
    {
        $this->assertProbeSet($exercise);

        if ($exercise->block === WordExercises::CHECK_RECALL_BLOCK) {
            throw new InvalidLessonContent("The block name '{$exercise->block}' is reserved for the generated typed recall.");
        }

        $targets = [];
        $forms = [];

        foreach ($exercise->targets as $spec) {
            $ref = $spec->isGrammar() ? $this->grammarRef($context, $exercise) : $context->itemForTerm($spec->term ?? '')->ref();
            $forms[$ref->key()] = $spec->form;
            $targets[] = new TargetDefinition(
                $ref,
                $exercise->stage === LessonStage::Check && $exercise->format->canProbe(),
                $spec->contrast,
                $spec->form,
            );
        }

        $payload = $this->payload($context, $exercise, $forms);

        $sink->add($exercise->key, $exercise->block, $exercise->format, $payload, $targets, $exercise->probeSet);
    }

    private function assertProbeSet(AuthoredExercise $exercise): void
    {
        $isCheck = $exercise->stage === LessonStage::Check;

        if ($isCheck && ! in_array($exercise->probeSet, ['a', 'b'], true)) {
            throw new InvalidLessonContent("Check exercise '{$exercise->key}' needs probe set a or b.");
        }

        if (! $isCheck && $exercise->probeSet !== null) {
            throw new InvalidLessonContent("Only check exercises have a probe set, not '{$exercise->key}'.");
        }
    }

    private function grammarRef(BuildContext $context, AuthoredExercise $exercise): TargetRef
    {
        if ($context->grammar === null) {
            throw new InvalidLessonContent("Exercise '{$exercise->key}' targets the grammar point, but unit {$context->unit->slug} has none.");
        }

        return TargetRef::for($context->grammar);
    }

    /**
     * @param  array<string, string>  $forms
     * @return array<string, mixed>
     */
    private function payload(BuildContext $context, AuthoredExercise $exercise, array $forms): array
    {
        $format = $exercise->format;
        $payload = $exercise->payload;

        if ($format->isChoice()) {
            $this->assertChoice($context, $exercise->key, $payload, $exercise->accepted);
        }

        if ($format->isPassage()) {
            $this->assertPassage($exercise->key, $payload, $format);
        }

        if ($format === LessonExerciseFormat::WriteGuided) {
            $payload['required'] = $this->required($context, $exercise);
        }

        if ($format->isExactMatch() || in_array($format, [LessonExerciseFormat::SpeakRepeat], true)) {
            $payload['accepted'] = $this->accepted($context, $exercise, $forms);
            $payload['portunol_slips'] = $exercise->portunolSlips;
        }

        if ($format === LessonExerciseFormat::ListenType || $format === LessonExerciseFormat::SpeakRepeat) {
            $payload['text'] ??= $exercise->accepted[0] ?? throw new InvalidLessonContent("Exercise '{$exercise->key}' needs a text.");
        }

        if ($format === LessonExerciseFormat::BuildSentence) {
            $payload = $this->withTiles($context, $exercise, $payload);
        }

        return $payload;
    }

    /**
     * @param  array<string, string>  $forms
     * @return list<array{text: string, spans: array<string, array{int, int}>}>
     */
    private function accepted(BuildContext $context, AuthoredExercise $exercise, array $forms): array
    {
        $answers = $exercise->accepted;

        if ($answers === []) {
            $text = $exercise->payload['text'] ?? null;

            if (! is_string($text)) {
                throw new InvalidLessonContent("Exercise '{$exercise->key}' has no accepted answers.");
            }

            $answers = [$text];
        }

        $accepted = [];
        $seen = [];

        foreach ($answers as $answer) {
            $spans = $this->answerSpans->find($context->normalizer, $answer, $forms)
                ?? throw new InvalidLessonContent("Exercise '{$exercise->key}': a target form is missing from the accepted answer '{$answer}'.");

            $this->push($accepted, $seen, $context, $answer, $spans);

            $dropped = $this->withoutSubject($context, $answer);
            $droppedSpans = $dropped === null ? null : $this->answerSpans->find($context->normalizer, $dropped, $forms);

            if ($dropped !== null && $droppedSpans !== null) {
                $this->push($accepted, $seen, $context, $dropped, $droppedSpans);
            }
        }

        return $accepted;
    }

    /**
     * @param  list<array{text: string, spans: array<string, array{int, int}>}>  $accepted
     * @param  array<string, true>  $seen
     * @param  array<string, array{int, int}>  $spans
     */
    private function push(array &$accepted, array &$seen, BuildContext $context, string $text, array $spans): void
    {
        $key = $context->normalizer->exactKey($text);

        if (isset($seen[$key])) {
            return;
        }

        $seen[$key] = true;
        $accepted[] = ['text' => $text, 'spans' => $spans];
    }

    private function withoutSubject(BuildContext $context, string $answer): ?string
    {
        $words = preg_split('/\s+/u', trim($answer), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $pronouns = self::PRONOUNS[$context->unit->language->code ?? ''] ?? [];

        if (count($words) < 2 || ! in_array($context->normalizer->exactKey($words[0]), $pronouns, true)) {
            return null;
        }

        return implode(' ', array_slice($words, 1));
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  list<string>  $accepted
     */
    private function assertChoice(BuildContext $context, string $key, array $payload, array $accepted): void
    {
        $options = is_array($payload['options'] ?? null) ? array_values(array_map(fn (mixed $option): string => is_string($option) ? $option : '', $payload['options'])) : [];
        $answer = is_string($payload['answer'] ?? null) ? $payload['answer'] : '';

        if (! in_array($answer, $options, true)) {
            throw new InvalidLessonContent("Exercise '{$key}': the answer is not among the options.");
        }

        $keys = array_map($context->normalizer->exactKey(...), $options);

        if (count(array_unique($keys)) !== count($keys)) {
            throw new InvalidLessonContent("Exercise '{$key}': two options normalise to the same text.");
        }

        $acceptedKeys = array_map($context->normalizer->exactKey(...), $accepted);

        foreach ($options as $option) {
            if ($option !== $answer && in_array($context->normalizer->exactKey($option), $acceptedKeys, true)) {
                throw new InvalidLessonContent("Exercise '{$key}': the distractor '{$option}' is also an accepted answer.");
            }
        }
    }

    /** @param  array<string, mixed>  $payload */
    private function assertPassage(string $key, array $payload, LessonExerciseFormat $format): void
    {
        $sets = [$payload['questions'] ?? null];

        if ($format === LessonExerciseFormat::ListenPassage) {
            $sets[] = $payload['substitute_questions'] ?? null;
        }

        foreach ($sets as $questions) {
            if (! is_array($questions) || $questions === []) {
                throw new InvalidLessonContent("Exercise '{$key}': a passage needs its question sets.");
            }

            foreach ($questions as $question) {
                $options = is_array($question) && is_array($question['options'] ?? null) ? $question['options'] : [];

                if (! is_array($question) || ! in_array($question['answer'] ?? null, $options, true)) {
                    throw new InvalidLessonContent("Exercise '{$key}': a question's answer is not among its options.");
                }
            }
        }
    }

    /** @return list<array{forms: list<string>, target: string|null}> */
    private function required(BuildContext $context, AuthoredExercise $exercise): array
    {
        $required = $exercise->payload['required'] ?? null;

        if (! is_array($required) || $required === []) {
            throw new InvalidLessonContent("Exercise '{$exercise->key}' needs required words.");
        }

        $entries = [];

        foreach ($required as $entry) {
            $forms = is_array($entry) && is_array($entry['forms'] ?? null) ? array_values(array_filter($entry['forms'], is_string(...))) : [];
            $term = is_array($entry) && is_string($entry['term'] ?? null) ? $entry['term'] : null;

            if ($forms === []) {
                throw new InvalidLessonContent("Exercise '{$exercise->key}': a required word has no accepted form.");
            }

            $entries[] = ['forms' => $forms, 'target' => $term === null ? null : $context->itemForTerm($term)->key()];
        }

        return $entries;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function withTiles(BuildContext $context, AuthoredExercise $exercise, array $payload): array
    {
        $accepted = $payload['accepted'] ?? [];
        $first = is_array($accepted) && is_array($accepted[0] ?? null) && is_string($accepted[0]['text'] ?? null) ? $accepted[0]['text'] : '';
        $tiles = preg_split('/\s+/u', trim($first), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $wanted = match ($exercise->stage) {
            LessonStage::Sentences => 1,
            LessonStage::Task => 2,
            default => 0,
        };
        $distractors = is_array($payload['distractors'] ?? null) ? array_values(array_filter($payload['distractors'], is_string(...))) : [];

        if (count($distractors) < $wanted) {
            throw new InvalidLessonContent("Exercise '{$exercise->key}' needs {$wanted} distractor tiles in the {$exercise->stage->value} lesson.");
        }

        unset($payload['distractors']);

        $all = [...$tiles, ...array_slice($distractors, 0, $wanted)];
        usort($all, fn (string $a, string $b): int => strcmp(md5($exercise->key.$a), md5($exercise->key.$b)));
        $payload['tiles'] = $all;

        foreach (is_array($accepted) ? $accepted : [] as $entry) {
            $answer = is_array($entry) && is_string($entry['text'] ?? null) ? $entry['text'] : '';
            $available = array_map($context->normalizer->exactKey(...), $all);

            foreach ($this->answerSpans->words($context->normalizer, $answer) as $word) {
                $at = array_search($word, $available, true);

                if ($at === false) {
                    throw new InvalidLessonContent("Exercise '{$exercise->key}': the tiles cannot rebuild the answer '{$answer}'.");
                }

                unset($available[$at]);
            }
        }

        return $payload;
    }
}
