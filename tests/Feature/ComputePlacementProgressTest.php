<?php

use App\Actions\Placement\ComputePlacementProgress;
use App\Actions\Placement\GetCurrentPlacementItem;
use App\Actions\Placement\RecordPlacementResponse;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\PlacementTestSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(PlacementTestSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->attempt = PlacementTestAttempt::factory()->create([
        'language_id' => $this->spanish->id,
        'started_at' => now(),
    ]);
});

it('reports 0% for a fresh attempt with no responses', function () {
    expect((new ComputePlacementProgress)->handle($this->attempt))->toBe(0);
});

it('reports 100% once every skill staircase has settled', function () {
    walkTheStaircase($this->attempt);

    expect((new ComputePlacementProgress)->handle($this->attempt))->toBe(100);
});

it('only ever moves forward as more questions are answered', function () {
    $previous = 0;
    $guard = 0;

    while (($item = (new GetCurrentPlacementItem)->handle($this->attempt)) !== null && $guard < 50) {
        (new RecordPlacementResponse)->handle($this->attempt, $item, $item->correct_answer);

        $current = (new ComputePlacementProgress)->handle($this->attempt);
        expect($current)->toBeGreaterThanOrEqual($previous);

        $previous = $current;
        $guard++;
    }

    expect($previous)->toBe(100);
});

function walkTheStaircase(PlacementTestAttempt $attempt): void
{
    $guard = 0;

    while (($item = (new GetCurrentPlacementItem)->handle($attempt)) !== null && $guard < 50) {
        (new RecordPlacementResponse)->handle($attempt, $item, $item->correct_answer);
        $guard++;
    }
}
