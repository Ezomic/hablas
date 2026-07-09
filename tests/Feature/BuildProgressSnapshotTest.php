<?php

use App\Actions\Progress\BuildProgressSnapshot;
use App\Enums\CefrLevel;
use App\Enums\ErrorTagCategory;
use App\Enums\Skill;
use App\Enums\UnitProgressStatus;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Models\Streak;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserSkillLevel;
use App\Models\UserUnitProgress;

it('assembles the full snapshot shape', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create(['code' => 'es', 'name' => 'Spanish']);

    foreach (Skill::cases() as $skill) {
        UserSkillLevel::factory()->create([
            'user_id' => $user->id,
            'language_id' => $language->id,
            'skill' => $skill,
            'cefr_level' => CefrLevel::A2,
        ]);
    }

    Streak::factory()->create([
        'user_id' => $user->id,
        'current_length' => 5,
        'longest_length' => 12,
        'last_activity_date' => now(),
    ]);

    $units = Unit::factory()->count(4)->create(['language_id' => $language->id]);
    UserUnitProgress::factory()->create(['user_id' => $user->id, 'unit_id' => $units[0]->id, 'status' => UnitProgressStatus::Completed]);
    UserUnitProgress::factory()->create(['user_id' => $user->id, 'unit_id' => $units[1]->id, 'status' => UnitProgressStatus::Completed]);
    UserUnitProgress::factory()->create(['user_id' => $user->id, 'unit_id' => $units[2]->id, 'status' => UnitProgressStatus::InProgress]);

    $card = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id]);
    SrsReview::factory()->count(3)->create(['user_id' => $user->id, 'srs_card_id' => $card->id, 'error_tag_category' => ErrorTagCategory::SerEstarConfusion]);

    $snapshot = (new BuildProgressSnapshot)->handle($user, $language);

    expect($snapshot['language'])->toBe(['code' => 'es', 'name' => 'Spanish'])
        ->and($snapshot['blendedLevel'])->toBe('A2')
        ->and($snapshot['skillLevels'])->toEqualCanonicalizing([
            'reading' => 'A2',
            'listening' => 'A2',
            'speaking' => 'A2',
            'writing' => 'A2',
        ])
        ->and($snapshot['streak'])->toBe(['currentLength' => 5, 'longestLength' => 12])
        ->and($snapshot['unitCompletionPercentage'])->toBe(50)
        ->and($snapshot['topErrorTags'])->toBe([
            ['category' => 'ser_estar_confusion', 'count' => 3],
        ]);
});

it('scopes unit completion percentage to the given language only', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $otherLanguage = Language::factory()->create();

    $units = Unit::factory()->count(2)->create(['language_id' => $language->id]);
    UserUnitProgress::factory()->create(['user_id' => $user->id, 'unit_id' => $units[0]->id, 'status' => UnitProgressStatus::Completed]);

    $otherUnit = Unit::factory()->create(['language_id' => $otherLanguage->id]);
    UserUnitProgress::factory()->create(['user_id' => $user->id, 'unit_id' => $otherUnit->id, 'status' => UnitProgressStatus::Completed]);

    $snapshot = (new BuildProgressSnapshot)->handle($user, $language);

    expect($snapshot['unitCompletionPercentage'])->toBe(50);
});

it('returns a zero percentage without a division error when the language has no units', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    $snapshot = (new BuildProgressSnapshot)->handle($user, $language);

    expect($snapshot['unitCompletionPercentage'])->toBe(0)
        ->and($snapshot['blendedLevel'])->toBeNull()
        ->and($snapshot['topErrorTags'])->toBe([]);
});
