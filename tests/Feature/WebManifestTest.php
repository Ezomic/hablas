<?php

declare(strict_types=1);

use App\Models\User;

it('serves the manifest as application/manifest+json', function (): void {
    $response = $this->get(route('manifest'))->assertOk();

    expect($response->headers->get('Content-Type'))->toStartWith('application/manifest+json');
});

it('keeps a stable id and scope while starting somewhere that does not redirect', function (): void {
    $manifest = $this->get(route('manifest'))->json();

    expect($manifest)
        ->id->toBe('/dashboard')
        ->scope->toBe('/')
        ->start_url->toBe('/?source=pwa')
        ->display->toBe('standalone')
        ->and($manifest['icons'])->toHaveCount(3);
});

it('links the manifest from the page head', function (): void {
    $this->get(route('home'))->assertSee('rel="manifest" href="'.route('manifest').'"', false);
});

it('shows the welcome page to signed-out users who launch the installed app', function (): void {
    $this->get('/?source=pwa')->assertOk();
});

it('sends signed-in users into their unit when they launch the installed app', function (): void {
    $this->actingAs(User::factory()->create())
        ->get('/?source=pwa')
        ->assertRedirect(route('continue'));
});

it('sends signed-in users into their unit on a plain visit too', function (): void {
    $this->actingAs(User::factory()->create())->get('/')->assertRedirect(route('continue'));
});

it('sets no cookies and allows caching the manifest for an hour', function (): void {
    $response = $this->get(route('manifest'))->assertOk();

    expect($response->headers->getCookies())->toBeEmpty()
        ->and($response->headers->get('Cache-Control'))->toContain('public')->toContain('max-age=3600');
});

it('points every manifest icon at a file that exists', function (): void {
    $icons = $this->get(route('manifest'))->json('icons');

    foreach ($icons as $icon) {
        expect(public_path(ltrim($icon['src'], '/')))->toBeFile();
    }
});

it('still redirects a signed-in launch that carries extra query parameters', function (): void {
    $this->actingAs(User::factory()->create())
        ->get('/?source=pwa&utm_campaign=x')
        ->assertRedirect(route('continue'));
});

it('sends signed-in users on whatever the source value', function (): void {
    $this->actingAs(User::factory()->create())->get('/?source=newsletter')->assertRedirect(route('continue'));
});
