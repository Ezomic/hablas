<?php

declare(strict_types=1);

namespace App\Speech;

use App\Enums\LessonExerciseFormat;
use App\Lessons\SpokenTexts;
use App\Lessons\UnitContent;
use App\Models\Language;
use App\Models\ListeningExercise;
use App\Models\PronunciationDrillExercise;
use App\Models\ShadowingExercise;
use App\Models\VocabularyItem;
use App\Services\UnitContentRegistry;

final class SpeechCorpus
{
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
            $payload = $exercise->payload;

            if (! isset($payload['text'])) {
                $payload['text'] = match ($exercise->format) {
                    LessonExerciseFormat::ListenType, LessonExerciseFormat::SpeakRepeat => $exercise->accepted[0] ?? null,
                    LessonExerciseFormat::ListenPair => $payload['answer'] ?? null,
                    default => null,
                };
            }

            array_push($strings, ...SpokenTexts::ofPayload($exercise->format, $payload));
        }

        return $strings;
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
