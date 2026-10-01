<?php

declare(strict_types=1);

namespace App\Enums;

enum ReviewKind: string
{
    case IndependentAi = 'independent_ai';
    case Owner = 'owner';
}
