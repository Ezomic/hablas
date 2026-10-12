<?php

declare(strict_types=1);

use App\Enums\LessonExerciseFormat;
use App\Lessons\DutchSentenceCollector;
use App\Lessons\DutchSentences;
use App\Lessons\DutchTextFields;
use App\Lessons\LocalizeExercise;
use App\Models\Lesson;
use App\Models\LessonExercise;
use App\Models\Unit;
use Database\Seeders\ContentSeeder;

afterEach(function () {
    app()->setLocale('en');
    DutchSentences::flush();
});

/** @param  array<string, string>  $entries */
function dutchSentencesIn(array $entries): DutchSentences
{
    $directory = sys_get_temp_dir().'/dutch-sentences-'.bin2hex(random_bytes(4));
    mkdir($directory);
    file_put_contents($directory.'/001.php', '<?php return '.var_export($entries, true).';');

    return new DutchSentences($directory);
}

/** @param  array<string, mixed>  $payload */
function sentenceExercise(LessonExerciseFormat $format, array $payload): LessonExercise
{
    return LessonExercise::factory()->create([
        'lesson_id' => Lesson::factory()->create(['unit_id' => Unit::factory()->create()->id])->id,
        'format' => $format,
        'payload' => $payload,
    ])->load('lesson');
}

describe('the English texts of an exercise', function () {
    it('finds the cues, options, notes, passage questions and grammar examples, and never the target language', function () {
        $seen = [];
        $collect = function (string $text, string $path) use (&$seen): string {
            $seen[$path] = $text;

            return $text;
        };

        DutchTextFields::map(LessonExerciseFormat::ChooseGap, ['prompt' => 'En otoño ___.', 'english' => 'In autumn it rains.', 'options' => ['llueve', 'hace'], 'answer' => 'llueve', 'why' => 'Rain has its own verb.'], $collect);
        DutchTextFields::map(LessonExerciseFormat::ReadPassage, ['prompt' => 'Read.', 'dialogue' => [['speaker' => 'Ana', 'text' => 'Hola.']], 'questions' => [['prompt' => 'Who speaks?', 'options' => ['Ana', 'Nobody'], 'answer' => 'Ana']]], $collect);
        DutchTextFields::map(LessonExerciseFormat::TeachGrammar, ['title' => 'Ser', 'explanation' => 'Use ser.', 'examples' => [['text' => 'Soy Ana.', 'english' => 'I am Ana.']]], $collect);

        expect($seen)->toBe([
            'english' => 'In autumn it rains.',
            'why' => 'Rain has its own verb.',
            'prompt' => 'Read.',
            'questions.0.prompt' => 'Who speaks?',
            'questions.0.options.0' => 'Ana',
            'questions.0.options.1' => 'Nobody',
            'questions.0.answer' => 'Ana',
            'title' => 'Ser',
            'explanation' => 'Use ser.',
            'examples.0.english' => 'I am Ana.',
        ]);
    });
});

describe('the glosses of an exercise', function () {
    it('translates the English meaning and keeps the target-language word', function () {
        $payload = DutchTextFields::map(LessonExerciseFormat::TypeGap, ['prompt' => 'Vengo ___ iglesia.', 'english' => 'I come from the church.', 'glosses' => ['vengo' => 'I come']], fn (string $text): string => strtoupper($text));

        expect($payload['glosses'])->toBe(['vengo' => 'I COME'])
            ->and($payload['prompt'])->toBe('Vengo ___ iglesia.');
    });
});

describe('a guided writing exercise', function () {
    it('translates an English instruction, or the English cue of a question asked in the target language, never the question itself', function () {
        $seen = [];
        $collect = function (string $text, string $path) use (&$seen): string {
            $seen[] = $path.'='.$text;

            return $text;
        };

        DutchTextFields::map(LessonExerciseFormat::WriteGuided, ['prompt' => 'Say that you are tired.', 'model' => 'Estoy cansado.'], $collect);
        DutchTextFields::map(LessonExerciseFormat::WriteGuided, ['prompt' => '¿Vas a la tienda?', 'english' => 'Are you going to the shop?', 'model' => 'Voy a la tienda.'], $collect);

        expect($seen)->toBe(['prompt=Say that you are tired.', 'english=Are you going to the shop?']);
    });
});

describe('Dutch exercise sentences', function () {
    it('keeps English when the interface is English', function () {
        $exercise = sentenceExercise(LessonExerciseFormat::SpeakRepeat, ['text' => 'Hola.', 'english' => 'Hello.']);

        (new LocalizeExercise(dutchSentencesIn(['Hello.' => 'Hallo.'])))->handle($exercise);

        expect($exercise->payload['english'])->toBe('Hello.');
    });

    it('shows Dutch cues, options, notes and passage questions, and leaves the target language and untranslated texts alone', function () {
        app()->setLocale('nl');
        $sentences = dutchSentencesIn([
            'In autumn it rains.' => 'In de herfst regent het.',
            'Rain has its own verb.' => 'Regen heeft een eigen werkwoord.',
            'Who speaks?' => 'Wie praat er?',
            'Nobody' => 'Niemand',
        ]);

        $gap = sentenceExercise(LessonExerciseFormat::ChooseGap, ['prompt' => 'En otoño ___.', 'english' => 'In autumn it rains.', 'options' => ['llueve', 'hace'], 'answer' => 'llueve', 'why' => 'Rain has its own verb.']);
        $passage = sentenceExercise(LessonExerciseFormat::ReadPassage, ['prompt' => 'Read the conversation.', 'dialogue' => [['speaker' => 'Ana', 'text' => 'Hola.']], 'questions' => [['prompt' => 'Who speaks?', 'options' => ['Ana', 'Nobody'], 'answer' => 'Ana']]]);

        foreach ([$gap, $passage] as $exercise) {
            (new LocalizeExercise($sentences))->handle($exercise);
        }

        expect($gap->payload)->toMatchArray(['prompt' => 'En otoño ___.', 'english' => 'In de herfst regent het.', 'options' => ['llueve', 'hace'], 'answer' => 'llueve', 'why' => 'Regen heeft een eigen werkwoord.'])
            ->and($passage->payload['prompt'])->toBe('Read the conversation.')
            ->and($passage->payload['dialogue'])->toBe([['speaker' => 'Ana', 'text' => 'Hola.']])
            ->and($passage->payload['questions'][0])->toBe(['prompt' => 'Wie praat er?', 'options' => ['Ana', 'Niemand'], 'answer' => 'Ana'])
            ->and($gap->isDirty('payload'))->toBeFalse();
    });

    it('translates a listening choice and its answer the same way, so the right option is still the right one', function () {
        app()->setLocale('nl');
        $exercise = sentenceExercise(LessonExerciseFormat::ListenChoose, ['text' => 'traer', 'options' => ['to bring', 'to hear'], 'answer' => 'to bring']);

        (new LocalizeExercise(dutchSentencesIn(['to bring' => 'meebrengen', 'to hear' => 'horen'])))->handle($exercise);

        expect($exercise->payload['options'])->toBe(['meebrengen', 'horen'])
            ->and($exercise->payload['answer'])->toBe('meebrengen');
    });
});

describe('the Dutch sentence data', function () {
    it('keeps a numeric text such as a number gloss when the data files are merged', function () {
        $directory = sys_get_temp_dir().'/dutch-sentences-'.bin2hex(random_bytes(4));
        mkdir($directory);
        file_put_contents($directory.'/001.php', '<?php return [\'a\' => \'b\'];');
        file_put_contents($directory.'/002.php', '<?php return [\'25\' => \'25\'];');

        expect((new DutchSentences($directory))->translate('25'))->toBe('25')
            ->and((new DutchSentences($directory))->translate('a'))->toBe('b');
    });

    it('has a non-empty translation without a long dash for every entry', function () {
        foreach ((new DutchSentences)->all() as $english => $dutch) {
            expect($english)->not->toBe('')
                ->and(trim($dutch))->not->toBe('', $english)
                ->and($dutch)->not->toContain('—', $english);
        }
    });

    it('keeps the answer among distinct options once the exercises are shown in Dutch', function () {
        $this->seed(ContentSeeder::class);
        app()->setLocale('nl');
        $localize = new LocalizeExercise;
        $problems = [];

        LessonExercise::query()->with('lesson')->whereIn('format', [LessonExerciseFormat::ListenChoose, LessonExerciseFormat::ReadPassage, LessonExerciseFormat::ListenPassage])->chunkById(500, function ($exercises) use ($localize, &$problems): void {
            foreach ($exercises as $exercise) {
                $localize->handle($exercise);
                $groups = $exercise->format === LessonExerciseFormat::ListenChoose ? [$exercise->payload] : (array) ($exercise->payload['questions'] ?? []);

                foreach ($groups as $group) {
                    $options = (array) ($group['options'] ?? []);

                    if (count(array_unique($options)) !== count($options) || ! in_array($group['answer'] ?? null, $options, true)) {
                        $problems[] = $exercise->key.': '.json_encode($group, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
        });

        expect($problems)->toBe([]);
    });
});

describe('the Dutch sentence export', function () {
    it('lists each English text once, with where it sits and a target-language sentence, and skips the translated ones', function () {
        sentenceExercise(LessonExerciseFormat::SpeakRepeat, ['text' => 'Hola, Ana.', 'english' => 'Hello, Ana.']);
        sentenceExercise(LessonExerciseFormat::SpeakRepeat, ['text' => 'Hola, Pablo.', 'english' => 'Hello, Ana.']);
        sentenceExercise(LessonExerciseFormat::ListenType, ['text' => 'Adiós.', 'english' => 'Goodbye.']);

        $all = (new DutchSentenceCollector(dutchSentencesIn([])))->handle();
        $missing = (new DutchSentenceCollector(dutchSentencesIn(['Goodbye.' => 'Tot ziens.'])))->handle(onlyMissing: true);

        expect(array_keys($all))->toEqualCanonicalizing(['Hello, Ana.', 'Goodbye.'])
            ->and($all['Hello, Ana.'])->toMatchArray(['format' => 'speak_repeat', 'field' => 'english', 'context' => 'Hola, Ana.'])
            ->and(array_keys($missing))->toBe(['Hello, Ana.']);
    });
});
