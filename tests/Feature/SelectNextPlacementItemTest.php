<?php

use App\Actions\Placement\DeriveCurrentPlacementTier;
use App\Actions\Placement\SelectNextPlacementItem;
use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestItem;
use App\Models\PlacementTestResponse;

it('returns an unanswered item at the derived starting tier', function () {
    $language = Language::factory()->create();
    $attempt = PlacementTestAttempt::factory()->create(['language_id' => $language->id]);
    $item = PlacementTestItem::factory()->tier(CefrSubLevel::A1_3)->create(['language_id' => $language->id, 'skill' => Skill::Reading]);

    $selected = (new SelectNextPlacementItem)->handle($attempt, Skill::Reading);

    expect($selected?->id)->toBe($item->id);
});

it('excludes already-answered items', function () {
    $language = Language::factory()->create();
    $attempt = PlacementTestAttempt::factory()->create(['language_id' => $language->id]);
    $answered = PlacementTestItem::factory()->tier(CefrSubLevel::A1_3)->create(['language_id' => $language->id, 'skill' => Skill::Reading]);
    $fresh = PlacementTestItem::factory()->tier(CefrSubLevel::A1_3)->create(['language_id' => $language->id, 'skill' => Skill::Reading]);
    PlacementTestResponse::factory()->create([
        'attempt_id' => $attempt->id,
        'item_id' => $answered->id,
        'skill' => Skill::Reading,
        'is_correct' => false,
    ]);

    $selected = (new SelectNextPlacementItem)->handle($attempt, Skill::Reading);

    expect($selected?->id)->toBe($fresh->id);
});

it('returns null after the max items per skill have been answered', function () {
    $language = Language::factory()->create();
    $attempt = PlacementTestAttempt::factory()->create(['language_id' => $language->id]);

    for ($i = 0; $i < 8; $i++) {
        PlacementTestResponse::factory()->create([
            'attempt_id' => $attempt->id,
            'skill' => Skill::Reading,
            'is_correct' => $i % 2 === 0,
        ]);
    }

    $selected = (new SelectNextPlacementItem)->handle($attempt, Skill::Reading);

    expect($selected)->toBeNull();
});

it('returns null after 3 consecutive identical tiers', function () {
    $language = Language::factory()->create();
    $attempt = PlacementTestAttempt::factory()->create(['language_id' => $language->id]);

    // Correct, then incorrect, incorrect, incorrect: A1.3 -> A2.1 -> A1.3 -> A1.2 -> A1.1.
    // Not settled yet (tiers keep moving). Follow with three incorrect
    // answers that clamp at the floor (A1.1, A1.1, A1.1) to settle.
    foreach ([true, false, false, false, false, false] as $isCorrect) {
        PlacementTestResponse::factory()->create([
            'attempt_id' => $attempt->id,
            'skill' => Skill::Reading,
            'is_correct' => $isCorrect,
        ]);
    }

    $selected = (new SelectNextPlacementItem)->handle($attempt, Skill::Reading);

    expect($selected)->toBeNull();
});

it('falls back to a neighboring tier when the exact tier has no unanswered items', function () {
    $language = Language::factory()->create();
    $attempt = PlacementTestAttempt::factory()->create(['language_id' => $language->id]);
    $neighbor = PlacementTestItem::factory()->tier(CefrSubLevel::A2_1)->create(['language_id' => $language->id, 'skill' => Skill::Reading]);
    // No items exist at A1.3 (the derived starting tier) at all.

    $selected = (new SelectNextPlacementItem)->handle($attempt, Skill::Reading);

    expect($selected?->id)->toBe($neighbor->id);
});

it('never returns an item from a different language', function () {
    $language = Language::factory()->create();
    $otherLanguage = Language::factory()->create();
    $attempt = PlacementTestAttempt::factory()->create(['language_id' => $language->id]);
    PlacementTestItem::factory()->tier(CefrSubLevel::A1_3)->create(['language_id' => $otherLanguage->id, 'skill' => Skill::Reading]);

    $selected = (new SelectNextPlacementItem)->handle($attempt, Skill::Reading);

    expect($selected)->toBeNull();
});

it('never returns an item from a different skill', function () {
    $language = Language::factory()->create();
    $attempt = PlacementTestAttempt::factory()->create(['language_id' => $language->id]);
    PlacementTestItem::factory()->tier(CefrSubLevel::A1_3)->create(['language_id' => $language->id, 'skill' => Skill::Listening]);

    $selected = (new SelectNextPlacementItem)->handle($attempt, Skill::Reading);

    expect($selected)->toBeNull();
});

it('selects an item at exactly the tier DeriveCurrentPlacementTier independently derives', function () {
    $language = Language::factory()->create();
    $attempt = PlacementTestAttempt::factory()->create(['language_id' => $language->id]);
    // Two correct answers: A1.3 -> A2.1 -> A2.2.
    PlacementTestResponse::factory()->count(2)->create([
        'attempt_id' => $attempt->id,
        'skill' => Skill::Reading,
        'is_correct' => true,
    ]);
    PlacementTestItem::factory()->tier(CefrSubLevel::A2_2)->create(['language_id' => $language->id, 'skill' => Skill::Reading]);

    $derivedTier = (new DeriveCurrentPlacementTier)->handle($attempt, Skill::Reading);
    $selected = (new SelectNextPlacementItem)->handle($attempt, Skill::Reading);

    expect($derivedTier)->toBe(CefrSubLevel::A2_2)
        ->and($selected?->cefr_sublevel_tag)->toBe($derivedTier);
});
