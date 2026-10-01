import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { cachePage, clearPageCache, PAGE_CACHE_NAME } from './pageCache';

const put = vi.fn();
const open = vi.fn();

beforeEach(() => {
    put.mockReset();
    open.mockReset().mockResolvedValue({ put });
    vi.stubGlobal('caches', { open, delete: vi.fn() });
});

afterEach(() => {
    vi.unstubAllGlobals();
});

describe('cachePage', () => {
    it('fetches the page as a full load, without the Inertia header, and caches it', async () => {
        const response = new Response('<html></html>', { status: 200 });
        vi.stubGlobal('fetch', vi.fn().mockResolvedValue(response));

        await cachePage('/lesson-runs/5');

        const [url, init] = vi.mocked(fetch).mock.calls[0];

        expect(url).toBe('/lesson-runs/5');
        expect(init?.headers).toEqual({ Accept: 'text/html' });
        expect(open).toHaveBeenCalledWith(PAGE_CACHE_NAME);
        expect(put).toHaveBeenCalledWith('/lesson-runs/5', response);
    });

    it('caches nothing when the page does not load', async () => {
        vi.stubGlobal(
            'fetch',
            vi.fn().mockResolvedValue(new Response('', { status: 500 })),
        );

        await cachePage('/lesson-runs/5');

        expect(put).not.toHaveBeenCalled();
    });

    it('leaves the cache alone when the request fails, as it does offline', async () => {
        vi.stubGlobal(
            'fetch',
            vi.fn().mockRejectedValue(new TypeError('offline')),
        );

        await expect(cachePage('/lesson-runs/5')).resolves.toBeUndefined();
        expect(put).not.toHaveBeenCalled();
    });

    it('does nothing where CacheStorage does not exist', async () => {
        vi.unstubAllGlobals();
        vi.stubGlobal('caches', undefined);
        vi.stubGlobal('fetch', vi.fn());

        await cachePage('/lesson-runs/5');

        expect(fetch).not.toHaveBeenCalled();
    });
});

describe('clearPageCache', () => {
    it('deletes the pages cache', async () => {
        const remove = vi.fn();
        vi.stubGlobal('caches', { delete: remove });

        await clearPageCache();

        expect(remove).toHaveBeenCalledWith(PAGE_CACHE_NAME);
    });
});
