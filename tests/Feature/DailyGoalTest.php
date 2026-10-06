<?php

declare(strict_types=1);

use App\Actions\Units\GetDayStrip;
use App\Models\Language;
use App\Models\LessonAnswer;
use App\Models\LessonRun;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Models\Streak;
use App\Models\Unit;
use App\Models\User;
use App\Models\VocabularyItem;
use App\Services\DailyGoal;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Tests\Fixtures\Lessons\LessonWorld;

function goalWorld(): array
{
    $unit = Unit::factory()->create();
    $language = Language::query()->findOrFail($unit->language_id);

    return [$language, $unit, User::factory()->create()];
}

function answerWord(User $user, VocabularyItem $item, ?CarbonImmutable $at = null): void
{
    $run = LessonRun::factory()->create(['user_id' => $user->id]);
    $answer = LessonAnswer::factory()->create(['lesson_run_id' => $run->id, 'answered_at' => $at ?? now()]);

    DB::table('lesson_answer_targets')->insert([
        'lesson_answer_id' => $answer->id,
        'targetable_type' => (new VocabularyItem)->getMorphClass(),
        'targetable_id' => $item->id,
        'is_correct' => true,
    ]);
}

function reviewWord(User $user, VocabularyItem $item, ?CarbonImmutable $at = null): void
{
    $card = SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $item->language_id, 'cardable_type' => VocabularyItem::class, 'cardable_id' => $item->id]);
    SrsReview::factory()->create(['srs_card_id' => $card->id, 'user_id' => $user->id, 'reviewed_at' => $at ?? now()]);
}

it('counts nothing before anything is practised', function () {
    [$language, , $user] = goalWorld();

    expect((new DailyGoal)->wordsToday($user, $language))->toBe(0);
});

it('counts a word once however often it was asked or reviewed today', function () {
    [$language, $unit, $user] = goalWorld();
    [$one, $two] = VocabularyItem::factory()->count(2)->create(['unit_id' => $unit->id, 'language_id' => $language->id]);

    answerWord($user, $one);
    answerWord($user, $one);
    reviewWord($user, $one);
    reviewWord($user, $two);

    expect((new DailyGoal)->wordsToday($user, $language))->toBe(2);
});

it('leaves out yesterday, other learners and other languages', function () {
    [$language, $unit, $user] = goalWorld();
    $item = VocabularyItem::factory()->create(['unit_id' => $unit->id, 'language_id' => $language->id]);
    $foreign = VocabularyItem::factory()->create();

    answerWord($user, $item, CarbonImmutable::yesterday()->setTime(23, 0));
    reviewWord($user, $item, CarbonImmutable::yesterday()->setTime(22, 0));
    answerWord(User::factory()->create(), $item);
    answerWord($user, $foreign);
    reviewWord($user, $foreign);

    expect((new DailyGoal)->wordsToday($user, $language))->toBe(0);
});

it('gives the strip words, goal, streak and reviews due', function () {
    [$language, $unit, $user] = goalWorld();
    $item = VocabularyItem::factory()->create(['unit_id' => $unit->id, 'language_id' => $language->id]);
    answerWord($user, $item);
    Streak::factory()->create(['user_id' => $user->id, 'current_length' => 5, 'last_activity_date' => today()]);
    SrsCard::factory()->create(['user_id' => $user->id, 'language_id' => $language->id, 'due_at' => now()->subHour()]);

    expect((new GetDayStrip)->handle($user, $language))->toBe(['words' => 1, 'goal' => 10, 'streak' => 5, 'due' => 1]);
});

it('hands the unit page the strip', function () {
    [$unit] = LessonWorld::seededHotel();
    $user = LessonWorld::learner();
    $user->forceFill(['current_language_id' => $unit->language_id])->save();

    $this->actingAs($user)->get(route('units.show', $unit))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('day.goal', 10)->has('day.words')->has('day.streak')->has('day.due'));
});
