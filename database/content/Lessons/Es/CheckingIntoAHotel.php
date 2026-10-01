<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\ContentReview;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class CheckingIntoAHotel implements UnitContent
{
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
            new WordData('el hotel', cue: 'hotel'),
            new WordData('la habitación', cue: 'room (in a hotel)'),
            new WordData('la reserva', cue: 'reservation (booking)'),
            new WordData('la llave', cue: 'key (for your room)'),
            new WordData('el recepcionista', cue: 'receptionist (at the hotel desk)', commonGender: true),
            new WordData('disponible', cue: 'available'),
            new WordData('la noche', cue: 'night'),
            new WordData('el baño', cue: 'bathroom', accepted: ['el cuarto de baño', 'el aseo']),
            new WordData('incluido', cue: 'included (masculine)', forms: ['incluida']),
            new WordData('el desayuno', cue: 'breakfast'),
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
        return [];
    }

    public function reviews(): array
    {
        return [
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (dictionary pass)', '2026-10-01', 'Sources: DLE (recepcionista m. y f.), Wikcionario. Fixed: accepted el aseo for bathroom. el recepcionista and la recepcionista both accepted, spelling confirmed. Open questions answered and removed.'),
        ];
    }
}
