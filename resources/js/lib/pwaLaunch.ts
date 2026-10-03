import { PAGE_CACHE_NAME } from './pageCache';

export function isPwaLaunch(request: Request): boolean {
    const url = new URL(request.url);

    return (
        request.mode === 'navigate' &&
        url.pathname === '/' &&
        url.searchParams.get('source') === 'pwa'
    );
}

// The server answers a signed-in launch with a redirect, which a browser will
// not serve from a cache for a navigation. So the launch is never cached, and
// offline it falls back to the last cached dashboard (or welcome page).
export async function handlePwaLaunch(request: Request): Promise<Response> {
    try {
        return await fetch(request);
    } catch (error) {
        const cache = await caches.open(PAGE_CACHE_NAME);
        const cached =
            (await cache.match('/dashboard')) ?? (await cache.match('/'));

        if (cached) {
            return cached;
        }

        throw error;
    }
}
