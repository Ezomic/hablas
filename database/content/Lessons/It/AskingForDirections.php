<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class AskingForDirections implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'asking-for-directions';
    }

    public function words(): array
    {
        return [
            new WordData('la strada', cue: 'street', accepted: ['la via']),
            new WordData('l\'angolo', cue: 'corner (of a street)'),
            new WordData('a destra', cue: 'to the right'),
            new WordData('a sinistra', cue: 'to the left'),
            new WordData('sempre dritto', cue: 'straight ahead', accepted: ['dritto', 'sempre diritto', 'tutto dritto']),
            new WordData('vicino', cue: 'near, close', accepted: ['vicino a']),
            new WordData('lontano', cue: 'far', accepted: ['lontano da']),
            new WordData('la cartina', cue: 'map of a town', accepted: ['la mappa']),
            new WordData('dov\'è… ?', cue: 'where is…? (asking for a place)', accepted: ['dove è… ?']),
            new WordData('la piazza', cue: 'square, plaza (in a town)'),
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
