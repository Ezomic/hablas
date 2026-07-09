<?php

use App\Actions\Progress\GetMostFrequentErrorTags;
use App\Enums\ErrorTagCategory;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Models\User;

it('ranks error tags by frequency descending', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $card = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id]);

    SrsReview::factory()->count(3)->create(['user_id' => $user->id, 'srs_card_id' => $card->id, 'error_tag_category' => ErrorTagCategory::SerEstarConfusion]);
    SrsReview::factory()->count(1)->create(['user_id' => $user->id, 'srs_card_id' => $card->id, 'error_tag_category' => ErrorTagCategory::WrongGender]);
    SrsReview::factory()->count(2)->create(['user_id' => $user->id, 'srs_card_id' => $card->id, 'error_tag_category' => ErrorTagCategory::FalseFriend]);

    $result = (new GetMostFrequentErrorTags)->handle($user, $language);

    expect($result->pluck('error_tag_category')->all())->toBe([
        ErrorTagCategory::SerEstarConfusion,
        ErrorTagCategory::FalseFriend,
        ErrorTagCategory::WrongGender,
    ])->and($result->pluck('count')->all())->toBe([3, 2, 1]);
});

it('excludes reviews without an error tag', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $card = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id]);

    SrsReview::factory()->create(['user_id' => $user->id, 'srs_card_id' => $card->id, 'error_tag_category' => null]);
    SrsReview::factory()->create(['user_id' => $user->id, 'srs_card_id' => $card->id, 'error_tag_category' => ErrorTagCategory::WrongTense]);

    $result = (new GetMostFrequentErrorTags)->handle($user, $language);

    expect($result)->toHaveCount(1)
        ->and($result->first()['error_tag_category'])->toBe(ErrorTagCategory::WrongTense);
});

it('excludes reviews for a different language', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $otherLanguage = Language::factory()->create();
    $card = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id]);
    $otherCard = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $otherLanguage->id]);

    SrsReview::factory()->create(['user_id' => $user->id, 'srs_card_id' => $card->id, 'error_tag_category' => ErrorTagCategory::PortunolSlip]);
    SrsReview::factory()->count(5)->create(['user_id' => $user->id, 'srs_card_id' => $otherCard->id, 'error_tag_category' => ErrorTagCategory::PortunolSlip]);

    $result = (new GetMostFrequentErrorTags)->handle($user, $language);

    expect($result)->toHaveCount(1)
        ->and($result->first()['count'])->toBe(1);
});

it('respects the limit', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $card = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id]);

    foreach (ErrorTagCategory::cases() as $category) {
        SrsReview::factory()->create(['user_id' => $user->id, 'srs_card_id' => $card->id, 'error_tag_category' => $category]);
    }

    $result = (new GetMostFrequentErrorTags)->handle($user, $language, limit: 2);

    expect($result)->toHaveCount(2);
});
