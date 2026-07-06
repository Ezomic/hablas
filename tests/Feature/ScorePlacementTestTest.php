<?php

use App\Actions\ScorePlacementTest;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestItem;
use App\Models\User;
use App\Models\UserSkillLevel;

beforeEach(function () {
    $this->language = Language::factory()->create();
    $this->user = User::factory()->create();
});

it('places a skill at A2 when almost all of its items are answered correctly', function () {
    $items = PlacementTestItem::factory()->count(5)->create([
        'language_id' => $this->language->id,
        'skill' => Skill::Reading,
    ]);

    $responses = $items->take(4)->mapWithKeys(fn (PlacementTestItem $item) => [$item->id => $item->correct_answer])->all();
    $responses[$items->last()->id] = 'a wrong answer';

    $attempt = (new ScorePlacementTest)->handle($this->user, $this->language, $responses);

    expect($attempt->resulting_skill_levels['reading'])->toBe(CefrLevel::A2->value);
});

it('keeps a skill at A1 when fewer than 80% of its items are answered correctly', function () {
    $items = PlacementTestItem::factory()->count(5)->create([
        'language_id' => $this->language->id,
        'skill' => Skill::Listening,
    ]);

    $responses = $items->take(2)->mapWithKeys(fn (PlacementTestItem $item) => [$item->id => $item->correct_answer])->all();

    $attempt = (new ScorePlacementTest)->handle($this->user, $this->language, $responses);

    expect($attempt->resulting_skill_levels['listening'])->toBe(CefrLevel::A1->value);
});

it('treats missing responses as incorrect', function () {
    PlacementTestItem::factory()->count(5)->create([
        'language_id' => $this->language->id,
        'skill' => Skill::Writing,
    ]);

    $attempt = (new ScorePlacementTest)->handle($this->user, $this->language, []);

    expect($attempt->resulting_skill_levels['writing'])->toBe(CefrLevel::A1->value);
});

it('scores each skill independently', function () {
    $readingItems = PlacementTestItem::factory()->count(5)->create([
        'language_id' => $this->language->id,
        'skill' => Skill::Reading,
    ]);
    $speakingItems = PlacementTestItem::factory()->count(5)->create([
        'language_id' => $this->language->id,
        'skill' => Skill::Speaking,
    ]);

    $responses = $readingItems->mapWithKeys(fn (PlacementTestItem $item) => [$item->id => $item->correct_answer])->all();
    // Leave all speaking items unanswered.

    $attempt = (new ScorePlacementTest)->handle($this->user, $this->language, $responses);

    expect($attempt->resulting_skill_levels['reading'])->toBe(CefrLevel::A2->value)
        ->and($attempt->resulting_skill_levels['speaking'])->toBe(CefrLevel::A1->value);
});

it('writes the resulting levels into user_skill_levels', function () {
    $items = PlacementTestItem::factory()->count(5)->create([
        'language_id' => $this->language->id,
        'skill' => Skill::Reading,
    ]);
    $responses = $items->mapWithKeys(fn (PlacementTestItem $item) => [$item->id => $item->correct_answer])->all();

    (new ScorePlacementTest)->handle($this->user, $this->language, $responses);

    $skillLevel = UserSkillLevel::query()
        ->where('user_id', $this->user->id)
        ->where('language_id', $this->language->id)
        ->where('skill', Skill::Reading)
        ->sole();

    expect($skillLevel->cefr_level)->toBe(CefrLevel::A2);
});

it('marks the attempt as completed', function () {
    PlacementTestItem::factory()->count(3)->create(['language_id' => $this->language->id]);

    $attempt = (new ScorePlacementTest)->handle($this->user, $this->language, []);

    expect($attempt->completed_at)->not->toBeNull();
});
