<?php

use App\Models\Language;
use App\Services\PortugueseTextNormalizer;
use App\Services\SpanishTextNormalizer;
use App\Services\TextNormalizerResolver;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
});

it('resolves the spanish normalizer for spanish', function () {
    $spanish = Language::query()->where('code', 'es')->sole();

    expect((new TextNormalizerResolver)->forLanguage($spanish))->toBeInstanceOf(SpanishTextNormalizer::class);
});

it('resolves the portuguese normalizer for portuguese', function () {
    $portuguese = Language::query()->where('code', 'pt')->sole();

    expect((new TextNormalizerResolver)->forLanguage($portuguese))->toBeInstanceOf(PortugueseTextNormalizer::class);
});

it('refuses a language it has no folding rules for', function () {
    $unknown = Language::factory()->create(['code' => 'xx']);

    expect(fn () => (new TextNormalizerResolver)->forLanguage($unknown))
        ->toThrow(RuntimeException::class, "No text normalizer for language 'xx'.");
});
