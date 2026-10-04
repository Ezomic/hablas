<?php

declare(strict_types=1);

use App\Models\Language;
use Database\Seeders\LanguageSeeder;

it('seeds the four languages', function () {
    $this->seed(LanguageSeeder::class);

    $spanish = Language::query()->where('code', 'es')->sole();
    $portuguese = Language::query()->where('code', 'pt')->sole();

    expect($spanish->name)->toBe('Spanish')
        ->and($portuguese->name)->toBe('Portuguese')
        ->and(Language::query()->where('code', 'fr')->sole()->name)->toBe('French')
        ->and(Language::query()->where('code', 'it')->sole()->name)->toBe('Italian');
});

it('is idempotent when run twice', function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(LanguageSeeder::class);

    expect(Language::query()->count())->toBe(4);
});
