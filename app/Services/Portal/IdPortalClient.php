<?php

declare(strict_types=1);

namespace App\Services\Portal;

use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Fetches the apps a user may open from Thijssensoftware ID, for the in-app
 * portal / app switcher. Talks to ID's client-credentials endpoint and fails
 * soft: any error yields an empty result so the switcher just shows nothing
 * rather than breaking the page.
 */
final class IdPortalClient
{
    private const TOKEN_CACHE_KEY = 'portal-client-token';

    private const CONNECT_TIMEOUT_SECONDS = 2;

    private const TIMEOUT_SECONDS = 3;

    private const BACKOFF_SECONDS = 60;

    private const STALE_COPY_SECONDS = 604800;

    /**
     * @return array{apps: list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string, current: bool}>, categories: list<array{category: string, apps: list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string, current: bool}>}>}
     */
    public function appsFor(User $user): array
    {
        if (! $this->configured()) {
            return ['apps' => [], 'categories' => []];
        }

        $key = 'portal-apps:v2:'.sha1($user->email);

        $cached = $this->stored($key) ?? $this->refresh($user, $key);

        if ($cached === null) {
            return ['apps' => [], 'categories' => []];
        }

        $currentSlug = Config::string('services.thijssensoftware.slug');

        return [
            'apps' => $this->withCurrent($cached['applications'], $currentSlug),
            'categories' => array_map(
                fn (array $group): array => [
                    'category' => $group['category'],
                    'apps' => $this->withCurrent($group['apps'], $currentSlug),
                ],
                $cached['categories'],
            ),
        ];
    }

    /**
     * @param  list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string}>  $apps
     * @return list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string, current: bool}>
     */
    private function withCurrent(array $apps, string $currentSlug): array
    {
        return array_map(
            fn (array $app): array => [...$app, 'current' => $app['slug'] === $currentSlug],
            $apps,
        );
    }

    /**
     * @return array{applications: list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string}>, categories: list<array{category: string, apps: list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string}>}>}|null
     */
    private function stored(string $key): ?array
    {
        $value = Cache::get($key);

        if (! is_array($value)) {
            return null;
        }

        /** @var array{applications: list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string}>, categories: list<array{category: string, apps: list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string}>}>} $value */
        return $value;
    }

    /**
     * The list is fresh for the cache TTL and then kept as a stale copy for a
     * week. Once the fresh copy has expired a page is served the stale one at
     * once and the refresh runs after the response, so no page waits on ID. Only
     * a first visit with no copy at all fetches inline, with short timeouts. A
     * failure is remembered for a minute so a slow or down ID is not retried by
     * every request.
     *
     * @return array{applications: list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string}>, categories: list<array{category: string, apps: list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string}>}>}|null
     */
    private function refresh(User $user, string $key): ?array
    {
        $stale = $this->stored($key.':stale');

        if ($stale !== null) {
            if (! Cache::has($key.':backoff')) {
                Cache::put($key.':backoff', true, self::BACKOFF_SECONDS);
                defer(fn () => $this->load($user, $key));
            }

            return $stale;
        }

        if (Cache::has($key.':backoff')) {
            return null;
        }

        return $this->load($user, $key);
    }

    /**
     * @return array{applications: list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string}>, categories: list<array{category: string, apps: list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string}>}>}|null
     */
    private function load(User $user, string $key): ?array
    {
        $fetched = $this->fetch($user);

        if ($fetched === null) {
            Cache::put($key.':backoff', true, self::BACKOFF_SECONDS);

            return null;
        }

        Cache::forget($key.':backoff');
        Cache::put($key, $fetched, Config::integer('services.thijssensoftware.portal_cache_ttl', 300));
        Cache::put($key.':stale', $fetched, self::STALE_COPY_SECONDS);

        return $fetched;
    }

    /**
     * @return array{applications: list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string}>, categories: list<array{category: string, apps: list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string}>}>}|null
     */
    private function fetch(User $user): ?array
    {
        try {
            $token = $this->token();

            if ($token === null) {
                return null;
            }

            $response = $this->http()->withToken($token)
                ->acceptJson()
                ->asJson()
                ->post($this->url('/api/portal/apps'), ['email' => $user->email]);

            if ($response->failed()) {
                return null;
            }

            /** @var list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string}> $applications */
            $applications = $response->json('applications', []);

            /** @var list<array{category: string, apps: list<array{slug: string, name: string, initials: string, accent: string|null, launch_url: string}>}> $categories */
            $categories = $response->json('categories', []);

            return ['applications' => $applications, 'categories' => $categories];
        } catch (Throwable) {
            return null;
        }
    }

    private function token(): ?string
    {
        $cached = Cache::get(self::TOKEN_CACHE_KEY);

        if (is_string($cached)) {
            return $cached;
        }

        $response = $this->http()->acceptJson()->asForm()->post($this->url('/oauth/token'), [
            'grant_type' => 'client_credentials',
            'client_id' => config('services.thijssensoftware.client_id'),
            'client_secret' => config('services.thijssensoftware.client_secret'),
        ]);

        if ($response->failed()) {
            return null;
        }

        $token = $response->json('access_token');
        $expiresIn = $response->json('expires_in');
        $ttl = is_numeric($expiresIn) ? (int) $expiresIn : 600;

        if (! is_string($token)) {
            return null;
        }

        Cache::put(self::TOKEN_CACHE_KEY, $token, max(60, $ttl - 30));

        return $token;
    }

    private function http(): PendingRequest
    {
        return Http::connectTimeout(self::CONNECT_TIMEOUT_SECONDS)->timeout(self::TIMEOUT_SECONDS);
    }

    private function configured(): bool
    {
        return filled(config('services.thijssensoftware.base_url'))
            && filled(config('services.thijssensoftware.client_id'))
            && filled(config('services.thijssensoftware.client_secret'));
    }

    private function url(string $path): string
    {
        return rtrim(Config::string('services.thijssensoftware.base_url'), '/').$path;
    }
}
