<?php

declare(strict_types=1);

namespace App\Enums;

enum ReviewScope: string
{
    case Words = 'words';
    case Lessons = 'lessons';
}
