<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class AtTheAirport implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'at-the-airport';
    }

    public function words(): array
    {
        return [
            new WordData('el aeropuerto', cue: 'airport'),
            new WordData('el vuelo', cue: 'flight'),
            new WordData('la maleta', cue: 'suitcase'),
            new WordData('el pasaporte', cue: 'passport'),
            new WordData('la puerta', cue: 'gate (at an airport)', questions: ['The gloss is "gate / door" and the cue asks for the airport sense. Is "la puerta" right for an airport gate in Spain, or is "la puerta de embarque" expected?']),
            new WordData('la salida', cue: 'exit, or departure (on an airport board)', questions: ['The gloss is "departure / exit". Spanish airport boards read "Salidas" for departures, so the word is right. Is the double gloss a problem for a single typed answer?']),
            new WordData('la llegada', cue: 'arrival'),
            new WordData('el billete', cue: 'ticket (for a flight or train)'),
            new WordData('retrasado', cue: 'delayed (masculine)', forms: ['retrasada']),
            new WordData('internacional', cue: 'international'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'El vuelo es largo.', 'english' => 'The flight is long.'],
            ['text' => 'La maleta es grande.', 'english' => 'The suitcase is big.'],
        ];
    }

    public function exercises(): array
    {
        return [];
    }

    public function reviews(): array
    {
        return [];
    }
}
