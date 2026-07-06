<?php

use App\Actions\Reflections\GetCanDoStatementsForReflection;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\CefrCanDoStatement;
use App\Models\Language;
use App\Models\User;
use App\Models\UserSkillLevel;

it('returns statements matching the user current level for each skill', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    UserSkillLevel::factory()->create([
        'user_id' => $user->id, 'language_id' => $language->id,
        'skill' => Skill::Reading, 'cefr_level' => CefrLevel::B1,
    ]);

    $a1Reading = CefrCanDoStatement::factory()->create(['skill' => Skill::Reading, 'cefr_level' => CefrLevel::A1]);
    $b1Reading = CefrCanDoStatement::factory()->create(['skill' => Skill::Reading, 'cefr_level' => CefrLevel::B1]);

    $statements = (new GetCanDoStatementsForReflection)->handle($user, $language);

    expect($statements->pluck('id'))->toContain($b1Reading->id)
        ->and($statements->pluck('id'))->not->toContain($a1Reading->id);
});

it('defaults to A1 for a skill with no recorded level', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    $a1Writing = CefrCanDoStatement::factory()->create(['skill' => Skill::Writing, 'cefr_level' => CefrLevel::A1]);
    $b2Writing = CefrCanDoStatement::factory()->create(['skill' => Skill::Writing, 'cefr_level' => CefrLevel::B2]);

    $statements = (new GetCanDoStatementsForReflection)->handle($user, $language);

    expect($statements->pluck('id'))->toContain($a1Writing->id)
        ->and($statements->pluck('id'))->not->toContain($b2Writing->id);
});
