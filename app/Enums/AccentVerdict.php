<?php

declare(strict_types=1);

namespace App\Enums;

enum AccentVerdict: string
{
    case Exact = 'exact';
    case Missing = 'missing';
    case OtherWord = 'other_word';
}
