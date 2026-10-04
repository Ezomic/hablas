<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Lessons\ListContentWords;
use App\Models\Language;
use App\Services\UnitContentRegistry;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('lessons:words {language : The language code, es, pt, fr or it} {unit? : The unit slug, every unit of the language by default}')]
#[Description('List every distinct target-language word of a unit one per line, for the spelling-dictionary pass')]
class LessonWords extends Command
{
    public function handle(UnitContentRegistry $registry, ListContentWords $listContentWords): int
    {
        $language = Language::query()->where('code', $this->argument('language'))->first();

        if ($language === null) {
            $this->error('Unknown language.');

            return self::FAILURE;
        }

        $words = [];

        foreach ($registry->all() as $content) {
            if ($content->languageCode() === $language->code && ($this->argument('unit') === null || $content->unitSlug() === $this->argument('unit'))) {
                array_push($words, ...$listContentWords->handle($content, $language));
            }
        }

        $words = array_values(array_unique($words));
        sort($words, SORT_STRING);

        foreach ($words as $word) {
            $this->line($word);
        }

        return self::SUCCESS;
    }
}
