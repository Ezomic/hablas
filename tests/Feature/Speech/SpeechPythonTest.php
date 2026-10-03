<?php

declare(strict_types=1);

use App\Enums\SpeechSpeed;
use App\Speech\AudioEncoder;
use App\Speech\Mp3Encoder;
use App\Speech\SpeechPython;
use App\Speech\SpeechVoices;
use App\Speech\SupertonicEngine;
use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;

function helperOutput(array ...$rows): string
{
    return implode("\n", array_map(fn (array $row): string => json_encode($row, JSON_THROW_ON_ERROR), $rows))."\n";
}

beforeEach(function (): void {
    config(['speech.python' => 'python3', 'speech.models_dir' => null]);
});

it('sends a JSONL batch to the synthesize helper and decodes the WAVs in order', function (): void {
    Process::fake(['*' => Process::result(helperOutput(
        ['id' => 1, 'error' => 'boom'],
        ['id' => 0, 'wav' => base64_encode('WAV-A')],
    ), exitCode: 1)]);

    $voice = app(SpeechVoices::class)->primary('es');
    $wavs = app(SupertonicEngine::class)->synthesizeBatch($voice, SpeechSpeed::Slow, ['Hola', 'Adiós']);

    expect($wavs)->toBe(['WAV-A', null]);

    Process::assertRan(function (PendingProcess $process): bool {
        $lines = array_map(fn (string $line): mixed => json_decode($line, true), array_filter(explode("\n", (string) $process->input)));

        return $process->command === ['python3', base_path('scripts/tts/synthesize.py')]
            && $lines === [
                ['id' => 0, 'text' => 'Hola', 'voice' => 'F1', 'language' => 'es', 'speed' => 0.8],
                ['id' => 1, 'text' => 'Adiós', 'voice' => 'F1', 'language' => 'es', 'speed' => 0.8],
            ];
    });
});

it('speaks a single text', function (): void {
    Process::fake(['*' => Process::result(helperOutput(['id' => 0, 'wav' => base64_encode('WAV')]))]);

    $voice = app(SpeechVoices::class)->primary('es');

    expect(app(SupertonicEngine::class)->synthesize($voice, SpeechSpeed::Normal, 'Hola'))->toBe('WAV');
});

it('fails loudly when a single text cannot be spoken', function (): void {
    Process::fake(['*' => Process::result(helperOutput(['id' => 0, 'error' => 'boom']), exitCode: 1)]);

    app(SupertonicEngine::class)->synthesize(app(SpeechVoices::class)->primary('es'), SpeechSpeed::Normal, 'Hola');
})->throws(RuntimeException::class, 'could not speak [Hola]');

it('throws with the helper stderr when it produced nothing', function (): void {
    Process::fake(['*' => Process::result('', 'ModuleNotFoundError: supertonic', 1)]);

    app(SupertonicEngine::class)->synthesizeBatch(app(SpeechVoices::class)->primary('es'), SpeechSpeed::Normal, ['Hola']);
})->throws(RuntimeException::class, 'ModuleNotFoundError: supertonic');

it('treats an undecodable WAV as a failure', function (): void {
    Process::fake(['*' => Process::result(helperOutput(['id' => 0, 'wav' => '***']))]);

    expect(app(SupertonicEngine::class)->synthesizeBatch(app(SpeechVoices::class)->primary('es'), SpeechSpeed::Normal, ['Hola']))->toBe([null]);
});

it('runs the helper from the configured interpreter and models directory', function (): void {
    config(['speech.python' => '.venv-tts/bin/python', 'speech.models_dir' => '/models']);
    Process::fake(['*' => Process::result(helperOutput(['id' => 0, 'wav' => base64_encode('WAV')]))]);

    app(SpeechPython::class)->run('synthesize.py', [['text' => 'Hola']]);

    Process::assertRan(fn (PendingProcess $process): bool => $process->command[0] === base_path('.venv-tts/bin/python')
        && $process->environment === ['SPEECH_MODELS_DIR' => '/models']);
});

it('keeps an absolute interpreter and an empty batch as they are', function (): void {
    config(['speech.python' => '/opt/venv/bin/python']);
    Process::fake(['*' => Process::result('')]);

    expect(app(SpeechPython::class)->run('encode.py', []))->toBe([]);

    Process::assertRan(fn (PendingProcess $process): bool => $process->command[0] === '/opt/venv/bin/python' && $process->environment === []);
});

it('encodes WAVs to MP3 through the encode helper', function (): void {
    Process::fake(['*' => Process::result(helperOutput(
        ['id' => 0, 'mp3' => base64_encode('MP3-A'), 'duration_ms' => 1234],
        ['id' => 1, 'error' => 'silent audio'],
    ), exitCode: 1)]);

    $clips = app(Mp3Encoder::class)->encode(['wav-a', 'wav-b']);

    expect($clips[0]?->bytes)->toBe('MP3-A')
        ->and($clips[0]?->durationMs)->toBe(1234)
        ->and($clips[1])->toBeNull();

    Process::assertRan(fn (PendingProcess $process): bool => $process->command === ['python3', base_path('scripts/tts/encode.py')]
        && str_contains((string) $process->input, base64_encode('wav-a')));
});

it('binds the MP3 encoder as the audio encoder', function (): void {
    expect(app(AudioEncoder::class))->toBeInstanceOf(Mp3Encoder::class);
});
