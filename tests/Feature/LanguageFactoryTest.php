<?php

use App\Models\Language;
use Database\Seeders\LanguageSeeder;

it('never generates the reserved es or pt codes seeded by LanguageSeeder', function () {
    $codes = Language::factory()->count(50)->make()->pluck('code');

    expect($codes)->not->toContain('es')
        ->and($codes)->not->toContain('pt');
});

it('does not collide with LanguageSeeder rows when factory-creating alongside it', function () {
    $this->seed(LanguageSeeder::class);

    Language::factory()->count(50)->create();

    expect(Language::query()->count())->toBe(52)
        ->and(Language::query()->distinct('code')->count('code'))->toBe(52);
});
