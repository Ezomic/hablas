<?php

use App\Models\Language;
use App\Models\User;
use Database\Seeders\ContentSeeder;
use Illuminate\Support\Facades\DB;

/**
 * ContentSeeder is what the deploy runs against production, so the property
 * that matters most is that it seeds course content and nothing else.
 */
it('seeds the course content', function () {
    $this->seed(ContentSeeder::class);

    expect(Language::query()->where('code', 'es')->exists())->toBeTrue()
        ->and(DB::table('units')->count())->toBeGreaterThan(0)
        ->and(DB::table('cefr_can_do_statements')->count())->toBeGreaterThan(0);
});

it('never creates users, so it is safe to run on production', function () {
    $this->seed(ContentSeeder::class);

    expect(User::query()->count())->toBe(0);
});

it('is idempotent, so every deploy can run it', function () {
    $this->seed(ContentSeeder::class);

    $counts = fn () => [
        'languages' => Language::query()->count(),
        'units' => DB::table('units')->count(),
        'vocabulary_items' => DB::table('vocabulary_items')->count(),
        'cefr_can_do_statements' => DB::table('cefr_can_do_statements')->count(),
    ];

    $before = $counts();

    $this->seed(ContentSeeder::class);

    expect($counts())->toBe($before);
});
