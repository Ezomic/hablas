<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class GreetingsAndIntroductions implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'greetings-and-introductions';
    }

    public function words(): array
    {
        return [
            new WordData('ciao', cue: 'hi, or bye, between friends'),
            new WordData('buongiorno', cue: 'good morning, good day (until the afternoon)', accepted: ['buon giorno']),
            new WordData('buonasera', cue: 'good evening (the greeting from the afternoon on)', accepted: ['buona sera']),
            new WordData('buonanotte', cue: 'good night (said when going to bed)', accepted: ['buona notte']),
            new WordData('arrivederci', cue: 'goodbye'),
            new WordData('mi chiamo', cue: 'my name is (introducing yourself)'),
            new WordData('piacere', cue: 'nice to meet you', accepted: ['molto piacere']),
            new WordData('come stai?', cue: 'how are you? (informal, to a friend)', accepted: ['come stai']),
            new WordData('bene', cue: 'well, fine (as in sto bene, I am fine)'),
            new WordData('grazie', cue: 'thank you', accepted: ['mille grazie', 'molte grazie']),
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
