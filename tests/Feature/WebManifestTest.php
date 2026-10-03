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

it('lands signed-in users on the dashboard when they launch the installed app', function (): void {
    $this->actingAs(User::factory()->create())
        ->get('/?source=pwa')
        ->assertRedirect(route('dashboard'));
});

it('still shows signed-in users the welcome page on a plain visit', function (): void {
    $this->actingAs(User::factory()->create())->get('/')->assertOk();
});
