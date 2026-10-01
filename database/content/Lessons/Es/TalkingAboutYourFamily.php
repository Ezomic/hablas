<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class TalkingAboutYourFamily implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'talking-about-your-family';
    }

    public function words(): array
    {
        return [
            new WordData('la familia', cue: 'family'),
            new WordData('el padre', cue: 'father', accepted: ['el papá'], questions: ['"el papá" is accepted for "father". Confirm.']),
            new WordData('la madre', cue: 'mother', accepted: ['la mamá'], questions: ['"la mamá" is accepted for "mother". Confirm.']),
            new WordData('el hermano', cue: 'brother'),
            new WordData('la hermana', cue: 'sister'),
            new WordData('el hijo', cue: 'son'),
            new WordData('los abuelos', cue: 'grandparents'),
            new WordData('casado', cue: 'married (masculine)', forms: ['casada']),
            new WordData('soltero', cue: 'single, not married (masculine)', forms: ['soltera']),
            new WordData('mayor', cue: 'older (than someone else)', questions: ['"mayor" also means major or adult. The cue limits it to "older". Confirm.']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Mi hermano es mayor.', 'english' => 'My brother is older.'],
            ['text' => 'Mis abuelos son mayores.', 'english' => 'My grandparents are older.'],
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
