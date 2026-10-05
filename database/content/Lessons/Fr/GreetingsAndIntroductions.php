<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class GreetingsAndIntroductions implements UnitContent
{
    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'greetings-and-introductions';
    }

    public function words(): array
    {
        return [
            new WordData('bonjour', cue: 'hello, good day (the standard greeting until the evening)', accepted: ['salut']),
            new WordData('bonsoir', cue: 'good evening (the greeting after dark)'),
            new WordData('salut', cue: 'hi, or bye, between friends'),
            new WordData('au revoir', cue: 'goodbye'),
            new WordData('je m\'appelle', cue: 'my name is (introducing yourself)', accepted: ['mon nom est', 'moi, je m\'appelle']),
            new WordData('enchanté', cue: 'nice to meet you (said by a man)', accepted: ['enchantée']),
            new WordData('comment allez-vous ?', cue: 'how are you? (formal, to one person or more)', accepted: ['comment allez-vous']),
            new WordData('ça va ?', cue: 'how are you? (informal)', accepted: ['ça va', 'comment ça va ?', 'comment vas-tu ?']),
            new WordData('bien', cue: 'well, fine (as in I am fine)', accepted: ['ça va bien']),
            new WordData('merci', cue: 'thank you', accepted: ['merci beaucoup']),
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
