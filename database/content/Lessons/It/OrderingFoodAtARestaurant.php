<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class OrderingFoodAtARestaurant implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'ordering-food-at-a-restaurant';
    }

    public function words(): array
    {
        return [
            new WordData('il ristorante', cue: 'restaurant'),
            new WordData('il menù', cue: 'menu, the list of dishes', accepted: ['il menu']),
            new WordData('il conto', cue: 'the bill (in a restaurant)'),
            new WordData('vorrei', cue: 'I would like (polite request)'),
            new WordData('da bere', cue: 'something to drink (as in "qualcosa da bere")'),
            new WordData('da mangiare', cue: 'something to eat (as in "qualcosa da mangiare")'),
            new WordData('il cameriere', cue: 'waiter (a man)', accepted: ['la cameriera']),
            new WordData('delizioso', cue: 'delicious (masculine)', accepted: ['squisito'], forms: ['deliziosa', 'deliziosi', 'deliziose']),
            new WordData('la mancia', cue: 'tip (money left for the waiter)'),
            new WordData('vegetariano', cue: 'vegetarian (masculine)', forms: ['vegetariana', 'vegetariani', 'vegetariane']),
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
