<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class CheckingIntoAHotel implements UnitContent
{
    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'checking-into-a-hotel';
    }

    public function words(): array
    {
        return [
            new WordData('l\'hôtel', cue: 'hotel'),
            new WordData('la chambre', cue: 'room (in a hotel)'),
            new WordData('la réservation', cue: 'reservation'),
            new WordData('la clé', cue: 'key', accepted: ['la clef']),
            new WordData('le réceptionniste', cue: 'receptionist (a man)', accepted: ['la réceptionniste']),
            new WordData('disponible', cue: 'available', forms: ['disponibles']),
            new WordData('la nuit', cue: 'night'),
            new WordData('la salle de bain', cue: 'bathroom (the room with the bath or shower)', accepted: ['la salle de bains']),
            new WordData('compris', cue: 'included (masculine)', forms: ['comprise', 'comprises']),
            new WordData('le petit-déjeuner', cue: 'breakfast'),
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
