<?php

declare(strict_types=1);

namespace App\Enums;

enum SrsRating: string
{
    case Again = 'again';
    case Hard = 'hard';
    case Good = 'good';
    case Easy = 'easy';
}
