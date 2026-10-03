<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Testing\AssertableInertia;
use Tests\Support\LocaleCatalogs;

beforeEach(function (): void {
    config(['app.supported_locales' => ['en', 'nl']]);
});

it('keeps Dutch switched off by default', function (): void {
    config(['app.supported_locales' => ['en']]);

    $this->withHeader('Accept-Language', 'nl-NL,nl;q=0.9')
        ->get(route('home'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('interfaceLocale', 'en')
            ->where('supportedLocales', ['en']));

    expect(app()->getLocale())->toBe('en');
});

it('shares the resolved locale and the supported locales', function (): void {
    $user = User::factory()->create(['interface_locale' => 'nl']);

    $this->actingAs($user)->get(route('profile.edit'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('interfaceLocale', 'nl')
            ->where('supportedLocales', ['en', 'nl']));

    expect(app()->getLocale())->toBe('nl');
});

it('prefers the account over the cookie and Accept-Language', function (): void {
    $user = User::factory()->create(['interface_locale' => 'en']);

    $this->actingAs($user)
        ->withUnencryptedCookie('interface_locale', 'nl')
        ->withHeader('Accept-Language', 'nl-NL,nl;q=0.9')
        ->get(route('profile.edit'))
        ->assertInertia(fn (AssertableInertia $page) => $page->where('interfaceLocale', 'en'));
});

it('prefers the cookie over Accept-Language', function (): void {
    $this->withUnencryptedCookie('interface_locale', 'en')
        ->withHeader('Accept-Language', 'nl-NL,nl;q=0.9')
        ->get(route('home'))
        ->assertInertia(fn (AssertableInertia $page) => $page->where('interfaceLocale', 'en'));
});

it('uses the best Accept-Language match when nothing is stored', function (): void {
    $this->withHeader('Accept-Language', 'nl-NL,nl;q=0.9,en;q=0.5')
        ->get(route('home'))
        ->assertInertia(fn (AssertableInertia $page) => $page->where('interfaceLocale', 'nl'));
});

it('skips unsupported Accept-Language entries', function (): void {
    $this->withHeader('Accept-Language', 'de-DE,de;q=0.9,nl;q=0.5')
        ->get(route('home'))
        ->assertInertia(fn (AssertableInertia $page) => $page->where('interfaceLocale', 'nl'));
});

it('falls back to the app locale', function (): void {
    $this->withHeader('Accept-Language', 'de-DE,de;q=0.9')
        ->get(route('home'))
        ->assertInertia(fn (AssertableInertia $page) => $page->where('interfaceLocale', 'en'));
});

it('ignores a stored locale that is no longer supported', function (): void {
    config(['app.supported_locales' => ['en']]);
    $user = User::factory()->create(['interface_locale' => 'nl']);

    $this->actingAs($user)->get(route('profile.edit'))
        ->assertInertia(fn (AssertableInertia $page) => $page->where('interfaceLocale', 'en'));
});

it('writes the resolved locale to the account on the first authenticated visit', function (): void {
    $user = User::factory()->create(['interface_locale' => null]);

    $this->actingAs($user)
        ->withHeader('Accept-Language', 'nl-NL,nl;q=0.9')
        ->get(route('profile.edit'))
        ->assertOk();

    expect($user->fresh()->interface_locale)->toBe('nl');
});

it('copies the signed-out cookie choice into the account on first visit', function (): void {
    $user = User::factory()->create(['interface_locale' => null]);

    $this->actingAs($user)
        ->withUnencryptedCookie('interface_locale', 'en')
        ->withHeader('Accept-Language', 'nl-NL,nl;q=0.9')
        ->get(route('profile.edit'));

    expect($user->fresh()->interface_locale)->toBe('en');
});

it('does not overwrite a stored choice on later visits', function (): void {
    $user = User::factory()->create(['interface_locale' => 'en']);

    $this->actingAs($user)
        ->withHeader('Accept-Language', 'nl-NL,nl;q=0.9')
        ->get(route('profile.edit'));

    expect($user->fresh()->interface_locale)->toBe('en');
});

it('leaves the account untouched while only English is supported', function (): void {
    config(['app.supported_locales' => ['en']]);
    $user = User::factory()->create(['interface_locale' => null]);

    $this->actingAs($user)->get(route('profile.edit'))->assertOk();

    expect($user->fresh()->interface_locale)->toBeNull();
});

it('saves a signed-in choice on the account and in the cookie', function (): void {
    $user = User::factory()->create(['interface_locale' => 'en']);

    $this->actingAs($user)
        ->from(route('profile.edit'))
        ->patch(route('interface-locale.update'), ['interface_locale' => 'nl'])
        ->assertRedirect(route('profile.edit'))
        ->assertCookie('interface_locale', 'nl', encrypted: false);

    expect($user->fresh()->interface_locale)->toBe('nl');
});

it('sets only the cookie for a signed-out visitor', function (): void {
    $this->patch(route('interface-locale.guest.update'), ['interface_locale' => 'nl'])
        ->assertRedirect()
        ->assertCookie('interface_locale', 'nl', encrypted: false);

    expect(User::query()->whereNotNull('interface_locale')->exists())->toBeFalse();
});

it('rejects an unsupported locale', function (): void {
    $user = User::factory()->create(['interface_locale' => 'en']);

    $this->actingAs($user)
        ->patch(route('interface-locale.update'), ['interface_locale' => 'de'])
        ->assertSessionHasErrors('interface_locale');

    $this->patch(route('interface-locale.guest.update'), ['interface_locale' => 'nl_NL'])
        ->assertSessionHasErrors('interface_locale');

    expect($user->fresh()->interface_locale)->toBe('en');
});

it('rejects Dutch while only English is supported', function (): void {
    config(['app.supported_locales' => ['en']]);

    $this->patch(route('interface-locale.guest.update'), ['interface_locale' => 'nl'])
        ->assertSessionHasErrors('interface_locale');
});

it('requires a signed-in user for the account route', function (): void {
    $this->patch(route('interface-locale.update'), ['interface_locale' => 'nl'])
        ->assertRedirect(route('login'));
});

it('asks the user for a locale when sending notifications', function (): void {
    expect(User::factory()->make(['interface_locale' => 'nl'])->preferredLocale())->toBe('nl')
        ->and(User::factory()->make(['interface_locale' => null])->preferredLocale())->toBeNull();
});

it('does not offer a stored locale that is not supported', function (): void {
    config(['app.supported_locales' => ['en']]);

    expect(User::factory()->make(['interface_locale' => 'nl'])->preferredLocale())->toBeNull();
});

it('survives malformed Accept-Language and cookie input', function (string $header): void {
    $this->withHeader('Accept-Language', $header)
        ->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->where('interfaceLocale', 'en'));
})->with(['empty' => [''], 'commas' => [',,'], 'quality only' => [';q=1'], 'wildcard' => ['*'], 'refused' => ['nl;q=0']]);

it('survives an array cookie', function (): void {
    $this->call('GET', route('home'), cookies: ['interface_locale' => ['x']])
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->where('interfaceLocale', 'en'));
});

it('sets the locale cookie as SameSite Lax', function (): void {
    $this->patch(route('interface-locale.guest.update'), ['interface_locale' => 'nl'])
        ->assertCookie('interface_locale');

    $cookie = collect($this->app->make('cookie')->getQueuedCookies())->first();

    expect($cookie?->getSameSite())->toBe('lax');
});

it('renders the server messages Hablas already has in Dutch', function (): void {
    app()->setLocale('nl');

    expect(__('Profile updated.'))->toBe('Profiel bijgewerkt.');
});

it('renders the framework error pages in Dutch', function (int $status, string $message): void {
    Route::middleware('web')->get("/test-abort-{$status}", fn () => abort($status));

    $this->withUnencryptedCookie('interface_locale', 'nl')
        ->get("/test-abort-{$status}")
        ->assertStatus($status)
        ->assertSee($message);
})->with([
    [403, 'Geen toegang'],
    [404, 'Niet gevonden'],
    [429, 'Te veel verzoeken'],
    [500, 'Serverfout'],
]);

it('keeps the en and nl frontend catalogs in key parity', function (): void {
    $en = LocaleCatalogs::flatten(LocaleCatalogs::frontend('en'));
    $nl = LocaleCatalogs::flatten(LocaleCatalogs::frontend('nl'));

    expect(array_keys($nl))->toEqualCanonicalizing(array_keys($en));
});

it('keeps every frontend catalog value filled in and translated', function (): void {
    $en = LocaleCatalogs::flatten(LocaleCatalogs::frontend('en'));
    $nl = LocaleCatalogs::flatten(LocaleCatalogs::frontend('nl'));
    $sameInBoth = ['interfaceLocale.en', 'interfaceLocale.nl', 'nav.dashboard', 'nav.repository'];

    foreach ($nl as $key => $value) {
        expect(trim($value))->not->toBe('', "nl.{$key} is empty");
        expect($value)->not->toContain('—', "nl.{$key} has an em-dash");

        if (! in_array($key, $sameInBoth, true)) {
            expect($value)->not->toBe($en[$key], "nl.{$key} is the English text");
        }
    }
});

it('has a Dutch line for every literal translation key in PHP and no unused ones', function (): void {
    $used = [];

    foreach (LocaleCatalogs::phpSourceFiles() as $path) {
        $source = (string) file_get_contents($path);

        expect(preg_match('/\b(?:__|trans_choice)\(\s*\$/', $source))->toBe(0, "{$path} uses a dynamic translation key");

        preg_match_all('/\b(?:__|trans_choice)\(\s*(\'(?:[^\'\\\\]|\\\\.)*\'|"(?:[^"\\\\]|\\\\.)*")/', $source, $matches);

        foreach ($matches[1] as $literal) {
            $used[] = stripcslashes(substr($literal, 1, -1));
        }
    }

    $catalog = array_keys(json_decode((string) file_get_contents(lang_path('nl.json')), true, flags: JSON_THROW_ON_ERROR));
    $readByFramework = [
        'Unauthorized', 'Payment Required', 'Forbidden', 'Not Found',
        'Page Expired', 'Too Many Requests', 'Server Error', 'Service Unavailable',
    ];

    expect(array_values(array_diff($used, $catalog)))->toBe([])
        ->and(array_values(array_diff($catalog, $used, $readByFramework)))->toBe([]);
});

it('uses the app locale when the browser sends no Accept-Language', function (): void {
    $this->withoutHeader('Accept-Language')
        ->get(route('home'))
        ->assertInertia(fn (AssertableInertia $page) => $page->where('interfaceLocale', 'en'));
});
