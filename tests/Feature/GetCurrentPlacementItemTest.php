<?php

use App\Actions\Placement\GetCurrentPlacementItem;
use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestItem;
use App\Models\PlacementTestResponse;

it('returns the first skills item for a fresh attempt', function () {
    $language = Language::factory()->create();
    $attempt = PlacementTestAttempt::factory()->create(['language_id' => $language->id]);
    $readingItem = PlacementTestItem::factory()->tier(CefrSubLevel::A1_3)->create(['language_id' => $language->id, 'skill' => Skill::Reading]);
    PlacementTestItem::factory()->tier(CefrSubLevel::A1_3)->create(['language_id' => $language->id, 'skill' => Skill::Listening]);

    $item = (new GetCurrentPlacementItem)->handle($attempt);

    expect($item?->id)->toBe($readingItem->id);
});

it('moves to the next skill once the current skill has settled', function () {
    $language = Language::factory()->create();
    $attempt = PlacementTestAttempt::factory()->create(['language_id' => $language->id]);
    $listeningItem = PlacementTestItem::factory()->tier(CefrSubLevel::A1_1)->create(['language_id' => $language->id, 'skill' => Skill::Listening]);

    // Settle Reading via the 8-item cap (no Reading items exist, so each
    // "answer" targets a different skill's item — the cap only cares about
    // response *count* for that skill, not which item was answered).
    for ($i = 0; $i < 8; $i++) {
        PlacementTestResponse::factory()->create([
            'attempt_id' => $attempt->id,
            'skill' => Skill::Reading,
            'is_correct' => true,
        ]);
    }

    $item = (new GetCurrentPlacementItem)->handle($attempt);

    expect($item?->id)->toBe($listeningItem->id);
});

it('returns null once every skill has settled', function () {
    $language = Language::factory()->create();
    $attempt = PlacementTestAttempt::factory()->create(['language_id' => $language->id]);

    foreach (Skill::cases() as $skill) {
        for ($i = 0; $i < 8; $i++) {
            PlacementTestResponse::factory()->create([
                'attempt_id' => $attempt->id,
                'skill' => $skill,
                'is_correct' => true,
            ]);
        }
    }

    $item = (new GetCurrentPlacementItem)->handle($attempt);

    expect($item)->toBeNull();
});
