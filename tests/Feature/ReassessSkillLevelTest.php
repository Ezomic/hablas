<?php

declare(strict_types=1);

use App\Actions\NotifyOnBlendedLevelIncrease;
use App\Actions\Placement\FinalizePlacementAttempt;
use App\Actions\ReassessSkillLevel;
use App\Enums\CefrLevel;
use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\ListeningAttempt;
use App\Models\ListeningExercise;
use App\Models\PlacementTestAttempt;
use App\Models\ReadingAttempt;
use App\Models\ReadingPassage;
use App\Models\ScriptedPromptAttempt;
use App\Models\ScriptedPromptExercise;
use App\Models\ShadowingAttempt;
use App\Models\ShadowingExercise;
use App\Models\User;
use App\Models\UserSkillLevel;
use App\Models\WritingAttempt;
use App\Models\WritingExercise;

it('bumps writing up one CEFR level after a high-success attempt streak', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $skillLevel = UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::A1,
        'sub_level' => CefrSubLevel::A1_3,
    ]);
    $exercise = WritingExercise::factory()->create(['language_id' => $language->id]);

    WritingAttempt::factory()->count(10)->create([
        'user_id' => $user->id,
        'writing_exercise_id' => $exercise->id,
        'is_correct' => true,
    ]);

    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()->cefr_level)->toBe(CefrLevel::A2);
});

it('does not bump the level when the success rate is below the threshold', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $skillLevel = UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::A1,
        'sub_level' => CefrSubLevel::A1_3,
    ]);
    $exercise = WritingExercise::factory()->create(['language_id' => $language->id]);

    WritingAttempt::factory()->count(5)->create([
        'user_id' => $user->id,
        'writing_exercise_id' => $exercise->id,
        'is_correct' => true,
    ]);
    WritingAttempt::factory()->count(5)->create([
        'user_id' => $user->id,
        'writing_exercise_id' => $exercise->id,
        'is_correct' => false,
    ]);

    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()->cefr_level)->toBe(CefrLevel::A1);
});

it('does not bump the level when there are not yet enough attempts', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $skillLevel = UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::A1,
        'sub_level' => CefrSubLevel::A1_3,
    ]);
    $exercise = WritingExercise::factory()->create(['language_id' => $language->id]);

    WritingAttempt::factory()->count(5)->create([
        'user_id' => $user->id,
        'writing_exercise_id' => $exercise->id,
        'is_correct' => true,
    ]);

    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()->cefr_level)->toBe(CefrLevel::A1);
});

it('does nothing when the user has no skill level row yet for that skill', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $exercise = WritingExercise::factory()->create(['language_id' => $language->id]);

    WritingAttempt::factory()->count(10)->create([
        'user_id' => $user->id,
        'writing_exercise_id' => $exercise->id,
        'is_correct' => true,
    ]);

    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect(UserSkillLevel::query()->where('user_id', $user->id)->exists())->toBeFalse();
});

it('never bumps a skill already at the top of the CEFR scale', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $skillLevel = UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::C2,
    ]);
    $exercise = WritingExercise::factory()->create(['language_id' => $language->id]);

    WritingAttempt::factory()->count(10)->create([
        'user_id' => $user->id,
        'writing_exercise_id' => $exercise->id,
        'is_correct' => true,
    ]);

    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()->cefr_level)->toBe(CefrLevel::C2);
});

it('combines shadowing and scripted-prompt attempts for the speaking skill', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $skillLevel = UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Speaking,
        'cefr_level' => CefrLevel::A1,
        'sub_level' => CefrSubLevel::A1_3,
    ]);
    $shadowingExercise = ShadowingExercise::factory()->create(['language_id' => $language->id]);
    $scriptedPromptExercise = ScriptedPromptExercise::factory()->create(['language_id' => $language->id]);

    ShadowingAttempt::factory()->count(10)->create([
        'user_id' => $user->id,
        'shadowing_exercise_id' => $shadowingExercise->id,
        'score' => 90,
    ]);
    ScriptedPromptAttempt::factory()->count(10)->create([
        'user_id' => $user->id,
        'scripted_prompt_exercise_id' => $scriptedPromptExercise->id,
        'score' => 90,
    ]);

    (new ReassessSkillLevel)->handle($user, $language, Skill::Speaking);

    expect($skillLevel->fresh()->cefr_level)->toBe(CefrLevel::A2);
});

it('never mixes attempt history from a different language deck', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $otherLanguage = Language::factory()->create();
    $skillLevel = UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::A1,
        'sub_level' => CefrSubLevel::A1_3,
    ]);
    $otherExercise = WritingExercise::factory()->create(['language_id' => $otherLanguage->id]);

    WritingAttempt::factory()->count(10)->create([
        'user_id' => $user->id,
        'writing_exercise_id' => $otherExercise->id,
        'is_correct' => true,
    ]);

    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()->cefr_level)->toBe(CefrLevel::A1);
});

/** @return array{User, Language, UserSkillLevel, WritingExercise} */
function writingAtA1(): array
{
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $skillLevel = UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'cefr_level' => CefrLevel::A1,
        'sub_level' => CefrSubLevel::A1_3,
    ]);

    return [$user, $language, $skillLevel, WritingExercise::factory()->create(['language_id' => $language->id])];
}

function correctWritingAttempts(User $user, WritingExercise $exercise, int $count): void
{
    WritingAttempt::factory()->count($count)->create([
        'user_id' => $user->id,
        'writing_exercise_id' => $exercise->id,
        'is_correct' => true,
    ]);
}

it('raises the level once after 10 good attempts, and not again on the 11th', function () {
    [$user, $language, $skillLevel, $exercise] = writingAtA1();

    correctWritingAttempts($user, $exercise, 10);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A2)
        ->and($skillLevel->fresh()?->level_set_at?->toDateTimeString())->toBe(now()->toDateTimeString());

    $this->travel(1)->minutes();

    correctWritingAttempts($user, $exercise, 1);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A2);
});

it('steps again only after 10 new good attempts', function () {
    [$user, $language, $skillLevel, $exercise] = writingAtA1();

    correctWritingAttempts($user, $exercise, 10);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    $this->travel(1)->minutes();

    correctWritingAttempts($user, $exercise, 9);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->sub_level)->toBe(CefrSubLevel::A2_1);

    correctWritingAttempts($user, $exercise, 1);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->sub_level)->toBe(CefrSubLevel::A2_2)
        ->and($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A2);
});

it('does not count attempts made before a placement finalization after it, even when the level stays the same', function () {
    [$user, $language, $skillLevel, $exercise] = writingAtA1();
    $skillLevel->forceFill(['level_set_at' => now()->subDay()])->save();

    correctWritingAttempts($user, $exercise, 10);

    $this->travel(1)->minutes();

    // No responses settles every skill at A1.3, so writing stays A1.
    (new FinalizePlacementAttempt)->handle(PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
    ]));

    $this->travel(1)->minutes();

    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A1);

    correctWritingAttempts($user, $exercise, 10);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A2);
});

it('combines shadowing and scripted-prompt attempts made since the speaking level was set', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $skillLevel = UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Speaking,
        'cefr_level' => CefrLevel::A1,
        'sub_level' => CefrSubLevel::A1_3,
    ]);
    $shadowingExercise = ShadowingExercise::factory()->create(['language_id' => $language->id]);
    $scriptedPromptExercise = ScriptedPromptExercise::factory()->create(['language_id' => $language->id]);

    $shadow = fn (int $count) => ShadowingAttempt::factory()->count($count)->create([
        'user_id' => $user->id,
        'shadowing_exercise_id' => $shadowingExercise->id,
        'score' => 90,
    ]);
    $prompt = fn (int $count) => ScriptedPromptAttempt::factory()->count($count)->create([
        'user_id' => $user->id,
        'scripted_prompt_exercise_id' => $scriptedPromptExercise->id,
        'score' => 90,
    ]);

    $shadow(5);
    $prompt(5);

    $this->travel(1)->minutes();
    $skillLevel->forceFill(['level_set_at' => now()])->save();
    $this->travel(1)->minutes();

    $shadow(5);
    $prompt(4);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Speaking);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A1);

    $prompt(1);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Speaking);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A2);
});

it('counts only comprehension attempts made since the level was set', function (Skill $skill) {
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $skillLevel = UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => $skill,
        'cefr_level' => CefrLevel::A1,
        'sub_level' => CefrSubLevel::A1_3,
    ]);

    $attempt = $skill === Skill::Reading
        ? fn (int $count) => ReadingAttempt::factory()->count($count)->create([
            'user_id' => $user->id,
            'reading_passage_id' => ReadingPassage::factory()->create(['language_id' => $language->id])->id,
            'score' => 100,
        ])
        : fn (int $count) => ListeningAttempt::factory()->count($count)->create([
            'user_id' => $user->id,
            'listening_exercise_id' => ListeningExercise::factory()->create(['language_id' => $language->id])->id,
            'score' => 100,
        ]);

    $attempt(10);

    $this->travel(1)->minutes();
    $skillLevel->forceFill(['level_set_at' => now()])->save();
    $this->travel(1)->minutes();

    $attempt(9);
    (new ReassessSkillLevel)->handle($user, $language, $skill);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A1);

    $attempt(1);
    (new ReassessSkillLevel)->handle($user, $language, $skill);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A2);
})->with([Skill::Reading, Skill::Listening]);

function writingAt(CefrLevel $level, ?CefrSubLevel $tier): array
{
    $user = User::factory()->create();
    $language = Language::factory()->create();
    $skillLevel = UserSkillLevel::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'skill' => Skill::Writing,
        'cefr_level' => $level,
        'sub_level' => $tier,
    ]);

    return [$user, $language, $skillLevel, WritingExercise::factory()->create(['language_id' => $language->id])];
}

it('moves one tier inside a level and keeps the parent level', function () {
    [$user, $language, $skillLevel, $exercise] = writingAt(CefrLevel::A1, CefrSubLevel::A1_1);

    correctWritingAttempts($user, $exercise, 10);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->sub_level)->toBe(CefrSubLevel::A1_2)
        ->and($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A1)
        ->and($skillLevel->fresh()?->level_set_at?->toDateTimeString())->toBe(now()->toDateTimeString());
});

it('changes the level on the last tier of a level, and the milestone toast fires when that lifts the floor', function () {
    [$user, $language, $skillLevel, $exercise] = writingAt(CefrLevel::A1, CefrSubLevel::A1_3);
    correctWritingAttempts($user, $exercise, 10);

    $toast = (new NotifyOnBlendedLevelIncrease)->handle($user, $language, fn () => (new ReassessSkillLevel)->handle($user, $language, Skill::Writing));

    expect($skillLevel->fresh()?->sub_level)->toBe(CefrSubLevel::A2_1)
        ->and($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A2)
        ->and($toast)->toBe(['type' => 'milestone', 'message' => "You've reached A2 in {$language->name}!"]);
});

it('does not fire the milestone toast for a step inside a level', function () {
    [$user, $language, , $exercise] = writingAt(CefrLevel::A1, CefrSubLevel::A1_1);
    correctWritingAttempts($user, $exercise, 10);

    $toast = (new NotifyOnBlendedLevelIncrease)->handle($user, $language, fn () => (new ReassessSkillLevel)->handle($user, $language, Skill::Writing));

    expect($toast)->toBeNull();
});

it('moves B1.2 to B2, then B2 to C1 with no tier, and leaves C2 alone', function () {
    [$user, $language, $skillLevel, $exercise] = writingAt(CefrLevel::B1, CefrSubLevel::B1_2);

    correctWritingAttempts($user, $exercise, 10);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->sub_level)->toBe(CefrSubLevel::B2)
        ->and($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::B2);

    $this->travel(1)->minutes();
    correctWritingAttempts($user, $exercise, 20);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->sub_level)->toBeNull()
        ->and($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::C1);

    $this->travel(1)->minutes();
    correctWritingAttempts($user, $exercise, 20);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::C2)
        ->and($skillLevel->fresh()?->sub_level)->toBeNull();
});

it('still needs 20 attempts for a move above B2, where there are no tiers', function (CefrLevel $level, ?CefrSubLevel $tier, CefrLevel $expected) {
    [$user, $language, $skillLevel, $exercise] = writingAt($level, $tier);

    correctWritingAttempts($user, $exercise, 19);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->cefr_level)->toBe($level);

    correctWritingAttempts($user, $exercise, 1);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->cefr_level)->toBe($expected);
})->with([
    'B2 to C1' => [CefrLevel::B2, CefrSubLevel::B2, CefrLevel::C1],
    'C1 to C2' => [CefrLevel::C1, null, CefrLevel::C2],
]);

it('needs 10 attempts since the last step, not since the beginning', function () {
    [$user, $language, $skillLevel, $exercise] = writingAt(CefrLevel::A1, CefrSubLevel::A1_1);

    correctWritingAttempts($user, $exercise, 10);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    $this->travel(1)->minutes();
    correctWritingAttempts($user, $exercise, 9);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->sub_level)->toBe(CefrSubLevel::A1_2);
});

it('starts a row with no stored tier from the first tier of its level', function (CefrLevel $level, CefrSubLevel $expected) {
    [$user, $language, $skillLevel, $exercise] = writingAt($level, null);

    correctWritingAttempts($user, $exercise, 10);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->sub_level)->toBe($expected);
})->with([
    [CefrLevel::A1, CefrSubLevel::A1_2],
    [CefrLevel::A2, CefrSubLevel::A2_2],
    [CefrLevel::B1, CefrSubLevel::B1_2],
]);
