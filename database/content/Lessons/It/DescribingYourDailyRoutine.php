<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class DescribingYourDailyRoutine implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'describing-your-daily-routine';
    }

    public function words(): array
    {
        return [
            new WordData('alzarsi', cue: 'to get up', forms: ['mi alzo']),
            new WordData('svegliarsi', cue: 'to wake up', forms: ['mi sveglio']),
            new WordData('farsi la doccia', cue: 'to shower', accepted: ['fare la doccia'], forms: ['mi faccio la doccia']),
            new WordData('fare colazione', cue: 'to have breakfast', forms: ['faccio colazione']),
            new WordData('lavorare', cue: 'to work', forms: ['lavoro']),
            new WordData('andare a letto', cue: 'to go to bed', accepted: ['andare a dormire'], forms: ['vado a letto']),
            new WordData('presto', cue: 'early'),
            new WordData('tardi', cue: 'late (not early)'),
            new WordData('ogni giorno', cue: 'every day', accepted: ['tutti i giorni']),
            new WordData('di solito', cue: 'normally, usually', accepted: ['normalmente']),
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
