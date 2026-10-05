<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class AskingForDirections implements UnitContent
{
    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'asking-for-directions';
    }

    public function words(): array
    {
        return [
            new WordData('la rue', cue: 'street'),
            new WordData('le coin', cue: 'corner (of a street)'),
            new WordData('à droite', cue: 'to the right'),
            new WordData('à gauche', cue: 'to the left'),
            new WordData('tout droit', cue: 'straight ahead'),
            new WordData('près', cue: 'near, close', accepted: ['près de']),
            new WordData('loin', cue: 'far', accepted: ['loin de']),
            new WordData('le plan', cue: 'map of a town', accepted: ['la carte']),
            new WordData('où est… ?', cue: 'where is…? (asking for a place)', accepted: ['où se trouve… ?']),
            new WordData('la place', cue: 'square, plaza (in a town)'),
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
