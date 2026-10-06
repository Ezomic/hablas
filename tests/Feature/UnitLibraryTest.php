<?php

declare(strict_types=1);

use App\Actions\Languages\UnlockLanguageForUser;
use App\Actions\Lessons\StartLessonRun;
use App\Actions\Units\ListUnitLibrary;
use App\Enums\CefrLevel;
use App\Enums\LessonRunKind;
use App\Enums\LessonStage;
use App\Enums\MasteryScope;
use App\Enums\Skill;
use App\Enums\SrsRating;
use App\Enums\UnitProgressStatus;
use App\Models\Language;
use App\Models\Lesson;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\SrsCard;
use App\Models\SrsReview;
use App\Models\Unit;
use App\Models\UnitItemMastery;
use App\Models\User;
use App\Models\UserSkillLevel;
use App\Models\UserUnitProgress;
use App\Models\VocabularyItem;
use Database\Seeders\LanguageSeeder;
use Tests\Fixtures\Lessons\LessonWorld;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
    $this->user = User::factory()->create(['current_language_id' => $this->spanish->id]);
    (new UnlockLanguageForUser)->handle($this->user, $this->spanish);
});

/** @param  array<string, mixed>  $attributes */
function libraryUnit(Language $language, CefrLevel $level, int $sortOrder = 1, array $attributes = []): Unit
{
    return Unit::factory()->create([
        'language_id' => $language->id,
        'cefr_level' => $level,
        'sort_order' => $sortOrder,
        ...$attributes,
    ]);
}

function libraryLevels(User $user, Language $language, CefrLevel $level): void
{
    foreach (Skill::cases() as $skill) {
        UserSkillLevel::factory()->create([
            'user_id' => $user->id,
            'language_id' => $language->id,
            'skill' => $skill,
            'cefr_level' => $level,
        ]);
    }
}

function libraryCompleted(User $user, Unit $unit): void
{
    UserUnitProgress::factory()->create([
        'user_id' => $user->id,
        'unit_id' => $unit->id,
        'status' => UnitProgressStatus::Completed,
    ]);
}

it('requires authentication', function () {
    $this->get(route('units.index'))->assertRedirect(route('login'));
});

it('lists the units of the language being studied, with what each card needs', function () {
    $unit = libraryUnit($this->spanish, CefrLevel::A1, attributes: [
        'title' => 'Ordering coffee',
        'task_description' => 'Order a drink and pay for it.',
        'primary_skill' => Skill::Speaking,
    ]);
    libraryUnit(Language::query()->where('code', 'pt')->sole(), CefrLevel::A1);

    $this->actingAs($this->user)
        ->get(route('units.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('units/Index')
            ->where('language.name', 'Spanish')
            ->where('a1Deadline', '2026-12-01')
            ->has('units', 1)
            ->where('units.0', [
                'id' => $unit->id,
                'title' => 'Ordering coffee',
                'taskDescription' => 'Order a drink and pay for it.',
                'cefrLevel' => 'A1',
                'primarySkill' => 'speaking',
                'lessonCount' => 0,
                'lessonsCompleted' => 0,
                'masteredCount' => 0,
                'percent' => 0,
                'stars' => 0,
                'availability' => 'available',
            ]),
        );
});

it('orders units by level, then by their order within the level', function () {
    libraryLevels($this->user, $this->spanish, CefrLevel::C2);
    $b1 = libraryUnit($this->spanish, CefrLevel::B1, 1);
    $a1Second = libraryUnit($this->spanish, CefrLevel::A1, 2);
    $c1 = libraryUnit($this->spanish, CefrLevel::C1, 1);
    $a2 = libraryUnit($this->spanish, CefrLevel::A2, 5);
    $a1First = libraryUnit($this->spanish, CefrLevel::A1, 1);

    $this->actingAs($this->user)
        ->get(route('units.index'))
        ->assertInertia(fn ($page) => $page
            ->where('units', fn ($units) => collect($units)->pluck('id')->all() === [
                $a1First->id, $a1Second->id, $a2->id, $b1->id, $c1->id,
            ]),
        );
});

it('shows completed, available and locked units against the blended level', function () {
    libraryLevels($this->user, $this->spanish, CefrLevel::A2);
    $completed = libraryUnit($this->spanish, CefrLevel::A1, 1);
    libraryCompleted($this->user, $completed);
    libraryUnit($this->spanish, CefrLevel::A2, 1);
    libraryUnit($this->spanish, CefrLevel::B1, 1);

    $this->actingAs($this->user)
        ->get(route('units.index'))
        ->assertInertia(fn ($page) => $page
            ->where('units.0.availability', 'completed')
            ->where('units.1.availability', 'available')
            ->where('units.2.availability', 'locked'),
        );
});

it('keeps a completed unit open after the level drops below it', function () {
    libraryLevels($this->user, $this->spanish, CefrLevel::A1);
    $unit = libraryUnit($this->spanish, CefrLevel::B1);
    libraryCompleted($this->user, $unit);

    $this->actingAs($this->user)
        ->get(route('units.index'))
        ->assertInertia(fn ($page) => $page->where('units.0.availability', 'completed'));

    $this->actingAs($this->user)
        ->get(route('units.show', $unit))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('isCompleted', true));
});

it('opens A1 to a learner who has no skill levels yet', function () {
    libraryUnit($this->spanish, CefrLevel::A1, 1);
    libraryUnit($this->spanish, CefrLevel::A2, 1);

    $this->actingAs($this->user)
        ->get(route('units.index'))
        ->assertInertia(fn ($page) => $page
            ->where('units.0.availability', 'available')
            ->where('units.1.availability', 'locked'),
        );
});

it('holds new units back while the learner needs to reinforce, and keeps completed ones open', function () {
    libraryLevels($this->user, $this->spanish, CefrLevel::A1);
    $completed = libraryUnit($this->spanish, CefrLevel::A1, 1);
    libraryCompleted($this->user, $completed);
    libraryUnit($this->spanish, CefrLevel::A1, 2);
    libraryUnit($this->spanish, CefrLevel::A2, 1);
    $card = SrsCard::factory()->create(['user_id' => $this->user->id, 'language_id' => $this->spanish->id]);
    SrsReview::factory()->count(8)->create([
        'user_id' => $this->user->id,
        'srs_card_id' => $card->id,
        'rating' => SrsRating::Again,
    ]);

    $this->actingAs($this->user)
        ->get(route('units.index'))
        ->assertInertia(fn ($page) => $page
            ->where('units.0.availability', 'completed')
            ->where('units.1.availability', 'held_back')
            ->where('units.2.availability', 'locked'),
        );

    $this->actingAs($this->user)->get(route('units.show', $completed))->assertOk();
});

it('refuses to open or complete a locked unit, and enrolls nothing', function () {
    libraryLevels($this->user, $this->spanish, CefrLevel::A2);
    $locked = libraryUnit($this->spanish, CefrLevel::B1);
    VocabularyItem::factory()->create(['language_id' => $this->spanish->id, 'unit_id' => $locked->id]);

    $this->actingAs($this->user)->get(route('units.show', $locked))->assertForbidden();
    $this->actingAs($this->user)->post(route('units.completion.store', $locked))->assertForbidden();

    expect(SrsCard::query()->count())->toBe(0)
        ->and(UserUnitProgress::query()->count())->toBe(0);
});

it('renders an empty library for a learner with no language', function () {
    libraryUnit($this->spanish, CefrLevel::A1);
    $this->user->unlockedLanguages()->detach();

    $this->actingAs($this->user)
        ->get(route('units.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('units/Index')
            ->where('language', null)
            ->has('units', 0),
        );
});

function libraryLesson(Unit $unit, LessonStage $stage): Lesson
{
    $lesson = Lesson::factory()->stage($stage)->create(['unit_id' => $unit->id]);
    LessonExercise::factory()->create(['lesson_id' => $lesson->id]);

    return $lesson;
}

it('does not count a lesson with nothing left to play', function () {
    libraryLevels($this->user, $this->spanish, CefrLevel::A1);
    $unit = libraryUnit($this->spanish, CefrLevel::A1, 1);
    $meet = libraryLesson($unit, LessonStage::Meet);
    $empty = Lesson::factory()->stage(LessonStage::Recall)->create(['unit_id' => $unit->id]);
    $retired = Lesson::factory()->stage(LessonStage::Sentences)->create(['unit_id' => $unit->id]);
    LessonExercise::factory()->create(['lesson_id' => $retired->id, 'retired_at' => now()]);
    LessonRun::factory()->completed()->create(['user_id' => $this->user->id, 'lesson_id' => $meet->id]);
    LessonRun::factory()->completed()->create(['user_id' => $this->user->id, 'lesson_id' => $empty->id]);

    $row = (new ListUnitLibrary)->handle($this->user, $this->spanish)[0];

    expect([$row['lessonCount'], $row['lessonsCompleted']])->toBe([1, 1]);
});

it('counts a passed unit check as completing the check lesson', function () {
    [$unit] = LessonWorld::seededHotel();
    $user = LessonWorld::learner();
    LessonWorld::finishTeachingLessons($user, $unit);
    $check = (new StartLessonRun)->handle($user, LessonWorld::lesson($unit, LessonStage::Check), LessonRunKind::Check);
    LessonWorld::play($user, $check);

    $row = collect((new ListUnitLibrary)->handle($user, LessonWorld::spanish()))->firstWhere('id', $unit->id);

    expect($row['lessonCount'])->toBe(5)
        ->and($row['lessonsCompleted'])->toBe(5);
});

it('lists how many lessons a unit has, how many were completed and how many items are mastered', function () {
    libraryLevels($this->user, $this->spanish, CefrLevel::A1);
    $unit = libraryUnit($this->spanish, CefrLevel::A1, 1);
    $meet = libraryLesson($unit, LessonStage::Meet);
    libraryLesson($unit, LessonStage::Recall);
    LessonRun::factory()->completed()->count(2)->create(['user_id' => $this->user->id, 'lesson_id' => $meet->id]);
    $item = VocabularyItem::factory()->create(['language_id' => $this->spanish->id, 'unit_id' => $unit->id]);

    foreach ([MasteryScope::Words, MasteryScope::Full] as $scope) {
        UnitItemMastery::factory()->create([
            'user_id' => $this->user->id,
            'unit_id' => $unit->id,
            'masterable_type' => $item->getMorphClass(),
            'masterable_id' => $item->id,
            'scope' => $scope,
        ]);
    }

    $row = (new ListUnitLibrary)->handle($this->user, $this->spanish)[0];

    expect([$row['lessonCount'], $row['lessonsCompleted'], $row['masteredCount']])->toBe([2, 1, 1]);
});

it('lists a unit in progress as in progress', function () {
    libraryLevels($this->user, $this->spanish, CefrLevel::A1);
    $unit = libraryUnit($this->spanish, CefrLevel::A1, 1);
    UserUnitProgress::factory()->create(['user_id' => $this->user->id, 'unit_id' => $unit->id, 'status' => UnitProgressStatus::InProgress, 'completed_at' => null]);

    expect((new ListUnitLibrary)->handle($this->user, $this->spanish)[0]['availability'])->toBe('in_progress');
});
