<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class AskingForDirections implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'asking-for-directions';
    }

    public function words(): array
    {
        return [
            new WordData('la calle', cue: 'street'),
            new WordData('la esquina', cue: 'corner (of a street)'),
            new WordData('a la derecha', cue: 'to the right'),
            new WordData('a la izquierda', cue: 'to the left'),
            new WordData('todo recto', cue: 'straight ahead', accepted: ['todo derecho'], questions: ['"todo derecho" is accepted as the other usual form in Spain.']),
            new WordData('cerca', cue: 'near'),
            new WordData('lejos', cue: 'far'),
            new WordData('el mapa', cue: 'map'),
            new WordData('¿dónde está...?', cue: 'where is...? (asking for a place)'),
            new WordData('la plaza', cue: 'square (in a town)'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Como en la plaza.', 'english' => 'I eat in the square.'],
            ['text' => 'Vivimos cerca.', 'english' => 'We live nearby.'],
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
