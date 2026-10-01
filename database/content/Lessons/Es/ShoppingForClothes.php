<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\ContentReview;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class ShoppingForClothes implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'shopping-for-clothes';
    }

    public function words(): array
    {
        return [
            new WordData('la ropa', cue: 'clothes'),
            new WordData('la camisa', cue: 'shirt'),
            new WordData('los pantalones', cue: 'trousers', accepted: ['el pantalón']),
            new WordData('el precio', cue: 'price'),
            new WordData('la talla', cue: 'size (of clothes)'),
            new WordData('el color', cue: 'color'),
            new WordData('caro', cue: 'expensive (masculine)', forms: ['cara']),
            new WordData('barato', cue: 'cheap (masculine)', forms: ['barata']),
            new WordData('probarse', cue: 'to try on (clothes)', forms: ['me pruebo']),
            new WordData('el descuento', cue: 'discount'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'La camisa es cara.', 'english' => 'The shirt is expensive.'],
            ['text' => 'Los pantalones son baratos.', 'english' => 'The trousers are cheap.'],
        ];
    }

    public function exercises(): array
    {
        return [];
    }

    public function reviews(): array
    {
        return [
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (dictionary pass)', '2026-10-01', 'Sources: RAE excerpts via search (dle.rae.es blocked direct fetch), WordReference. los pantalones with el pantalón accepted; me pruebo is a finite form so it is definitely wrong against the infinitive cue. No data fixes. Open questions answered and removed. The seeder gloss pants (American) is outside this file.'),
        ];
    }
}
