<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class DescribingYourDailyRoutine implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'describing-your-daily-routine';
    }

    public function words(): array
    {
        return [
            new WordData('levantarse', cue: 'to get up', forms: ['me levanto'], questions: ['The wrong-person distractors are first-person forms such as "me levanto", shown against the infinitive. Confirm none can be read as a right answer to the infinitive cue.']),
            new WordData('despertarse', cue: 'to wake up', forms: ['me despierto']),
            new WordData('ducharse', cue: 'to shower', forms: ['me ducho']),
            new WordData('desayunar', cue: 'to have breakfast', forms: ['desayuno']),
            new WordData('trabajar', cue: 'to work', forms: ['trabajo']),
            new WordData('acostarse', cue: 'to go to bed', forms: ['me acuesto']),
            new WordData('temprano', cue: 'early'),
            new WordData('tarde', cue: 'late (not early)'),
            new WordData('todos los días', cue: 'every day'),
            new WordData('normalmente', cue: 'normally'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Me levanto temprano.', 'english' => 'I get up early.'],
            ['text' => 'Ella se acuesta tarde.', 'english' => 'She goes to bed late.'],
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
