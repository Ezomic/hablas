<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class OrderingFoodAtARestaurant implements UnitContent
{
    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'ordering-food-at-a-restaurant';
    }

    public function words(): array
    {
        return [
            new WordData('le restaurant', cue: 'restaurant'),
            new WordData('le menu', cue: 'the fixed-price meal; the full list of dishes is la carte, not le menu'),
            new WordData('l\'addition', cue: 'the bill (in a restaurant)'),
            new WordData('je voudrais', cue: 'I would like (polite request)'),
            new WordData('à boire', cue: 'something to drink (as in "quelque chose à boire")'),
            new WordData('à manger', cue: 'something to eat (as in "quelque chose à manger")'),
            new WordData('le serveur', cue: 'waiter (a man)', accepted: ['la serveuse']),
            new WordData('délicieux', cue: 'delicious (masculine)', forms: ['délicieuse', 'délicieuses']),
            new WordData('le pourboire', cue: 'tip (money left for the waiter)'),
            new WordData('végétarien', cue: 'vegetarian (masculine)', forms: ['végétarienne', 'végétariens', 'végétariennes']),
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
