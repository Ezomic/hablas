<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class ShoppingForClothes implements UnitContent
{
    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'shopping-for-clothes';
    }

    public function words(): array
    {
        return [
            new WordData('les vêtements', cue: 'clothes', accepted: ['les habits']),
            new WordData('la chemise', cue: 'shirt'),
            new WordData('le pantalon', cue: 'trousers (one word in French, singular)', accepted: ['les pantalons']),
            new WordData('le prix', cue: 'price'),
            new WordData('la taille', cue: 'size (of clothes)'),
            new WordData('la couleur', cue: 'color'),
            new WordData('cher', cue: 'expensive (masculine)', forms: ['chère', 'chers', 'chères']),
            new WordData('bon marché', cue: 'cheap, inexpensive (never changes, whatever the noun)', accepted: ['pas cher']),
            new WordData('essayer', cue: 'to try on (clothes)', forms: ['j\'essaie']),
            new WordData('la réduction', cue: 'discount', accepted: ['la remise']),
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
