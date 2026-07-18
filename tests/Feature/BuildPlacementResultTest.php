<?php

use App\Actions\Placement\BuildPlacementResult;
use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestItem;
use App\Models\PlacementTestResponse;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->spanish = Language::query()->where('code', 'es')->sole();
});

it('builds the blended level, per-skill levels, and a per-question breakdown', function () {
    $attempt = PlacementTestAttempt::factory()->create([
        'language_id' => $this->spanish->id,
        'completed_at' => now(),
        'resulting_skill_levels' => [
            'reading' => ['cefr_level' => 'B1', 'sub_level' => 'B1.1'],
            'listening' => ['cefr_level' => 'A2', 'sub_level' => 'A2.2'],
            'speaking' => ['cefr_level' => 'A2', 'sub_level' => 'A2.1'],
            'writing' => ['cefr_level' => 'B2', 'sub_level' => 'B2'],
        ],
    ]);

    $right = PlacementTestItem::factory()->create(['language_id' => $this->spanish->id, 'skill' => Skill::Reading, 'prompt' => 'Q1', 'correct_answer' => 'fui']);
    $wrong = PlacementTestItem::factory()->create(['language_id' => $this->spanish->id, 'skill' => Skill::Reading, 'prompt' => 'Q2', 'correct_answer' => 'fui']);
    $unsure = PlacementTestItem::factory()->create(['language_id' => $this->spanish->id, 'skill' => Skill::Reading, 'prompt' => 'Q3', 'correct_answer' => 'que']);

    PlacementTestResponse::factory()->create(['attempt_id' => $attempt->id, 'item_id' => $right->id, 'skill' => Skill::Reading, 'response' => 'fui', 'is_correct' => true]);
    PlacementTestResponse::factory()->create(['attempt_id' => $attempt->id, 'item_id' => $wrong->id, 'skill' => Skill::Reading, 'response' => 'era', 'is_correct' => false]);
    PlacementTestResponse::factory()->create(['attempt_id' => $attempt->id, 'item_id' => $unsure->id, 'skill' => Skill::Reading, 'response' => PlacementTestResponse::DONT_KNOW, 'is_correct' => false]);

    $result = (new BuildPlacementResult)->handle($attempt);

    // Blended is the lowest sub-level of the four skills.
    expect($result['blendedLevel'])->toBe('A2.1')
        ->and($result['skipped'])->toBeFalse();

    $reading = collect($result['skills'])->firstWhere('skill', 'reading');
    expect($reading['level'])->toBe('B1.1')
        ->and($reading['items'])->toHaveCount(3)
        ->and($reading['items'][0])->toMatchArray(['prompt' => 'Q1', 'yourAnswer' => 'fui', 'correctAnswer' => 'fui', 'status' => 'correct'])
        ->and($reading['items'][1])->toMatchArray(['prompt' => 'Q2', 'yourAnswer' => 'era', 'correctAnswer' => 'fui', 'status' => 'incorrect'])
        ->and($reading['items'][2])->toMatchArray(['prompt' => 'Q3', 'yourAnswer' => null, 'correctAnswer' => 'que', 'status' => 'dont_know']);

    // A skill with no responses still appears, with its level and an empty list.
    $writing = collect($result['skills'])->firstWhere('skill', 'writing');
    expect($writing['level'])->toBe('B2')
        ->and($writing['items'])->toBe([]);

    // Every listed level uses the sub-level scale, not the parent level.
    $listening = collect($result['skills'])->firstWhere('skill', 'listening');
    expect($listening['level'])->toBe('A2.2');
});

it('flags a skipped attempt that has no responses', function () {
    $attempt = PlacementTestAttempt::factory()->create([
        'language_id' => $this->spanish->id,
        'completed_at' => now(),
        'resulting_skill_levels' => [
            'reading' => ['cefr_level' => 'A1', 'sub_level' => 'A1.1'],
            'listening' => ['cefr_level' => 'A1', 'sub_level' => 'A1.1'],
            'speaking' => ['cefr_level' => 'A1', 'sub_level' => 'A1.1'],
            'writing' => ['cefr_level' => 'A1', 'sub_level' => 'A1.1'],
        ],
    ]);

    $result = (new BuildPlacementResult)->handle($attempt);

    expect($result['skipped'])->toBeTrue()
        ->and($result['blendedLevel'])->toBe('A1.1')
        ->and($result['skills'])->toHaveCount(4);
});
