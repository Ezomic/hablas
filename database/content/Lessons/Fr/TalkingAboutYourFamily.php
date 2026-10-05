<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class TalkingAboutYourFamily implements UnitContent
{
    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'talking-about-your-family';
    }

    public function words(): array
    {
        return [
            new WordData('la famille', cue: 'family'),
            new WordData('le père', cue: 'father'),
            new WordData('la mère', cue: 'mother'),
            new WordData('le frère', cue: 'brother'),
            new WordData('la sœur', cue: 'sister', accepted: ['la soeur']),
            new WordData('le fils', cue: 'son'),
            new WordData('les grands-parents', cue: 'grandparents'),
            new WordData('marié', cue: 'married (masculine)', forms: ['mariée', 'mariés', 'mariées']),
            new WordData('célibataire', cue: 'single, not married (the same for a man and a woman)', forms: ['célibataires']),
            new WordData('aîné', cue: 'older, eldest (of the siblings; masculine)', forms: ['aînée', 'aînés', 'aînées']),
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
