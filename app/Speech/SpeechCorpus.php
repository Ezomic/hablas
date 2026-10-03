<?php

declare(strict_types=1);

namespace App\Speech;

use App\Enums\LessonExerciseFormat;
use App\Lessons\UnitContent;
use App\Models\Language;
use App\Models\ListeningExercise;
use App\Models\PronunciationDrillExercise;
use App\Models\ShadowingExercise;
use App\Models\VocabularyItem;
use App\Services\UnitContentRegistry;

final class SpeechCorpus
{
    private const SPOKEN_PAYLOAD_FORMATS = [
        LessonExerciseFormat::ListenChoose,
        LessonExerciseFormat::ListenPair,
        LessonExerciseFormat::ListenType,
        LessonExerciseFormat::SpeakRepeat,
    ];

    public function __construct(
        private readonly UnitContentRegistry $registry,
        private readonly SpeechText $text,
    ) {}

    /** @return list<string> every distinct normalised string spoken in the language, sorted */
    public function texts(string $language): array
    {
        $strings = [...$this->unitContent($language), ...$this->stored($language)];

        $normalised = [];

        foreach ($strings as $string) {
            $text = $this->text->normalise($string);

            if ($text !== '') {
                $normalised[$text] = true;
            }
        }

        $texts = array_map(strval(...), array_keys($normalised));
        sort($texts, SORT_STRING);

        return $texts;
    }

    /** @return list<string> */
    private function unitContent(string $language): array
    {
        $strings = [];

        foreach ($this->registry->all() as $content) {
            if ($content->languageCode() === $language) {
                array_push($strings, ...$this->fromContent($content));
            }
        }

        return $strings;
    }

    /** @return list<string> */
    private function fromContent(UnitContent $content): array
    {
        $strings = [];

        foreach ($content->words() as $word) {
            $strings[] = $word->term;
            array_push($strings, ...$word->forms);
        }

        foreach ($content->grammarExamples() as $example) {
            $strings[] = $example['text'];
        }

        foreach ($content->exercises() as $exercise) {
            if (in_array($exercise->format, self::SPOKEN_PAYLOAD_FORMATS, true)) {
                $spoken = $exercise->payload['text']
                    ?? $exercise->accepted[0]
                    ?? ($exercise->format === LessonExerciseFormat::ListenPair ? $exercise->payload['answer'] ?? null : null);

                if (is_string($spoken)) {
                    $strings[] = $spoken;
                }
            }

            if ($exercise->format === LessonExerciseFormat::SpeakAnswer && is_string($exercise->payload['prompt'] ?? null)) {
                $strings[] = $exercise->payload['prompt'];
            }

            if ($exercise->format->isPassage()) {
                array_push($strings, ...$this->dialogueLines($exercise->payload['dialogue'] ?? null));
            }
        }

        return $strings;
    }

    /** @return list<string> */
    private function dialogueLines(mixed $dialogue): array
    {
        $lines = [];

        foreach (is_array($dialogue) ? $dialogue : [] as $line) {
            if (is_array($line) && is_string($line['text'] ?? null)) {
                $lines[] = $line['text'];
            }
        }

        return $lines;
    }

    /** @return list<string> */
    private function stored(string $language): array
    {
        $languageId = Language::query()->where('code', $language)->value('id');

        if ($languageId === null) {
            return [];
        }

        $strings = VocabularyItem::query()->where('language_id', $languageId)->pluck('term')->all();
        array_push($strings, ...ListeningExercise::query()->where('language_id', $languageId)->pluck('transcript')->all());
        array_push($strings, ...ShadowingExercise::query()->where('language_id', $languageId)->pluck('target_transcript')->all());

        $drills = PronunciationDrillExercise::query()->where('language_id', $languageId)->get(['word_a', 'word_b', 'target_word']);

        foreach ($drills as $drill) {
            array_push($strings, $drill->word_a, $drill->word_b, $drill->target_word);
        }

        return array_values(array_filter($strings, is_string(...)));
    }
}
