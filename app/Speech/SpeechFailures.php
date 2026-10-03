<?php

declare(strict_types=1);

namespace App\Speech;

final class SpeechFailures
{
    /** @var list<string> */
    private array $messages = [];

    public function record(string $message): void
    {
        $this->messages[] = $message;
    }

    /** @return list<string> */
    public function drain(): array
    {
        $messages = $this->messages;
        $this->messages = [];

        return $messages;
    }
}
