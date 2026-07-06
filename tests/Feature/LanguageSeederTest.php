<?php

use App\Models\Language;
use Database\Seeders\LanguageSeeder;

it('seeds spanish as active and portuguese as inactive', function () {
    $this->seed(LanguageSeeder::class);

    $spanish = Language::query()->where('code', 'es')->sole();
    $portuguese = Language::query()->where('code', 'pt')->sole();

    expect($spanish->is_active)->toBeTrue()
        ->and($portuguese->is_active)->toBeFalse();
});

it('is idempotent when run twice', function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(LanguageSeeder::class);

    expect(Language::query()->count())->toBe(2);
});
