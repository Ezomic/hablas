<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\ContentReview;
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
            new WordData('la puerta', cue: 'gate (at an airport)', accepted: ['la puerta de embarque']),
            new WordData('la salida', cue: 'exit, or departure (on an airport board)'),
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
        return [
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (dictionary pass)', '2026-10-01', 'Sources: RAE excerpts via search (dle.rae.es blocked direct fetch), Aena boards and site. Fixed: accepted la puerta de embarque for the gate cue. la salida covers exit and departure (boards read Salidas), kept. retrasado is the Aena board wording for a delayed flight (offensive only said of people). Open questions answered and removed.'),
        ];
    }
}
