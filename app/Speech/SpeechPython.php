<?php

declare(strict_types=1);

namespace App\Speech;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Facades\Process;
use RuntimeException;

final class SpeechPython
{
    public function __construct(
        private readonly Repository $config,
        private readonly SpeechFailures $failures,
    ) {}

    /**
     * @param  list<array<string, mixed>>  $requests
     * @return array<int, array<string, mixed>> the helper's answer per request, keyed by the request's position
     */
    public function run(string $script, array $requests): array
    {
        $input = '';

        foreach ($requests as $position => $request) {
            $input .= json_encode(['id' => $position] + $request, JSON_THROW_ON_ERROR)."\n";
        }

        $modelsDir = $this->config->get('speech.models_dir');

        $result = Process::path(base_path())
            ->timeout(3600)
            ->env(is_string($modelsDir) && $modelsDir !== '' ? ['SPEECH_MODELS_DIR' => $modelsDir] : [])
            ->input($input)
            ->run([$this->interpreter(), base_path("scripts/tts/{$script}")]);

        $answers = [];

        foreach (explode("\n", $result->output()) as $line) {
            $row = json_decode($line, true);

            if (is_array($row) && is_string($row['error'] ?? null)) {
                $this->failures->record($row['error']);
            }

            if (is_array($row) && is_int($row['id'] ?? null)) {
                $answers[$row['id']] = array_filter($row, is_string(...), ARRAY_FILTER_USE_KEY);
            }
        }

        if ($answers === [] && $requests !== [] && $result->failed()) {
            throw new RuntimeException("Speech helper {$script} failed: ".trim(substr($result->errorOutput(), -1000)));
        }

        return $answers;
    }

    private function interpreter(): string
    {
        $python = $this->config->string('speech.python');

        return str_contains($python, '/') && ! str_starts_with($python, '/') ? base_path($python) : $python;
    }
}
