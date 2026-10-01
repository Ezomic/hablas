<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\ContentReview;
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
            new WordData('todo recto', cue: 'straight ahead', accepted: ['todo derecho']),
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
        return [
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (dictionary pass)', '2026-10-01', 'Sources: WordReference forum, SpanishDict, Kwiziq. todo recto is the Spain form and todo derecho is also valid, both accepted. No data fixes. Open question answered and removed.'),
        ];
    }
}
