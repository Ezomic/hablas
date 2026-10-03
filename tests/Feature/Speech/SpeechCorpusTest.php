<?php

declare(strict_types=1);

use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\UnitContent;
use App\Lessons\WordData;
use App\Models\Language;
use App\Models\ListeningExercise;
use App\Models\PronunciationDrillExercise;
use App\Models\ShadowingExercise;
use App\Models\VocabularyItem;
use App\Services\UnitContentRegistry;
use App\Speech\SpeechCorpus;
use Tests\Fixtures\Lessons\HotelContent;

final class PortugueseStubContent implements UnitContent
{
    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'stub';
    }

    public function words(): array
    {
        return [new WordData('o pão')];
    }

    public function grammarExamples(): array
    {
        return [];
    }

    public function exercises(): array
    {
        return [
            new AuthoredExercise(Stage::Task, Format::SpeakAnswer, 'x', ['prompt' => 'Fala português?']),
            new AuthoredExercise(Stage::Task, Format::TypeWord, 'y', ['text' => 'not spoken']),
            new AuthoredExercise(Stage::Task, Format::ListenType, 'z', [], accepted: ['Eu vivo aqui.']),
            new AuthoredExercise(Stage::Task, Format::SpeakRepeat, 'w', ['text' => 'Bom dia.'], accepted: ['ignored']),
            new AuthoredExercise(Stage::Task, Format::ListenPair, 'p', ['options' => ['a casa', 'o carro'], 'answer' => 'a casa', 'english' => 'the house']),
            new AuthoredExercise(Stage::Task, Format::ListenPassage, 'q', ['dialogue' => [['speaker' => 'A', 'text' => 'Olá!'], 'junk', ['speaker' => 'B']]]),
        ];
    }

    public function reviews(): array
    {
        return [];
    }
}

function corpus(UnitContent ...$contents): SpeechCorpus
{
    app()->instance(UnitContentRegistry::class, new UnitContentRegistry(array_values($contents)));

    return app(SpeechCorpus::class);
}

it('collects the spoken strings of unit content for one language', function (): void {
    $texts = corpus(new HotelContent, new PortugueseStubContent)->texts('es');

    expect($texts)->toContain('el recepcionista', 'disponible', 'disponibles', 'incluida')
        ->toContain('El hotel está cerca.')
        ->toContain('¿Hay una habitación disponible para dos noches?')
        ->toContain('La reserva es para dos noches.')
        ->toContain('¿Tiene una reserva?', 'Su habitación es la tres.')
        ->not->toContain('Sí, para dos noches.')
        ->not->toContain('o pão');
});

it('speaks the prompt of a speak-answer exercise and nothing from text-only formats', function (): void {
    $texts = corpus(new PortugueseStubContent)->texts('pt');

    expect($texts)->toBe(['Bom dia.', 'Eu vivo aqui.', 'Fala português?', 'Olá!', 'a casa', 'o pão']);
});

it('collects stored vocabulary, transcripts, shadowing and drills', function (): void {
    $es = Language::factory()->create(['code' => 'es']);
    $pt = Language::factory()->create(['code' => 'pt']);

    VocabularyItem::factory()->create(['language_id' => $es->id, 'term' => 'el gato']);
    VocabularyItem::factory()->create(['language_id' => $pt->id, 'term' => 'o gato']);
    ListeningExercise::factory()->create(['language_id' => $es->id, 'transcript' => 'Buenos días.']);
    ShadowingExercise::factory()->create(['language_id' => $es->id, 'target_transcript' => 'Mucho gusto.']);
    PronunciationDrillExercise::factory()->create(['language_id' => $es->id, 'word_a' => 'pero', 'word_b' => 'perro', 'target_word' => 'perro']);

    expect(corpus()->texts('es'))->toBe(['Buenos días.', 'Mucho gusto.', 'el gato', 'pero', 'perro']);
});

it('returns each normalised string once, sorted', function (): void {
    $es = Language::factory()->create(['code' => 'es']);

    VocabularyItem::factory()->create(['language_id' => $es->id, 'term' => 'la  casa ']);
    ShadowingExercise::factory()->create(['language_id' => $es->id, 'target_transcript' => 'la casa']);
    ShadowingExercise::factory()->create(['language_id' => $es->id, 'target_transcript' => '   ']);

    expect(corpus()->texts('es'))->toBe(['la casa']);
});

it('returns nothing stored for an unknown language', function (): void {
    expect(corpus()->texts('xx'))->toBe([]);
});
