<?php

declare(strict_types=1);

namespace App\Enums;

enum AccentPolicy: string
{
    case Forgive = 'forgive';
    case Note = 'note';
    case Reject = 'reject';
}
