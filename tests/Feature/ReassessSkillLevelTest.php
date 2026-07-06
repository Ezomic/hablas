<?php

use App\Actions\ReassessSkillLevel;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\Language;
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
