<?php

use App\Actions\SelectNextUnit;
use App\Enums\CefrLevel;
use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\Skill;
use App\Enums\UnitProgressStatus;
use App\Models\Language;
use App\Models\Unit;
use App\Models\UnitInterestTag;
use App\Models\User;
use App\Models\UserInterestPreference;
use App\Models\UserSkillLevel;
use App\Models\UserUnitProgress;

beforeEach(function () {
    $this->language = Language::factory()->create();
    $this->user = User::factory()->create();
});

it('only selects units at or below the users blended CEFR level', function () {
    UserSkillLevel::factory()->create([
        'user_id' => $this->user->id,
        'language_id' => $this->language->id,
        'skill' => Skill::Reading,
        'cefr_level' => CefrLevel::A1,
    ]);
    $tooHard = Unit::factory()->create(['language_id' => $this->language->id, 'cefr_level' => CefrLevel::B1, 'sort_order' => 1]);
    $justRight = Unit::factory()->create(['language_id' => $this->language->id, 'cefr_level' => CefrLevel::A1, 'sort_order' => 2]);

    $selected = (new SelectNextUnit)->handle($this->user, $this->language);

    expect($selected->id)->toBe($justRight->id)
        ->and($selected->id)->not->toBe($tooHard->id);
});

it('falls back to a lower level when nothing exists at the exact blended level', function () {
    UserSkillLevel::factory()->create([
        'user_id' => $this->user->id,
        'language_id' => $this->language->id,
        'skill' => Skill::Reading,
        'cefr_level' => CefrLevel::A2,
    ]);
    $onlyOption = Unit::factory()->create(['language_id' => $this->language->id, 'cefr_level' => CefrLevel::A1, 'sort_order' => 1]);

    $selected = (new SelectNextUnit)->handle($this->user, $this->language);

    expect($selected->id)->toBe($onlyOption->id);
});

it('excludes units the user has already completed', function () {
    $completed = Unit::factory()->create(['language_id' => $this->language->id, 'cefr_level' => CefrLevel::A1, 'sort_order' => 1]);
    $remaining = Unit::factory()->create(['language_id' => $this->language->id, 'cefr_level' => CefrLevel::A1, 'sort_order' => 2]);
    UserUnitProgress::factory()->create([
        'user_id' => $this->user->id,
        'unit_id' => $completed->id,
        'status' => UnitProgressStatus::Completed,
    ]);

    $selected = (new SelectNextUnit)->handle($this->user, $this->language);

    expect($selected->id)->toBe($remaining->id);
});

it('prioritizes travel-tagged units over everyday-social and professional ones', function () {
    $everydaySocial = Unit::factory()->create([
        'language_id' => $this->language->id,
        'cefr_level' => CefrLevel::A1,
        'context_tag' => ContextTag::EverydaySocial,
        'sort_order' => 1,
    ]);
    $travel = Unit::factory()->create([
        'language_id' => $this->language->id,
        'cefr_level' => CefrLevel::A1,
        'context_tag' => ContextTag::Travel,
        'sort_order' => 2,
    ]);

    $selected = (new SelectNextUnit)->handle($this->user, $this->language);

    expect($selected->id)->toBe($travel->id);
});

it('favors a unit whose primary skill is under-represented in recent completions', function () {
    $readingHeavy = collect(range(1, 3))->map(fn (int $i) => Unit::factory()->create([
        'language_id' => $this->language->id,
        'cefr_level' => CefrLevel::A1,
        'context_tag' => ContextTag::Travel,
        'primary_skill' => Skill::Reading,
        'sort_order' => $i,
    ]));
    $readingHeavy->each(fn (Unit $unit) => UserUnitProgress::factory()->create([
        'user_id' => $this->user->id,
        'unit_id' => $unit->id,
        'status' => UnitProgressStatus::Completed,
        'completed_at' => now(),
    ]));

    $nextReading = Unit::factory()->create([
        'language_id' => $this->language->id,
        'cefr_level' => CefrLevel::A1,
        'context_tag' => ContextTag::Travel,
        'primary_skill' => Skill::Reading,
        'sort_order' => 10,
    ]);
    $nextSpeaking = Unit::factory()->create([
        'language_id' => $this->language->id,
        'cefr_level' => CefrLevel::A1,
        'context_tag' => ContextTag::Travel,
        'primary_skill' => Skill::Speaking,
        'sort_order' => 11,
    ]);

    $selected = (new SelectNextUnit)->handle($this->user, $this->language);

    expect($selected->id)->toBe($nextSpeaking->id)
        ->and($selected->id)->not->toBe($nextReading->id);
});

it('only weighs the rotation window, not the users entire history', function () {
    // 3 old speaking completions, outside a window of 10, should not count against speaking.
    collect(range(1, 3))->each(function (int $i) {
        $unit = Unit::factory()->create([
            'language_id' => $this->language->id,
            'cefr_level' => CefrLevel::A1,
            'context_tag' => ContextTag::Travel,
            'primary_skill' => Skill::Speaking,
            'sort_order' => $i,
        ]);
        UserUnitProgress::factory()->create([
            'user_id' => $this->user->id,
            'unit_id' => $unit->id,
            'status' => UnitProgressStatus::Completed,
            'completed_at' => now()->subDays(100),
        ]);
    });

    // 10 recent reading completions fill the rotation window.
    collect(range(4, 13))->each(function (int $i) {
        $unit = Unit::factory()->create([
            'language_id' => $this->language->id,
            'cefr_level' => CefrLevel::A1,
            'context_tag' => ContextTag::Travel,
            'primary_skill' => Skill::Reading,
            'sort_order' => $i,
        ]);
        UserUnitProgress::factory()->create([
            'user_id' => $this->user->id,
            'unit_id' => $unit->id,
            'status' => UnitProgressStatus::Completed,
            'completed_at' => now()->subDay(),
        ]);
    });

    $nextReading = Unit::factory()->create(['language_id' => $this->language->id, 'cefr_level' => CefrLevel::A1, 'context_tag' => ContextTag::Travel, 'primary_skill' => Skill::Reading, 'sort_order' => 20]);
    $nextSpeaking = Unit::factory()->create(['language_id' => $this->language->id, 'cefr_level' => CefrLevel::A1, 'context_tag' => ContextTag::Travel, 'primary_skill' => Skill::Speaking, 'sort_order' => 21]);

    $selected = (new SelectNextUnit)->handle($this->user, $this->language);

    expect($selected->id)->toBe($nextSpeaking->id);
});

it('returns null when there are no eligible units left', function () {
    $selected = (new SelectNextUnit)->handle($this->user, $this->language);

    expect($selected)->toBeNull();
});

it('never selects a unit from a different language deck', function () {
    $otherLanguage = Language::factory()->create();
    Unit::factory()->create(['language_id' => $otherLanguage->id, 'cefr_level' => CefrLevel::A1, 'sort_order' => 1]);
    $ownUnit = Unit::factory()->create(['language_id' => $this->language->id, 'cefr_level' => CefrLevel::A1, 'sort_order' => 1]);

    $selected = (new SelectNextUnit)->handle($this->user, $this->language);

    expect($selected->id)->toBe($ownUnit->id);
});

it('prefers a unit whose interest tags overlap the users preferences among skill-tied candidates', function () {
    UserInterestPreference::factory()->create([
        'user_id' => $this->user->id,
        'interest_tag' => InterestTag::Cooking,
    ]);

    $noMatch = Unit::factory()->create([
        'language_id' => $this->language->id,
        'cefr_level' => CefrLevel::A1,
        'context_tag' => ContextTag::Travel,
        'primary_skill' => Skill::Reading,
        'sort_order' => 1,
    ]);
    $match = Unit::factory()->create([
        'language_id' => $this->language->id,
        'cefr_level' => CefrLevel::A1,
        'context_tag' => ContextTag::Travel,
        'primary_skill' => Skill::Reading,
        'sort_order' => 2,
    ]);
    UnitInterestTag::factory()->create([
        'unit_id' => $match->id,
        'interest_tag' => InterestTag::Cooking,
    ]);

    $selected = (new SelectNextUnit)->handle($this->user, $this->language);

    expect($selected->id)->toBe($match->id)
        ->and($selected->id)->not->toBe($noMatch->id);
});

it('ignores interest tags entirely when the user has no preferences set', function () {
    $unit = Unit::factory()->create([
        'language_id' => $this->language->id,
        'cefr_level' => CefrLevel::A1,
        'context_tag' => ContextTag::Travel,
        'primary_skill' => Skill::Reading,
        'sort_order' => 1,
    ]);

    $selected = (new SelectNextUnit)->handle($this->user, $this->language);

    expect($selected->id)->toBe($unit->id);
});
