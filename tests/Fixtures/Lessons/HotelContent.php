<?php

declare(strict_types=1);

namespace Tests\Fixtures\Lessons;

use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonStage as Stage;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\TargetSpec;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

/**
 * A small, invented unit shaped like "Checking into a hotel": the words,
 * authored sentences and both check sets the engine's tests build lessons
 * from. It is test data, not course content.
 */
final class HotelContent implements UnitContent
{
    public function __construct(
        private readonly bool $wordsReviewed = true,
        private readonly bool $lessonsReviewed = true,
        private readonly bool $withAuthored = true,
    ) {}

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'checking-into-a-hotel';
    }

    public function words(): array
    {
        return [
            new WordData('el recepcionista', cue: 'receptionist (at the hotel desk)', commonGender: true),
            new WordData('disponible', forms: ['disponibles']),
            new WordData('incluido', forms: ['incluida', 'incluidos']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'El hotel está cerca.', 'english' => 'The hotel is near.'],
            ['text' => 'La habitación está lista.', 'english' => 'The room is ready.'],
        ];
    }

    public function exercises(): array
    {
        if (! $this->withAuthored) {
            return [];
        }

        return [
            ...$this->recall(),
            ...$this->sentences(),
            ...$this->task(),
            ...$this->check('a'),
            ...$this->check('b'),
        ];
    }

    public function reviews(): array
    {
        $reviews = [];

        if ($this->wordsReviewed) {
            $reviews[] = new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'second AI review', '2026-10-02', 'Checked against the es_ES dictionary.');
        }

        if ($this->lessonsReviewed) {
            $reviews[] = new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'second AI review', '2026-10-02');
            $reviews[] = new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-03');
        }

        return $reviews;
    }

    /** @return list<AuthoredExercise> */
    private function recall(): array
    {
        return [
            new AuthoredExercise(Stage::Recall, Format::ChooseGap, 'recall.choose_gap.bano-aqui', [
                'prompt' => 'El baño ___ aquí.', 'english' => 'The bathroom is here.',
                'options' => ['está', 'es', 'hay'], 'answer' => 'está',
            ], targets: [TargetSpec::grammar('está')]),
            new AuthoredExercise(Stage::Recall, Format::ChooseGap, 'recall.choose_gap.hay-habitacion', [
                'prompt' => '___ una habitación disponible.', 'english' => 'There is a room available.',
                'options' => ['Hay', 'Está', 'Es'], 'answer' => 'Hay',
            ], targets: [TargetSpec::grammar('Hay', contrast: true)]),
            new AuthoredExercise(Stage::Recall, Format::ChooseGap, 'recall.choose_gap.llave', [
                'prompt' => 'La llave ___ en la habitación.', 'english' => 'The key is in the room.',
                'options' => ['está', 'es', 'hay'], 'answer' => 'está',
            ], targets: [TargetSpec::grammar('está')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        return [
            new AuthoredExercise(Stage::Sentences, Format::TypeGap, 'sentences.type_gap.llave', [
                'prompt' => 'La llave ___ en la habitación.', 'english' => 'The key is in the room.',
            ], accepted: ['está'], targets: [TargetSpec::grammar('está')]),
            new AuthoredExercise(Stage::Sentences, Format::TranslateSentence, 'sentences.translate.desayuno', [
                'prompt' => 'Breakfast is included.', 'english' => 'Breakfast is included.',
            ], accepted: ['El desayuno está incluido.'], targets: [TargetSpec::word('el desayuno'), TargetSpec::word('incluido'), TargetSpec::grammar('está')]),
            new AuthoredExercise(Stage::Sentences, Format::BuildSentence, 'sentences.build.desayuno', [
                'prompt' => 'Breakfast is included.', 'english' => 'Breakfast is included.', 'distractors' => ['es'],
            ], accepted: ['El desayuno está incluido.'], targets: [TargetSpec::word('el desayuno'), TargetSpec::word('incluido'), TargetSpec::grammar('está')]),
            new AuthoredExercise(Stage::Sentences, Format::SpeakRepeat, 'sentences.speak_repeat.hay', [
                'text' => '¿Hay una habitación disponible para dos noches?', 'english' => 'Is there a room available for two nights?',
            ], targets: [TargetSpec::word('disponible'), TargetSpec::word('la noche', 'noches'), TargetSpec::grammar('Hay', contrast: true)]),
            new AuthoredExercise(Stage::Sentences, Format::ListenType, 'sentences.listen_type.reserva', [
                'text' => 'La reserva es para dos noches.', 'english' => 'The reservation is for two nights.',
            ], accepted: ['La reserva es para dos noches.'], targets: [TargetSpec::word('la reserva'), TargetSpec::word('la noche', 'noches'), TargetSpec::grammar('es', contrast: true)]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        return [
            new AuthoredExercise(Stage::Task, Format::ReadPassage, 'task.read_passage.reception', [
                'dialogue' => [['speaker' => 'Recepcionista', 'text' => '¿Tiene una reserva?'], ['speaker' => 'Ana', 'text' => 'Sí, para dos noches.']],
                'questions' => [['prompt' => 'How many nights?', 'options' => ['one', 'two', 'three'], 'answer' => 'two']],
            ], targets: [TargetSpec::word('la reserva'), TargetSpec::word('la noche')]),
            new AuthoredExercise(Stage::Task, Format::TransformSentence, 'task.transform.plural', [
                'prompt' => 'Make it plural.', 'source' => 'La habitación está disponible.',
            ], accepted: ['Las habitaciones están disponibles.'], targets: [TargetSpec::word('la habitación', 'las habitaciones'), TargetSpec::word('disponible', 'disponibles'), TargetSpec::grammar('están')]),
            new AuthoredExercise(Stage::Task, Format::WriteGuided, 'task.write_guided.room', [
                'prompt' => 'Ask for a room for two nights and whether breakfast is included.',
                'required' => [
                    ['forms' => ['habitación', 'habitaciones'], 'term' => 'la habitación'],
                    ['forms' => ['noches'], 'term' => 'la noche'],
                    ['forms' => ['desayuno'], 'term' => 'el desayuno'],
                    ['forms' => ['incluido'], 'term' => 'incluido'],
                ],
            ], targets: [TargetSpec::word('la habitación')]),
            new AuthoredExercise(Stage::Task, Format::BuildSentence, 'task.build.llave', [
                'prompt' => 'The key is in the room.', 'english' => 'The key is in the room.', 'distractors' => ['es', 'hay'],
            ], accepted: ['La llave está en la habitación.'], targets: [TargetSpec::word('la llave'), TargetSpec::word('la habitación'), TargetSpec::grammar('está')]),
            new AuthoredExercise(Stage::Task, Format::SpeakAnswer, 'task.speak_answer.reserva', [
                'prompt' => '¿Tiene una reserva?', 'english' => 'Do you have a reservation?', 'slots' => [['tengo', 'tiene'], ['reserva']],
            ], targets: [TargetSpec::word('la reserva')]),
            new AuthoredExercise(Stage::Task, Format::ListenPassage, 'task.listen_passage.reception', [
                'dialogue' => [['speaker' => 'Recepcionista', 'text' => 'Su habitación es la tres.']],
                'questions' => [['prompt' => 'Which room?', 'options' => ['two', 'three'], 'answer' => 'three']],
                'substitute_questions' => [['prompt' => 'Whose room is it?', 'options' => ['yours', 'mine'], 'answer' => 'yours']],
            ], targets: [TargetSpec::word('la habitación')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function check(string $set): array
    {
        $sentences = $set === 'a'
            ? [
                ['The bathroom is in the room.', 'El baño está en la habitación.', ['el baño', 'la habitación'], 'está'],
                ['The key is available.', 'La llave está disponible.', ['la llave', 'disponible'], 'está'],
            ]
            : [
                ['The breakfast is in the hotel.', 'El desayuno está en el hotel.', ['el desayuno', 'el hotel'], 'está'],
                ['The night is included.', 'La noche está incluida.', ['la noche', 'incluido'], 'está'],
            ];

        $exercises = [];

        foreach ($sentences as $index => [$english, $answer, $terms, $form]) {
            $exercises[] = new AuthoredExercise(Stage::Check, Format::TranslateSentence, "check.{$set}.translate.{$index}", [
                'prompt' => $english, 'english' => $english,
            ], accepted: [$answer], targets: [
                ...array_map(fn (string $term): TargetSpec => TargetSpec::word($term, $this->formIn($answer, $term)), $terms),
                TargetSpec::grammar($form),
            ], probeSet: $set);
        }

        $exercises[] = new AuthoredExercise(Stage::Check, Format::TypeGap, "check.{$set}.type_gap.hay", [
            'prompt' => '___ una habitación disponible.', 'english' => 'There is a room available.',
        ], accepted: ['Hay'], targets: [TargetSpec::grammar('Hay', contrast: true)], probeSet: $set);

        $exercises[] = new AuthoredExercise(Stage::Check, Format::ListenType, "check.{$set}.listen_type.es", [
            'text' => $set === 'a' ? 'La reserva es para dos noches.' : 'La reserva es para tres noches.',
            'english' => 'The reservation is for nights.',
        ], accepted: [$set === 'a' ? 'La reserva es para dos noches.' : 'La reserva es para tres noches.'], targets: [
            TargetSpec::word('la reserva'), TargetSpec::grammar('es', contrast: true),
        ], probeSet: $set);

        $exercises[] = new AuthoredExercise(Stage::Check, Format::SpeakAnswer, "check.{$set}.speak_answer", [
            'prompt' => '¿Tiene una reserva?', 'english' => 'Do you have a reservation?', 'slots' => [['tengo', 'tiene'], ['reserva']],
        ], targets: [TargetSpec::word('la reserva')], probeSet: $set);

        return $exercises;
    }

    private function formIn(string $answer, string $term): string
    {
        $forms = ['el baño' => 'el baño', 'la habitación' => 'la habitación', 'la llave' => 'la llave', 'disponible' => 'disponible', 'el desayuno' => 'el desayuno', 'el hotel' => 'el hotel', 'la noche' => 'la noche', 'incluido' => 'incluida'];

        return $forms[$term] ?? $term;
    }
}
