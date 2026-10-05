<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class ShoppingForClothes implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'shopping-for-clothes';
    }

    public function words(): array
    {
        return [
            new WordData('i vestiti', cue: 'clothes'),
            new WordData('la camicia', cue: 'shirt'),
            new WordData('i pantaloni', cue: 'trousers (always plural in Italian)'),
            new WordData('il prezzo', cue: 'price'),
            new WordData('la taglia', cue: 'size (of clothes)'),
            new WordData('il colore', cue: 'color'),
            new WordData('caro', cue: 'expensive (masculine)', forms: ['cara', 'cari', 'care']),
            new WordData('economico', cue: 'cheap, inexpensive (masculine)', forms: ['economica', 'economici', 'economiche']),
            new WordData('provare', cue: 'to try on (clothes)', forms: ['provo']),
            new WordData('lo sconto', cue: 'discount'),
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
