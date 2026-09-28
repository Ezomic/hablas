<?php

declare(strict_types=1);

namespace App\Enums;

enum WritingExerciseType: string
{
    case FillInTemplate = 'fill_in_template';
    case GuidedParagraph = 'guided_paragraph';
    case SentenceTransformation = 'sentence_transformation';
}
