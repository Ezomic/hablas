<?php

declare(strict_types=1);

use App\Models\Unit;
use App\Models\User;
use Database\Seeders\ContentSeeder;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
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
