<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Lessons\RenderLessonReviewSheet;
use App\Models\Unit;
use App\Services\UnitContentRegistry;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('lessons:review-sheet {language : The language code, es or pt} {unit : The unit slug} {--audience=reviewer : reviewer or owner}')]
#[Description('Render a unit as a Markdown review sheet; the owner sheet leaves out the check')]
class LessonReviewSheet extends Command
{
    public function handle(UnitContentRegistry $registry, RenderLessonReviewSheet $renderLessonReviewSheet): int
    {
        $audience = $this->option('audience');

        if (! in_array($audience, ['reviewer', 'owner'], true)) {
            $this->error('The audience is reviewer or owner.');

            return self::FAILURE;
        }

        foreach ($registry->all() as $content) {
            if ($content->languageCode() !== $this->argument('language') || $content->unitSlug() !== $this->argument('unit')) {
                continue;
            }

            $unit = Unit::query()->where('slug', $content->unitSlug())->whereHas('language', fn ($query) => $query->where('code', $content->languageCode()))->first();

            if ($unit === null) {
                break;
            }

            $this->output->write($renderLessonReviewSheet->handle($unit, $content, $audience === 'owner'));

            return self::SUCCESS;
        }

        $this->error('No content for that language and unit.');

        return self::FAILURE;
    }
}
