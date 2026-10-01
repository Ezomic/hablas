<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\ContentReview;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class GreetingsAndIntroductions implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'greetings-and-introductions';
    }

    public function words(): array
    {
        return [
            new WordData('hola', cue: 'hello'),
            new WordData('buenos días', cue: 'good morning'),
            new WordData('buenas tardes', cue: 'good afternoon'),
            new WordData('buenas noches', cue: 'good evening or good night (greeting after dark)'),
            new WordData('adiós', cue: 'goodbye'),
            new WordData('me llamo', cue: 'my name is (introducing yourself)', accepted: ['mi nombre es', 'yo me llamo']),
            new WordData('mucho gusto', cue: 'nice to meet you', accepted: ['encantado', 'encantada', 'encantado de conocerte', 'encantada de conocerte', 'mucho gusto en conocerte']),
            new WordData('¿cómo estás?', cue: 'how are you?', accepted: ['¿cómo está?', '¿qué tal?', '¿qué tal estás?']),
            new WordData('bien', cue: 'well, fine (as in I am fine)', accepted: ['estoy bien']),
            new WordData('gracias', cue: 'thank you', accepted: ['muchas gracias']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Soy Ana.', 'english' => 'I am Ana.'],
            ['text' => 'Ella es mi amiga.', 'english' => 'She is my friend.'],
        ];
    }

    public function exercises(): array
    {
        return [];
    }

    public function reviews(): array
    {
        return [
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (dictionary pass)', '2026-10-01', 'Sources: RAE excerpts via search (dle.rae.es blocked direct fetch), WordReference forum, SpanishDict, hinative. Fixed: cue for hola no longer says informal; accepted yo me llamo, encantado de conocerte (m/f), mucho gusto en conocerte, qué tal, qué tal estás, estoy bien, muchas gracias. mucho gusto is correct but more formal in Spain, encantado/a stays accepted. Open questions answered and removed.'),
        ];
    }
}
