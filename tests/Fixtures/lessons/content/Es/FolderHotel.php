<?php

declare(strict_types=1);

namespace Tests\Fixtures\Lessons\Content\Es;

use App\Lessons\UnitContent;

final class FolderHotel implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'checking-into-a-hotel';
    }

    public function words(): array
    {
        return [];
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
