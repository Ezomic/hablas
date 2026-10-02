<?php

declare(strict_types=1);

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('serves the page at a URL that does not collide with the static sample directory', function () {
    expect(route('tts-preview', absolute: false))->toBe('/voice-test');
});

it('redirects guests to the login page', function () {
    $this->get(route('tts-preview'))->assertRedirect(route('login'));
});

it('renders the preview page with the sample manifest for authenticated users', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('tts-preview'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('TtsPreview')
            ->has('samples', fn (Assert $samples) => $samples
                ->each(fn (Assert $sample) => $sample
                    ->hasAll(['language', 'engine', 'voice', 'speed', 'kind', 'text', 'file', 'duration', 'bytes', 'licence'])
                    ->etc()
                )
            )
        );
});

it('ships a sample file for every manifest entry', function () {
    $samples = json_decode((string) file_get_contents(public_path('tts-preview/manifest.json')), true, flags: JSON_THROW_ON_ERROR);

    expect($samples)->not->toBeEmpty();

    foreach ($samples as $sample) {
        expect(public_path('tts-preview/'.$sample['file']))->toBeFile();
    }
});
