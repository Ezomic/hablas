<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class TalkingAboutYourFamily implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'talking-about-your-family';
    }

    public function words(): array
    {
        return [
            new WordData('la famiglia', cue: 'family'),
            new WordData('il padre', cue: 'father'),
            new WordData('la madre', cue: 'mother'),
            new WordData('il fratello', cue: 'brother'),
            new WordData('la sorella', cue: 'sister'),
            new WordData('il figlio', cue: 'son'),
            new WordData('i nonni', cue: 'grandparents'),
            new WordData('sposato', cue: 'married (masculine)', forms: ['sposata', 'sposati', 'sposate']),
            new WordData('single', cue: 'single, unmarried (the same for a man and a woman)', accepted: ['celibe', 'nubile']),
            new WordData('maggiore', cue: 'older, elder (of the siblings; the same for a man and a woman)', forms: ['maggiori']),
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
