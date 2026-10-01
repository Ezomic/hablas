<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\ContentReview;
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
            new WordData('el menú', cue: 'menu (the list of dishes)', accepted: ['la carta'], note: 'This is the list of dishes. In Spain la carta means the same and is also accepted.'),
            new WordData('la cuenta', cue: 'bill (to pay at the end)'),
            new WordData('quisiera', cue: 'I would like', accepted: ['me gustaría', 'yo quisiera']),
            new WordData('para beber', cue: 'to drink (as in something to drink)', accepted: ['de beber', 'algo de beber', 'algo para beber']),
            new WordData('para comer', cue: 'to eat (as in something to eat)', accepted: ['de comer', 'algo de comer', 'algo para comer']),
            new WordData('el camarero', cue: 'waiter (or waitress)', accepted: ['la camarera']),
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
        return [
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (dictionary pass)', '2026-10-01', 'Sources: RAE excerpts via search (dle.rae.es blocked direct fetch), Wikcionario (menú also means carta, the list of dishes), SpanishDict. Fixed: accepted yo quisiera, algo de beber, algo para beber, algo de comer, algo para comer. el menú kept with la carta accepted, la camarera accepted. Open questions answered and removed.'),
        ];
    }
}
