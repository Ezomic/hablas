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
