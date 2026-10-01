<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

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
            new WordData('hola', cue: 'hello (informal greeting)'),
            new WordData('buenos días', cue: 'good morning'),
            new WordData('buenas tardes', cue: 'good afternoon'),
            new WordData('buenas noches', cue: 'good evening or good night (greeting after dark)', questions: ['The gloss covers two English greetings. Is one phrase right for both in Spain?']),
            new WordData('adiós', cue: 'goodbye'),
            new WordData('me llamo', cue: 'my name is (introducing yourself)', accepted: ['mi nombre es'], questions: ['The seeder expects only "me llamo". "mi nombre es" is also accepted here. Is it natural at A1, and should "me llamo" stay the primary answer?']),
            new WordData('mucho gusto', cue: 'nice to meet you', accepted: ['encantado', 'encantada'], questions: ['"encantado" and "encantada" are accepted as the usual reply in Spain. Is "mucho gusto" natural in Spain or mostly Latin American?']),
            new WordData('¿cómo estás?', cue: 'how are you?', accepted: ['¿cómo está?'], questions: ['The seeder expects only the tú form. The usted form "¿cómo está?" is accepted too. Should "¿qué tal?" be accepted as well?']),
            new WordData('bien', cue: 'well, fine (as in I am fine)'),
            new WordData('gracias', cue: 'thank you', questions: ['Should "muchas gracias" be accepted for "thank you"?']),
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
        return [];
    }
}
