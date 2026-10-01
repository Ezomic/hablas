<?php

declare(strict_types=1);

use App\Actions\Progress\GetMostFrequentErrorTags;
use App\Enums\ErrorTagCategory;
use App\Models\Language;
use App\Models\Lesson;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Models\Unit;
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

it('counts lesson answers next to review ratings', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $card = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id]);
    SrsReview::factory()->count(1)->create(['user_id' => $user->id, 'srs_card_id' => $card->id, 'error_tag_category' => ErrorTagCategory::WrongGender]);

    $unit = Unit::factory()->create(['language_id' => $language->id]);
    $lesson = Lesson::factory()->create(['unit_id' => $unit->id]);
    $run = LessonRun::factory()->create(['user_id' => $user->id, 'lesson_id' => $lesson->id]);
    foreach ([1, 2] as $attempt) {
        LessonAnswer::factory()->create([
            'lesson_run_id' => $run->id,
            'lesson_exercise_id' => LessonExercise::factory()->create(['lesson_id' => $lesson->id])->id,
            'attempt' => $attempt,
            'is_correct' => false,
            'error_tag_category' => ErrorTagCategory::WrongGender,
        ]);
    }
    LessonAnswer::factory()->create([
        'lesson_run_id' => $run->id,
        'lesson_exercise_id' => LessonExercise::factory()->create(['lesson_id' => $lesson->id])->id,
        'error_tag_category' => ErrorTagCategory::SerEstarConfusion,
    ]);
    LessonAnswer::factory()->create([
        'lesson_run_id' => $run->id,
        'lesson_exercise_id' => LessonExercise::factory()->create(['lesson_id' => $lesson->id])->id,
        'error_tag_category' => null,
    ]);

    $result = (new GetMostFrequentErrorTags)->handle($user, $language);

    expect($result->pluck('error_tag_category')->all())->toBe([ErrorTagCategory::WrongGender, ErrorTagCategory::SerEstarConfusion])
        ->and($result->pluck('count')->all())->toBe([3, 1]);
});

it('does not count another language\'s or another learner\'s lesson tags', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $otherLanguage = Language::factory()->create();

    foreach ([[$user, $otherLanguage], [User::factory()->create(), $language]] as [$owner, $unitLanguage]) {
        $lesson = Lesson::factory()->create(['unit_id' => Unit::factory()->create(['language_id' => $unitLanguage->id])->id]);
        LessonAnswer::factory()->create([
            'lesson_run_id' => LessonRun::factory()->create(['user_id' => $owner->id, 'lesson_id' => $lesson->id])->id,
            'lesson_exercise_id' => LessonExercise::factory()->create(['lesson_id' => $lesson->id])->id,
            'error_tag_category' => ErrorTagCategory::WrongGender,
        ]);
    }

    expect((new GetMostFrequentErrorTags)->handle($user, $language))->toBeEmpty();
});
