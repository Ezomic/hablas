<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class AtTheAirport implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'at-the-airport';
    }

    public function words(): array
    {
        return [
            new WordData('l\'aeroporto', cue: 'airport'),
            new WordData('il volo', cue: 'flight'),
            new WordData('la valigia', cue: 'suitcase'),
            new WordData('il passaporto', cue: 'passport'),
            new WordData('l\'uscita', cue: 'gate (as on an airport board, "uscita B12")', accepted: ['il gate', 'il cancello']),
            new WordData('la partenza', cue: 'departure (on an airport board)'),
            new WordData('l\'arrivo', cue: 'arrival'),
            new WordData('il biglietto', cue: 'ticket (for a flight or train)'),
            new WordData('in ritardo', cue: 'delayed, late'),
            new WordData('internazionale', cue: 'international', forms: ['internazionali']),
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
