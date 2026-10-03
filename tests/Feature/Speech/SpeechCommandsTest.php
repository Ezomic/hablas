<?php

declare(strict_types=1);

use App\Enums\SpeechSpeed;
use App\Models\Language;
use App\Models\SpeechClip;
use App\Models\VocabularyItem;
use App\Services\UnitContentRegistry;
use App\Speech\AudioEncoder;
use App\Speech\SpeechKey;
use App\Speech\SpeechVoices;
use App\Speech\SupertonicEngine;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Tests\Support\FakeAudioEncoder;
use Tests\Support\FakeSpeechEngine;

beforeEach(function (): void {
    FakeSpeechEngine::$batches = [];
    Storage::fake('public');
    Storage::fake('local');
    config(['speech.engines.supertonic' => FakeSpeechEngine::class]);
    app()->bind(AudioEncoder::class, FakeAudioEncoder::class);
    app()->instance(UnitContentRegistry::class, new UnitContentRegistry([]));

    $es = Language::factory()->create(['code' => 'es']);

    foreach (['el gato', 'la casa', 'mañana'] as $term) {
        VocabularyItem::factory()->create(['language_id' => $es->id, 'term' => $term]);
    }
});

function primaryPath(string $text, SpeechSpeed $speed, string $language = 'es'): string
{
    $voice = app(SpeechVoices::class)->primary($language);

    return SpeechClip::pathFor($language, $voice->id, app(SpeechKey::class)->make($language, $voice->id, $speed, $text));
}

describe('speech:generate', function (): void {
    it('writes both speed variants and records the rows', function (): void {
        $this->artisan('speech:generate', ['language' => 'es'])
            ->expectsOutputToContain('generated 6 of 6 clips, 0 failed, 0 skipped')
            ->assertSuccessful();

        Storage::disk('public')->assertExists(primaryPath('el gato', SpeechSpeed::Normal));
        Storage::disk('public')->assertExists(primaryPath('el gato', SpeechSpeed::Slow));
        expect(SpeechClip::query()->count())->toBe(6)
            ->and(SpeechClip::query()->where('speed', 'slow')->count())->toBe(3);

        $clip = SpeechClip::query()->firstOrFail();

        expect($clip->duration_ms)->toBe(1500)
            ->and($clip->bytes)->toBe(Storage::disk('public')->size($clip->path()))
            ->and(Storage::disk('public')->get(primaryPath('la casa', SpeechSpeed::Slow)))->toBe('mp3:wav|supertonic-f1|slow|la casa');
    });

    it('skips clips whose file exists', function (): void {
        $this->artisan('speech:generate', ['language' => 'es'])->assertSuccessful();
        $spoken = FakeSpeechEngine::spoken();

        $this->artisan('speech:generate', ['language' => 'es'])
            ->expectsOutputToContain('generated 0 of 0 clips, 0 failed, 6 skipped')
            ->assertSuccessful();

        expect(FakeSpeechEngine::spoken())->toBe($spoken);
    });

    it('generates only what is missing after the corpus grows', function (): void {
        $this->artisan('speech:generate', ['language' => 'es'])->assertSuccessful();
        VocabularyItem::factory()->create(['language_id' => Language::query()->where('code', 'es')->value('id'), 'term' => 'el perro']);

        $this->artisan('speech:generate', ['language' => 'es'])
            ->expectsOutputToContain('generated 2 of 2 clips, 0 failed, 6 skipped')
            ->assertSuccessful();
    });

    it('writes nothing on a dry run and estimates the size', function (): void {
        $this->artisan('speech:generate', ['language' => 'es', '--dry-run' => true])
            ->expectsOutputToContain('6 clips to generate, 0 already present, about')
            ->assertSuccessful();

        expect(Storage::disk('public')->allFiles())->toBe([])
            ->and(SpeechClip::query()->count())->toBe(0)
            ->and(FakeSpeechEngine::$batches)->toBe([]);
    });

    it('regenerates existing clips when forced', function (): void {
        $this->artisan('speech:generate', ['language' => 'es'])->assertSuccessful();
        $spoken = FakeSpeechEngine::spoken();

        $this->artisan('speech:generate', ['language' => 'es', '--force' => true])
            ->expectsOutputToContain('generated 6 of 6 clips')
            ->assertSuccessful();

        expect(FakeSpeechEngine::spoken())->toBe($spoken + 6)
            ->and(SpeechClip::query()->count())->toBe(6);
    });

    it('limits the number of texts', function (): void {
        $this->artisan('speech:generate', ['language' => 'es', '--limit' => '2'])
            ->expectsOutputToContain('generated 4 of 4 clips')
            ->assertSuccessful();

        expect(SpeechClip::query()->count())->toBe(4);
    });

    it('generates one speed variant', function (): void {
        $this->artisan('speech:generate', ['language' => 'es', '--speed' => 'slow'])->assertSuccessful();

        expect(SpeechClip::query()->pluck('speed')->unique()->all())->toBe([SpeechSpeed::Slow]);
    });

    it('rejects an unknown speed', function (): void {
        $this->artisan('speech:generate', ['language' => 'es', '--speed' => 'fast'])
            ->expectsOutputToContain('The speed must be normal or slow.')
            ->assertFailed();
    });

    it('picks a voice by name', function (): void {
        $this->artisan('speech:generate', ['language' => 'es', '--voice' => 'm1'])
            ->expectsOutputToContain('es (supertonic-m1)')
            ->assertSuccessful();

        expect(SpeechClip::query()->where('voice_id', 'supertonic-m1')->count())->toBe(6);
    });

    it('rejects a voice the language does not have', function (): void {
        $this->artisan('speech:generate', ['language' => 'es', '--voice' => 'nobody'])
            ->expectsOutputToContain('No voice configured for [es].')
            ->assertFailed();
    });

    it('covers every configured language without an argument', function (): void {
        $this->artisan('speech:generate')
            ->expectsOutputToContain('es (supertonic-f1): generated 6 of 6')
            ->expectsOutputToContain('pt (supertonic-f1): generated 0 of 0')
            ->assertSuccessful();
    });

    it('rejects a limit that is not a positive integer', function (string $limit): void {
        $this->artisan('speech:generate', ['language' => 'es', '--limit' => $limit])
            ->expectsOutputToContain('The limit must be a positive integer.')
            ->assertFailed();

        expect(FakeSpeechEngine::$batches)->toBe([]);
    })->with(['0', '-3', 'abc', '2.5']);

    it('leaves a good file in place when the write fails and keeps no temporary file', function (): void {
        $this->artisan('speech:generate', ['language' => 'es', '--limit' => '1'])->assertSuccessful();
        $path = primaryPath('el gato', SpeechSpeed::Normal);
        $row = SpeechClip::query()->where('speed', 'normal')->firstOrFail();

        $real = Storage::disk('public');
        $failing = Mockery::mock($real)->makePartial();
        $failing->shouldReceive('move')->andThrow(new RuntimeException('disk full'));
        Storage::set('public', $failing);
        $real->put($path, 'good');

        $this->artisan('speech:generate', ['language' => 'es', '--limit' => '1', '--force' => true])
            ->expectsOutputToContain('error: disk full')
            ->assertFailed();

        expect($real->get($path))->toBe('good')
            ->and($real->allFiles('speech'))->each->not->toEndWith('.tmp')
            ->and($row->fresh()?->bytes)->toBe($row->bytes);
    });

    it('resumes a half-finished run and clears stale temporary files', function (): void {
        $this->artisan('speech:generate', ['language' => 'es', '--limit' => '1'])->assertSuccessful();
        $stale = primaryPath('la casa', SpeechSpeed::Normal).'.tmp';
        Storage::disk('public')->put($stale, 'half');

        $this->artisan('speech:generate', ['language' => 'es'])
            ->expectsOutputToContain('generated 4 of 4 clips, 0 failed, 2 skipped')
            ->assertSuccessful();

        Storage::disk('public')->assertMissing($stale);
        expect(SpeechClip::query()->count())->toBe(6);
    });

    it('shows the first helper errors', function (): void {
        config(['speech.engines.supertonic' => SupertonicEngine::class]);
        Process::fake(['*' => Process::result(implode("\n", array_map(
            fn (int $id): string => json_encode(['id' => $id, 'error' => "model exploded {$id}"], JSON_THROW_ON_ERROR),
            range(0, 9),
        ))."\n", exitCode: 1)]);

        $this->artisan('speech:generate', ['language' => 'es', '--speed' => 'normal'])
            ->expectsOutputToContain('error: model exploded 0')
            ->expectsOutputToContain('error: model exploded 2')
            ->doesntExpectOutputToContain('model exploded 3')
            ->assertFailed();
    });

    it('reports clips the engine could not speak and fails', function (): void {
        VocabularyItem::factory()->create(['language_id' => Language::query()->where('code', 'es')->value('id'), 'term' => FakeSpeechEngine::FAILING_TEXT]);

        $this->artisan('speech:generate', ['language' => 'es'])
            ->expectsOutputToContain('generated 6 of 8 clips, 2 failed')
            ->assertFailed();

        expect(SpeechClip::query()->count())->toBe(6);
    });
});

describe('speech:sample', function (): void {
    it('writes one sample per language and speed on the local disk', function (): void {
        $this->artisan('speech:sample')->expectsOutputToContain('8 samples written.')->assertSuccessful();

        Storage::disk('local')->assertExists('speech-samples/es-supertonic-f1-normal.mp3');
        Storage::disk('local')->assertExists('speech-samples/it-supertonic-f1-slow.mp3');
        expect(SpeechClip::query()->count())->toBe(0);
    });

    it('fails when nothing could be encoded', function (): void {
        app()->bind(AudioEncoder::class, fn (): AudioEncoder => new class implements AudioEncoder
        {
            public function encode(array $wavs): array
            {
                return array_map(fn (): null => null, $wavs);
            }
        });

        $this->artisan('speech:sample')->assertFailed();
    });
});

describe('speech:index', function (): void {
    it('records clips found on disk and is idempotent', function (): void {
        Storage::disk('public')->put(primaryPath('el gato', SpeechSpeed::Normal), 'abc');
        Storage::disk('public')->put(primaryPath('el gato', SpeechSpeed::Slow), 'abcdef');
        Storage::disk('public')->put('speech/es/supertonic-f1/ff/'.str_repeat('f', 64).'.mp3', 'orphan');

        $this->artisan('speech:index')->expectsOutputToContain('Indexed 2 clips.')->assertSuccessful();
        $this->artisan('speech:index')->expectsOutputToContain('Indexed 0 clips.')->assertSuccessful();

        expect(SpeechClip::query()->count())->toBe(2)
            ->and(SpeechClip::query()->where('speed', 'slow')->value('bytes'))->toBe(6);
    });

    it('ignores empty files and stray temporary files', function (): void {
        Storage::disk('public')->put(primaryPath('el gato', SpeechSpeed::Normal), '');
        Storage::disk('public')->put(primaryPath('la casa', SpeechSpeed::Normal).'.tmp', 'half');

        $this->artisan('speech:index')->expectsOutputToContain('Indexed 0 clips.')->assertSuccessful();

        expect(SpeechClip::query()->count())->toBe(0);
    });

    it('keeps a known duration and corrects a changed size', function (): void {
        $this->artisan('speech:generate', ['language' => 'es', '--limit' => '1'])->assertSuccessful();
        Storage::disk('public')->put(primaryPath('el gato', SpeechSpeed::Normal), 'changed');

        $this->artisan('speech:index')->expectsOutputToContain('Indexed 1 clips.')->assertSuccessful();

        $clip = SpeechClip::query()->where('speed', 'normal')->firstOrFail();

        expect($clip->bytes)->toBe(7)->and($clip->duration_ms)->toBe(1500);
    });
});

describe('speech:prune', function (): void {
    beforeEach(function (): void {
        $this->artisan('speech:generate', ['language' => 'es'])->assertSuccessful();
        Storage::disk('public')->put('speech/es/supertonic-f1/ff/'.str_repeat('f', 64).'.mp3', 'orphan');
        SpeechClip::query()->create(['language' => 'es', 'voice_id' => 'supertonic-f1', 'speed' => 'normal', 'hash' => str_repeat('f', 64), 'bytes' => 6]);
    });

    it('only lists orphans by default', function (): void {
        $this->artisan('speech:prune')->expectsOutputToContain('Would remove 1 files and 1 rows.')->assertSuccessful();

        expect(Storage::disk('public')->allFiles('speech'))->toHaveCount(7)
            ->and(SpeechClip::query()->count())->toBe(7);
    });

    it('removes only the orphans when forced', function (): void {
        $this->artisan('speech:prune', ['--force' => true])->expectsOutputToContain('Removed 1 files and 1 rows.')->assertSuccessful();

        expect(Storage::disk('public')->allFiles('speech'))->toHaveCount(6)
            ->and(SpeechClip::query()->count())->toBe(6);
        Storage::disk('public')->assertExists(primaryPath('el gato', SpeechSpeed::Normal));
    });

    it('ignores temporary files', function (): void {
        Storage::disk('public')->put('speech/es/supertonic-f1/ab/half.mp3.tmp', 'half');

        $this->artisan('speech:prune', ['--force' => true])->expectsOutputToContain('Removed 1 files')->assertSuccessful();

        Storage::disk('public')->assertExists('speech/es/supertonic-f1/ab/half.mp3.tmp');
    });

    it('refuses to delete anything when the corpus is empty', function (): void {
        VocabularyItem::query()->delete();

        $this->artisan('speech:prune', ['--force' => true, '--allow-mass-delete' => true])
            ->expectsOutputToContain('The current corpus has no clips')
            ->assertFailed();

        expect(Storage::disk('public')->allFiles('speech'))->toHaveCount(7)
            ->and(SpeechClip::query()->count())->toBe(7);
    });

    it('refuses to delete more than half of the files unless allowed', function (): void {
        foreach (range(1, 7) as $number) {
            Storage::disk('public')->put("speech/es/supertonic-f1/aa/orphan{$number}.mp3", 'x');
        }

        $this->artisan('speech:prune', ['--force' => true])
            ->expectsOutputToContain('pass --allow-mass-delete')
            ->assertFailed();

        expect(Storage::disk('public')->allFiles('speech'))->toHaveCount(14);

        $this->artisan('speech:prune', ['--force' => true, '--allow-mass-delete' => true])->assertSuccessful();

        expect(Storage::disk('public')->allFiles('speech'))->toHaveCount(6);
    });

    it('treats clips of every configured voice as current', function (): void {
        $this->artisan('speech:generate', ['language' => 'es', '--voice' => 'M1'])->assertSuccessful();

        $this->artisan('speech:prune', ['--force' => true])->expectsOutputToContain('Removed 1 files and 1 rows.')->assertSuccessful();

        expect(SpeechClip::query()->where('voice_id', 'supertonic-m1')->count())->toBe(6);
    });
});

describe('speech:verify', function (): void {
    it('fails for a language that requires audio and has gaps', function (): void {
        $this->artisan('speech:verify', ['language' => 'es'])
            ->expectsOutputToContain('normal: el gato')
            ->expectsOutputToContain('6 clips without audio.')
            ->assertFailed();
    });

    it('passes once every clip has audio', function (): void {
        $this->artisan('speech:generate', ['language' => 'es'])->assertSuccessful();

        $this->artisan('speech:verify', ['language' => 'es'])->expectsOutputToContain('0 clips without audio.')->assertSuccessful();
    });

    it('lists gaps without failing for a language that does not require audio', function (): void {
        VocabularyItem::factory()->create(['language_id' => Language::factory()->create(['code' => 'pt'])->id, 'term' => 'o gato']);

        $this->artisan('speech:verify', ['language' => 'pt'])->expectsOutputToContain('2 clips without audio.')->assertSuccessful();
    });

    it('truncates a long list', function (): void {
        $es = Language::query()->where('code', 'es')->firstOrFail();

        foreach (range(1, 30) as $number) {
            VocabularyItem::factory()->create(['language_id' => $es->id, 'term' => "palabra {$number}"]);
        }

        $this->artisan('speech:verify', ['language' => 'es'])->expectsOutputToContain('... and 16 more')->assertFailed();
    });

    it('fails for a language without a configured voice', function (): void {
        $this->artisan('speech:verify', ['language' => 'xx'])->expectsOutputToContain('No voice configured for [xx].')->assertFailed();
    });
});
