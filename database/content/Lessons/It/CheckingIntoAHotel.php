<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class CheckingIntoAHotel implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'checking-into-a-hotel';
    }

    public function words(): array
    {
        return [
            new WordData('l\'albergo', cue: 'hotel', accepted: ['l\'hotel']),
            new WordData('la camera', cue: 'room (in a hotel)'),
            new WordData('la prenotazione', cue: 'reservation'),
            new WordData('la chiave', cue: 'key'),
            new WordData('il receptionist', cue: 'receptionist (a man; the word is the same for a woman)', accepted: ['la receptionist']),
            new WordData('disponibile', cue: 'available', forms: ['disponibili']),
            new WordData('la notte', cue: 'night'),
            new WordData('il bagno', cue: 'bathroom (the room with the bath or shower)'),
            new WordData('incluso', cue: 'included (masculine)', forms: ['inclusa', 'inclusi', 'incluse']),
            new WordData('la colazione', cue: 'breakfast'),
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
