<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\Portal\IdPortalClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

function portalKey(User $user): string
{
    return 'portal-apps:v2:'.sha1($user->email);
}

function appRow(string $slug): array
{
    return ['slug' => $slug, 'name' => ucfirst($slug), 'initials' => strtoupper($slug[0]), 'accent' => null, 'launch_url' => "https://{$slug}.test"];
}

/**
 * ID as the tests see it: one fake whose answer follows $state, so a test can
 * change what ID says without registering a second fake that would never match.
 */
function fakeId(ArrayObject $state): void
{
    Http::fake(function (Request $request) use ($state) {
        if (str_ends_with($request->url(), '/oauth/token')) {
            return Http::response(['access_token' => 'token', 'expires_in' => 600]);
        }

        return Http::response(['applications' => array_map(appRow(...), $state['apps']), 'categories' => []], $state['status']);
    });
}

function appsRequests(): int
{
    return Http::recorded(fn (Request $request): bool => str_ends_with($request->url(), '/api/portal/apps'))->count();
}

beforeEach(function () {
    config([
        'services.thijssensoftware.base_url' => 'https://id.test',
        'services.thijssensoftware.client_id' => 'client',
        'services.thijssensoftware.client_secret' => 'secret',
        'services.thijssensoftware.slug' => 'hablas',
    ]);
    Cache::flush();
    $this->user = User::factory()->create();
    $this->id = new ArrayObject(['status' => 200, 'apps' => ['hablas']]);
    fakeId($this->id);
});

it('fetches once when there is no copy at all, then serves the cache', function () {
    $first = (new IdPortalClient)->appsFor($this->user);
    $second = (new IdPortalClient)->appsFor($this->user);

    expect($first['apps'])->toHaveCount(1)
        ->and($first['apps'][0]['current'])->toBeTrue()
        ->and($second)->toBe($first)
        ->and(appsRequests())->toBe(1);
});

it('serves the stale copy at once when the fresh one has expired, and refreshes it after the response', function () {
    $this->withoutDefer();
    (new IdPortalClient)->appsFor($this->user);
    Cache::forget(portalKey($this->user));
    $this->id['apps'] = ['hablas', 'chronos'];

    $served = (new IdPortalClient)->appsFor($this->user);

    expect($served['apps'])->toHaveCount(1)
        ->and(count((new IdPortalClient)->appsFor($this->user)['apps']))->toBe(2);
});

it('keeps serving the stale copy when ID is down, and asks again only after a minute', function () {
    $this->withoutDefer();
    (new IdPortalClient)->appsFor($this->user);
    Cache::forget(portalKey($this->user));
    $this->id['status'] = 500;

    $served = (new IdPortalClient)->appsFor($this->user);
    (new IdPortalClient)->appsFor($this->user);
    (new IdPortalClient)->appsFor($this->user);

    expect($served['apps'])->toHaveCount(1)
        ->and(appsRequests())->toBe(2);
});

it('returns nothing and does not ask again straight away when ID is down and nothing is cached', function () {
    $this->id['status'] = 500;

    $first = (new IdPortalClient)->appsFor($this->user);
    $second = (new IdPortalClient)->appsFor($this->user);

    expect($first)->toBe(['apps' => [], 'categories' => []])
        ->and($second)->toBe($first)
        ->and(appsRequests())->toBe(1);
});

it('asks again once the minute has passed', function () {
    $this->id['status'] = 500;
    (new IdPortalClient)->appsFor($this->user);

    $this->travel(61)->seconds();
    $this->id['status'] = 200;

    expect((new IdPortalClient)->appsFor($this->user)['apps'])->toHaveCount(1);
});

it('does nothing when the portal is not configured', function () {
    config(['services.thijssensoftware.client_id' => null]);
    Http::fake();

    expect((new IdPortalClient)->appsFor($this->user))->toBe(['apps' => [], 'categories' => []]);
    Http::assertNothingSent();
});
