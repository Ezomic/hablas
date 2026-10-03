<?php

declare(strict_types=1);

use App\Speech\SpeechNotice;
use Inertia\Testing\AssertableInertia as Assert;

it('shows the credits page to guests', function (): void {
    $this->get(route('credits'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Credits')
            ->has('credits', 1)
            ->where('credits.0.engine', 'supertonic')
            ->where('credits.0.voices.es', ['F1', 'F2', 'M1', 'M2'])
            ->has('credits.0.restrictions', 13));
});

it('renders every configured voice from the config', function (): void {
    config(['speech.languages.es.voices.0.attribution' => 'Another voice by Someone', 'speech.languages.es.voices.0.license' => 'MIT']);

    $this->get(route('credits'))
        ->assertInertia(fn (Assert $page) => $page->has('credits', 2)->where('credits.0.license', 'MIT'));
});

it('keeps the NOTICE file in sync with the config', function (): void {
    expect(file_get_contents(base_path('NOTICE')))->toBe(app(SpeechNotice::class)->handle());
});

it('regenerates the NOTICE file with speech:notice', function (): void {
    $before = file_get_contents(base_path('NOTICE'));

    try {
        file_put_contents(base_path('NOTICE'), 'stale');

        $this->artisan('speech:notice')->assertSuccessful();

        expect(file_get_contents(base_path('NOTICE')))->toBe($before);
    } finally {
        file_put_contents(base_path('NOTICE'), $before);
    }
});

it('translates every use restriction in both interface languages', function (): void {
    $en = json_decode((string) file_get_contents(resource_path('js/lang/en.json')), true);
    $nl = json_decode((string) file_get_contents(resource_path('js/lang/nl.json')), true);

    foreach (config('speech.languages.es.voices.0.restrictions') as $id => $text) {
        expect($en['credits']['restrictions'][$id] ?? null)->toBe($text)
            ->and($nl['credits']['restrictions'][$id] ?? '')->not->toBe('');
    }
});
