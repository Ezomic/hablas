<?php

declare(strict_types=1);

namespace App\Enums;

enum LessonStage: string
{
    case Meet = 'meet';
    case Recall = 'recall';
    case Sentences = 'sentences';
    case Task = 'task';
    case Check = 'check';

    public function position(): int
    {
        return match ($this) {
            self::Meet => 1,
            self::Recall => 2,
            self::Sentences => 3,
            self::Task => 4,
            self::Check => 5,
        };
    }

    public function title(): string
    {
        return match ($this) {
            self::Meet => 'Meet the words',
            self::Recall => 'Recall the words',
            self::Sentences => 'Build sentences',
            self::Task => 'Do the task',
            self::Check => 'Unit check',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Meet => __('Meet the words'),
            self::Recall => __('Recall the words'),
            self::Sentences => __('Build sentences'),
            self::Task => __('Do the task'),
            self::Check => __('Unit check'),
        };
    }

    /**
     * Whether an answer given after a hint still counts as right first time.
     * Hints are free in lesson 1 only.
     */
    public function hintsAreFree(): bool
    {
        return $this === self::Meet;
    }

    /**
     * What a missing accent does to an answer at this stage: forgiven and
     * counted as right in lessons 1 and 2, accepted with a note in 3 and 4,
     * wrong in the check. A dropped accent that makes another word is forgiven
     * in every lesson, with a note, and wrong in the check.
     */
    public function accentPolicy(): AccentPolicy
    {
        return match ($this) {
            self::Meet, self::Recall => AccentPolicy::Forgive,
            self::Sentences, self::Task => AccentPolicy::Note,
            self::Check => AccentPolicy::Reject,
        };
    }

    /** @return float the speech synthesis rate, 1.0 being normal speed */
    public function audioSpeed(): float
    {
        return match ($this) {
            self::Meet => 0.75,
            self::Recall => 0.9,
            self::Sentences, self::Task, self::Check => 1.0,
        };
    }

    /** @return int|null how many times the clip may be replayed; null is unlimited */
    public function replayLimit(): ?int
    {
        return match ($this) {
            self::Meet, self::Recall, self::Sentences, self::Task => null,
            self::Check => 2,
        };
    }

    public function offersSlowerAudio(): bool
    {
        return in_array($this, [self::Meet, self::Recall, self::Sentences], true);
    }

    public function isEvidenceStage(): bool
    {
        return $this->position() >= 3;
    }
}
