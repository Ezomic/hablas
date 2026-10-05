<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class DescribingYourDailyRoutine implements UnitContent
{
    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'describing-your-daily-routine';
    }

    public function words(): array
    {
        return [
            new WordData('se lever', cue: 'to get up', forms: ['je me lève']),
            new WordData('se réveiller', cue: 'to wake up', forms: ['je me réveille']),
            new WordData('se doucher', cue: 'to shower', accepted: ['prendre une douche'], forms: ['je me douche']),
            new WordData('prendre le petit-déjeuner', cue: 'to have breakfast', forms: ['je prends le petit-déjeuner']),
            new WordData('travailler', cue: 'to work', forms: ['je travaille']),
            new WordData('se coucher', cue: 'to go to bed', forms: ['je me couche']),
            new WordData('tôt', cue: 'early'),
            new WordData('tard', cue: 'late (not early)'),
            new WordData('tous les jours', cue: 'every day', accepted: ['chaque jour']),
            new WordData('normalement', cue: 'normally', accepted: ['d\'habitude']),
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
