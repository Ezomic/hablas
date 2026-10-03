<?php

declare(strict_types=1);

use App\Enums\SpeechSpeed;
use App\Models\Language;
use App\Models\ListeningExercise;
use App\Models\SpeechClip;
use App\Models\Unit;
use App\Models\User;
use App\Speech\SpeechKey;
use App\Speech\SpeechVoices;
use Database\Seeders\ContentSeeder;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * The framework only raises CommandFinished outside unit tests, so these
 * dispatch it the way `php artisan migrate` does on a release.
 *
 * @param  array<string, mixed>  $options
 */
function finishedCommand(string $command, array $options = [], int $exitCode = 0): CommandFinished
{
    $input = new ArrayInput($options, Artisan::all()[$command]->getDefinition());

    return new CommandFinished($command, $input, new BufferedOutput, $exitCode);
}

it('seeds the course content after migrate succeeds, without creating users', function () {
    event(finishedCommand('migrate', ['--force' => true]));

    expect(DB::table('languages')->count())->toBeGreaterThan(0)
        ->and(DB::table('units')->count())->toBeGreaterThan(0)
        ->and(DB::table('cefr_can_do_statements')->count())->toBeGreaterThan(0)
        ->and(User::query()->count())->toBe(0);
});

it('brings changed content up to date on a release with no pending migrations', function () {
    $this->seed(ContentSeeder::class);
    $unit = Unit::query()->orderBy('id')->firstOrFail();
    $seededTitle = $unit->title;
    $unit->update(['title' => 'Stale title']);

    event(finishedCommand('migrate', ['--force' => true]));

    expect($unit->refresh()->title)->toBe($seededTitle);
});

it('leaves the content alone when migrate failed', function () {
    event(finishedCommand('migrate', ['--force' => true], exitCode: 1));

    expect(DB::table('units')->count())->toBe(0);
});

it('leaves the content alone on a migrate dry run', function () {
    event(finishedCommand('migrate', ['--pretend' => true]));

    expect(DB::table('units')->count())->toBe(0);
});

it('leaves the content alone after other commands, such as the test suite\'s migrate:fresh', function () {
    event(finishedCommand('migrate:fresh'));

    expect(DB::table('units')->count())->toBe(0);
});

it('indexes the clips on disk after seeding, so a freshly seeded transcript is found', function () {
    Storage::fake('local');
    $this->seed(ContentSeeder::class);

    $transcript = ListeningExercise::query()->firstOrFail()->transcript;
    $language = Language::query()->findOrFail(ListeningExercise::query()->firstOrFail()->language_id)->code;
    $voice = app(SpeechVoices::class)->primary($language);
    $hash = app(SpeechKey::class)->make($language, $voice->id, SpeechSpeed::Normal, $transcript);
    Storage::disk('local')->put(SpeechClip::pathFor($language, $voice->id, $hash), 'mp3');

    DB::table('listening_exercises')->delete();
    DB::table('shadowing_exercises')->delete();
    DB::table('vocabulary_items')->delete();
    DB::table('units')->delete();

    $event = finishedCommand('migrate', ['--force' => true]);
    event($event);

    expect(SpeechClip::query()->where('hash', $hash)->exists())->toBeTrue()
        ->and($event->output->fetch())->toContain('Indexed ');
});

it('leaves the clip index alone when migrate failed or ran as a dry run', function () {
    Storage::fake('local');

    event(finishedCommand('migrate', ['--force' => true], exitCode: 1));
    event(finishedCommand('migrate', ['--pretend' => true]));

    expect(SpeechClip::query()->count())->toBe(0);
});

it('reports an indexing failure without failing the migrate', function () {
    config(['speech.disk' => 'missing-disk']);

    $event = finishedCommand('migrate', ['--force' => true]);
    event($event);

    expect($event->output->fetch())->toContain('Speech clips were not indexed')
        ->and(DB::table('units')->count())->toBeGreaterThan(0);
});
