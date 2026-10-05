<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class AtTheAirport implements UnitContent
{
    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'at-the-airport';
    }

    public function words(): array
    {
        return [
            new WordData('l\'aéroport', cue: 'airport'),
            new WordData('le vol', cue: 'flight'),
            new WordData('la valise', cue: 'suitcase', accepted: ['le bagage']),
            new WordData('le passeport', cue: 'passport'),
            new WordData('la porte', cue: 'gate (at an airport)', accepted: ['la porte d\'embarquement']),
            new WordData('le départ', cue: 'departure (on an airport board)'),
            new WordData('l\'arrivée', cue: 'arrival'),
            new WordData('le billet', cue: 'ticket (for a flight or train)'),
            new WordData('en retard', cue: 'delayed, late', accepted: ['retardé']),
            new WordData('international', cue: 'international', forms: ['internationale', 'internationaux', 'internationales']),
        ];
    }

    public function grammarExamples(): array
    {
        return [];
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
