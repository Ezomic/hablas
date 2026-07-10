<?php

use App\Actions\Placement\RecordPlacementResponse;
use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestItem;
use App\Models\PlacementTestResponse;

it('records a correct response', function () {
    $attempt = PlacementTestAttempt::factory()->create();
    $item = PlacementTestItem::factory()->create(['skill' => Skill::Reading, 'correct_answer' => 'airport']);

    $response = (new RecordPlacementResponse)->handle($attempt, $item, 'airport');

    expect($response->is_correct)->toBeTrue()
        ->and($response->response)->toBe('airport')
        ->and($response->skill)->toBe(Skill::Reading)
        ->and($response->attempt_id)->toBe($attempt->id)
        ->and($response->item_id)->toBe($item->id)
        ->and($response->answered_at)->not->toBeNull();
});

it('records an incorrect response', function () {
    $attempt = PlacementTestAttempt::factory()->create();
    $item = PlacementTestItem::factory()->create(['correct_answer' => 'airport']);

    $response = (new RecordPlacementResponse)->handle($attempt, $item, 'hotel');

    expect($response->is_correct)->toBeFalse();
});

it('stamps tier_at_time from responses recorded before this one, not including it', function () {
    $attempt = PlacementTestAttempt::factory()->create();
    $firstItem = PlacementTestItem::factory()->create(['skill' => Skill::Reading, 'correct_answer' => 'right']);
    $secondItem = PlacementTestItem::factory()->create(['skill' => Skill::Reading, 'correct_answer' => 'right']);

    $first = (new RecordPlacementResponse)->handle($attempt, $firstItem, 'right');
    $second = (new RecordPlacementResponse)->handle($attempt, $secondItem, 'right');

    expect($first->tier_at_time)->toBe(CefrSubLevel::A1_3)
        ->and($second->tier_at_time)->toBe(CefrSubLevel::A2_1);
});

it('persists the response row', function () {
    $attempt = PlacementTestAttempt::factory()->create();
    $item = PlacementTestItem::factory()->create();

    (new RecordPlacementResponse)->handle($attempt, $item, 'anything');

    expect(PlacementTestResponse::query()->where('attempt_id', $attempt->id)->where('item_id', $item->id)->exists())->toBeTrue();
});
