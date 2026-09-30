<?php

declare(strict_types=1);

use App\Actions\Placement\FinalizePlacementAttempt;
use App\Actions\ReassessSkillLevel;
use App\Enums\CefrLevel;
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
    ]);
    $exercise = WritingExercise::factory()->create(['language_id' => $language->id]);

    WritingAttempt::factory()->count(20)->create([
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
    ]);
    $exercise = WritingExercise::factory()->create(['language_id' => $language->id]);

    WritingAttempt::factory()->count(10)->create([
        'user_id' => $user->id,
        'writing_exercise_id' => $exercise->id,
        'is_correct' => true,
    ]);
    WritingAttempt::factory()->count(10)->create([
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

    WritingAttempt::factory()->count(20)->create([
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

    WritingAttempt::factory()->count(20)->create([
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
    ]);
    $otherExercise = WritingExercise::factory()->create(['language_id' => $otherLanguage->id]);

    WritingAttempt::factory()->count(20)->create([
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

it('raises the level once after 20 good attempts, and not again on the 21st', function () {
    [$user, $language, $skillLevel, $exercise] = writingAtA1();

    correctWritingAttempts($user, $exercise, 20);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A2)
        ->and($skillLevel->fresh()?->level_set_at?->toDateTimeString())->toBe(now()->toDateTimeString());

    $this->travel(1)->minutes();

    correctWritingAttempts($user, $exercise, 1);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A2);
});

it('raises the level again only after 20 new good attempts', function () {
    [$user, $language, $skillLevel, $exercise] = writingAtA1();

    correctWritingAttempts($user, $exercise, 20);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    $this->travel(1)->minutes();

    correctWritingAttempts($user, $exercise, 19);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A2);

    correctWritingAttempts($user, $exercise, 1);
    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::B1);
});

it('does not count attempts made before a placement finalization after it, even when the level stays the same', function () {
    [$user, $language, $skillLevel, $exercise] = writingAtA1();
    $skillLevel->forceFill(['level_set_at' => now()->subDay()])->save();

    correctWritingAttempts($user, $exercise, 20);

    $this->travel(1)->minutes();

    // No responses settles every skill at A1.3, so writing stays A1.
    (new FinalizePlacementAttempt)->handle(PlacementTestAttempt::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
    ]));

    $this->travel(1)->minutes();

    (new ReassessSkillLevel)->handle($user, $language, Skill::Writing);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A1);

    correctWritingAttempts($user, $exercise, 20);
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

    $shadow(10);
    $prompt(10);

    $this->travel(1)->minutes();
    $skillLevel->forceFill(['level_set_at' => now()])->save();
    $this->travel(1)->minutes();

    $shadow(10);
    $prompt(9);
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

    $attempt(20);

    $this->travel(1)->minutes();
    $skillLevel->forceFill(['level_set_at' => now()])->save();
    $this->travel(1)->minutes();

    $attempt(19);
    (new ReassessSkillLevel)->handle($user, $language, $skill);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A1);

    $attempt(1);
    (new ReassessSkillLevel)->handle($user, $language, $skill);

    expect($skillLevel->fresh()?->cefr_level)->toBe(CefrLevel::A2);
})->with([Skill::Reading, Skill::Listening]);
