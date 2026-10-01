<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

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
            new WordData('los pantalones', cue: 'trousers (pants)', accepted: ['el pantalón'], questions: ['The seeder glosses this as "pants", which is American. The cue says "trousers (pants)" and the singular "el pantalón" is accepted too. Confirm.']),
            new WordData('el precio', cue: 'price'),
            new WordData('la talla', cue: 'size (of clothes)'),
            new WordData('el color', cue: 'color'),
            new WordData('caro', cue: 'expensive (masculine)', forms: ['cara']),
            new WordData('barato', cue: 'cheap (masculine)', forms: ['barata']),
            new WordData('probarse', cue: 'to try on (clothes)', forms: ['me pruebo'], questions: ['The wrong-person distractor "me pruebo" is shown against the infinitive. Confirm it cannot be read as a right answer to "to try on".']),
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
        return [];
    }
}
