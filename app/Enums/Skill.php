<?php

declare(strict_types=1);

namespace App\Enums;

enum Skill: string
{
    case Reading = 'reading';
    case Listening = 'listening';
    case Speaking = 'speaking';
    case Writing = 'writing';
}
