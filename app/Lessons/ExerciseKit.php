<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonStage as Stage;

/**
 * Short constructors for the authored exercises of a unit's content class, so
 * a class reads as its sentences and not as payload plumbing.
 */
final class ExerciseKit
{
    public static function word(string $term, ?string $form = null): TargetSpec
    {
        return TargetSpec::word($term, $form);
    }

    public static function form(string $form, bool $contrast = false): TargetSpec
    {
        return TargetSpec::grammar($form, $contrast);
    }

    /**
     * @param  list<string>  $options
     * @return array{prompt: string, options: list<string>, answer: string}
     */
    public static function question(string $prompt, array $options, string $answer): array
    {
        return ['prompt' => $prompt, 'options' => $options, 'answer' => $answer];
    }

    /** @return array{speaker: string, text: string} */
    public static function line(string $speaker, string $text): array
    {
        return ['speaker' => $speaker, 'text' => $text];
    }

    /**
     * @param  list<string>  $options
     * @param  array<string, string>  $glosses
     */
    public static function gap(Stage $stage, string $key, string $prompt, array $options, string $answer, TargetSpec $target, string $why, string $block, ?string $english = null, array $glosses = []): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::ChooseGap, $key, array_filter(['prompt' => $prompt, 'english' => $english, 'options' => $options, 'answer' => $answer, 'why' => $why, 'glosses' => $glosses]), [$answer], [$target], block: $block);
    }

    /** @param  array<string, string>  $glosses */
    public static function typeGap(Stage $stage, string $key, string $prompt, string $english, string $answer, TargetSpec $target, ?string $why = null, string $block = 'write', ?string $set = null, array $glosses = []): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::TypeGap, $key, array_filter(['prompt' => $prompt, 'english' => $english, 'why' => $why, 'glosses' => $glosses]), [$answer], [$target], $set, $block);
    }

    /**
     * @param  list<string>  $answers
     * @param  list<TargetSpec>  $targets
     * @param  array<string, string>  $glosses
     */
    public static function translate(Stage $stage, string $key, string $english, array $answers, array $targets, string $block = 'write', ?string $set = null, array $glosses = []): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::TranslateSentence, $key, array_filter(['prompt' => $english, 'english' => $english, 'glosses' => $glosses]), $answers, $targets, $set, $block);
    }

    /**
     * @param  list<string>  $distractors
     * @param  list<TargetSpec>  $targets
     * @param  array<string, string>  $glosses
     */
    public static function build(Stage $stage, string $key, string $english, string $answer, array $distractors, array $targets, string $block = 'write', array $glosses = []): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::BuildSentence, $key, array_filter(['prompt' => $english, 'english' => $english, 'distractors' => $distractors, 'glosses' => $glosses]), [$answer], $targets, block: $block);
    }

    /**
     * @param  list<string>  $answers
     * @param  list<TargetSpec>  $targets
     * @param  array<string, string>  $glosses
     */
    public static function transform(Stage $stage, string $key, string $instruction, string $source, array $answers, array $targets, array $glosses = []): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::TransformSentence, $key, array_filter(['prompt' => $instruction, 'source' => $source, 'glosses' => $glosses]), $answers, $targets, block: 'write');
    }

    /**
     * @param  list<string>  $options  English meanings
     * @param  list<TargetSpec>  $targets
     */
    public static function listenChoose(Stage $stage, string $key, string $text, array $options, string $answer, array $targets): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::ListenChoose, $key, ['text' => $text, 'options' => $options, 'answer' => $answer], targets: $targets, block: 'listen');
    }

    /**
     * @param  list<TargetSpec>  $targets
     * @param  list<string>  $alsoAccepted
     */
    public static function listenType(Stage $stage, string $key, string $text, string $english, array $targets, string $block = 'listen', ?string $set = null, array $alsoAccepted = [], ?string $homophoneNote = null): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::ListenType, $key, array_filter(['text' => $text, 'english' => $english, 'homophone_note' => $homophoneNote]), [$text, ...$alsoAccepted], $targets, $set, $block);
    }

    /**
     * @param  list<TargetSpec>  $targets
     * @param  list<string>  $alsoAccepted
     */
    public static function speakRepeat(Stage $stage, string $key, string $text, string $english, array $targets, string $block = 'speak', array $alsoAccepted = []): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::SpeakRepeat, $key, ['text' => $text, 'english' => $english], $alsoAccepted === [] ? [] : [$text, ...$alsoAccepted], $targets, block: $block);
    }

    /**
     * @param  list<list<string>>  $slots
     * @param  list<TargetSpec>  $targets
     */
    public static function speakAnswer(Stage $stage, string $key, string $prompt, string $english, array $slots, string $model, array $targets, string $block = 'speak', ?string $set = null): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::SpeakAnswer, $key, ['prompt' => $prompt, 'english' => $english, 'slots' => $slots, 'model' => $model], targets: $targets, probeSet: $set, block: $block);
    }

    /**
     * @param  list<array{speaker: string, text: string}>  $dialogue
     * @param  list<array{prompt: string, options: list<string>, answer: string}>  $questions
     * @param  list<TargetSpec>  $targets
     * @param  array<string, string>  $glosses
     */
    public static function readPassage(Stage $stage, string $key, string $prompt, array $dialogue, array $questions, array $targets, string $block = 'read', ?string $set = null, array $glosses = []): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::ReadPassage, $key, array_filter(['prompt' => $prompt, 'dialogue' => $dialogue, 'questions' => $questions, 'glosses' => $glosses]), targets: $targets, probeSet: $set, block: $block);
    }

    /**
     * @param  list<array{speaker: string, text: string}>  $dialogue
     * @param  list<array{prompt: string, options: list<string>, answer: string}>  $questions
     * @param  list<array{prompt: string, options: list<string>, answer: string}>  $substituteQuestions
     * @param  list<TargetSpec>  $targets
     */
    public static function listenPassage(Stage $stage, string $key, array $dialogue, array $questions, array $substituteQuestions, array $targets, string $block = 'listen', ?string $set = null): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::ListenPassage, $key, ['prompt' => 'Listen to the conversation.', 'dialogue' => $dialogue, 'questions' => $questions, 'substitute_questions' => $substituteQuestions], targets: $targets, probeSet: $set, block: $block);
    }

    /**
     * @param  list<string>  $chips
     * @param  list<array{forms: list<string>, term: string|null}>  $required
     * @param  list<TargetSpec>  $targets
     * @param  array<string, string>  $glosses
     */
    public static function writeGuided(Stage $stage, string $key, string $prompt, array $chips, string $model, array $required, array $targets, array $glosses = []): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::WriteGuided, $key, array_filter(['prompt' => $prompt, 'chips' => $chips, 'glosses' => $glosses, 'model' => $model, 'required' => $required]), targets: $targets, block: 'write');
    }
}
