<?php

use App\Models\Language;
use Database\Seeders\LanguageSeeder;

it('seeds spanish and portuguese', function () {
    $this->seed(LanguageSeeder::class);

    $spanish = Language::query()->where('code', 'es')->sole();
    $portuguese = Language::query()->where('code', 'pt')->sole();

    expect($spanish->name)->toBe('Spanish')
        ->and($portuguese->name)->toBe('Portuguese');
});

it('is idempotent when run twice', function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(LanguageSeeder::class);

    expect(Language::query()->count())->toBe(2);
});
