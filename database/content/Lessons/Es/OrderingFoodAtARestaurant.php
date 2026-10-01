<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class OrderingFoodAtARestaurant implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'ordering-food-at-a-restaurant';
    }

    public function words(): array
    {
        return [
            new WordData('el restaurante', cue: 'restaurant'),
            new WordData('el menú', cue: 'menu (the list of dishes)', accepted: ['la carta'], questions: ['In Spain the list of dishes is "la carta" and "el menú" is the set meal (menú del día). The term is kept as seeded and "la carta" is accepted. Should the term be renamed to "la carta", or "el menú" glossed as the set meal?']),
            new WordData('la cuenta', cue: 'bill (to pay at the end)'),
            new WordData('quisiera', cue: 'I would like', accepted: ['me gustaría'], questions: ['The seeder expects only "quisiera". "me gustaría" is accepted as the other usual form.']),
            new WordData('para beber', cue: 'to drink (as in something to drink)', accepted: ['de beber'], questions: ['The cue needs the phrase sense: a bare "beber" is not accepted. Is "de beber" right alongside "para beber"?']),
            new WordData('para comer', cue: 'to eat (as in something to eat)', accepted: ['de comer'], questions: ['The cue needs the phrase sense: a bare "comer" is not accepted. Is "de comer" right alongside "para comer"?']),
            new WordData('el camarero', cue: 'waiter (or waitress)', accepted: ['la camarera'], questions: ['The English is gender-neutral here, so "la camarera" is accepted. Confirm.']),
            new WordData('delicioso', cue: 'delicious (masculine)', forms: ['deliciosa']),
            new WordData('la propina', cue: 'tip (money for the waiter)'),
            new WordData('vegetariano', cue: 'vegetarian (masculine)', forms: ['vegetariana']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'El camarero trabaja aquí.', 'english' => 'The waiter works here.'],
            ['text' => 'Hablamos español.', 'english' => 'We speak Spanish.'],
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
