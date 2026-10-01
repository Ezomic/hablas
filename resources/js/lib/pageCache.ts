// Shared with resources/sw-src/sw.ts, which fills this cache with the last
// render of every visited page. Those renders carry the signed-in user's data.
export const PAGE_CACHE_NAME = 'pages';

export async function clearPageCache(): Promise<void> {
    // CacheStorage only exists in secure contexts.
    if (typeof caches === 'undefined') {
        return;
    }

    await caches.delete(PAGE_CACHE_NAME);
}

/**
 * Puts a page into the cache however it was reached. The service worker only
 * caches full page loads, and an Inertia visit is a fetch, so a page opened
 * through a link or a redirect is cached by asking for it again as a full
 * load, which is what an offline reload then finds.
 */
export async function cachePage(url: string): Promise<void> {
    if (typeof caches === 'undefined') {
        return;
    }

    try {
        const response = await fetch(url, {
            credentials: 'same-origin',
            headers: { Accept: 'text/html' },
        });

        if (response.ok) {
            const cache = await caches.open(PAGE_CACHE_NAME);

            await cache.put(url, response);
        }
    } catch {
        // Offline already: whatever was cached before is what stays.
    }
}
