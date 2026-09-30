<?php

declare(strict_types=1);

namespace App\Enums;

enum VocabularySort: string
{
    case Recent = 'recent';
    case Due = 'due';
    case Alphabetical = 'alphabetical';
}
