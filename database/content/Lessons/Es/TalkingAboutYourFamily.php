<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\ContentReview;
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
            new WordData('el padre', cue: 'father', accepted: ['el papá']),
            new WordData('la madre', cue: 'mother', accepted: ['la mamá']),
            new WordData('el hermano', cue: 'brother'),
            new WordData('la hermana', cue: 'sister'),
            new WordData('el hijo', cue: 'son'),
            new WordData('los abuelos', cue: 'grandparents'),
            new WordData('casado', cue: 'married (masculine)', forms: ['casada']),
            new WordData('soltero', cue: 'single, not married (masculine)', forms: ['soltera']),
            new WordData('mayor', cue: 'older (than someone else)', accepted: ['más viejo']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Mi hermano es mayor.', 'english' => 'My brother is older.'],
            ['text' => 'Mis hermanos son mayores.', 'english' => 'My brothers and sisters are older.'],
        ];
    }

    public function exercises(): array
    {
        return [];
    }

    public function reviews(): array
    {
        return [
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (dictionary pass)', '2026-10-01', 'Sources: RAE excerpts via search (dle.rae.es blocked direct fetch), WordReference. papá and mamá accepted for padre and madre (accents kept). Fixed: accepted más viejo for mayor; grammar example Mis abuelos son mayores replaced by Mis hermanos son mayores (mayores alone with grandparents reads as elderly, not older). Open questions answered and removed.'),
        ];
    }
}
