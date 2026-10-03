import { afterEach, describe, expect, it, vi } from 'vitest';
import { handlePwaLaunch, isPwaLaunch } from './pwaLaunch';

function navigation(url: string): Request {
    const request = new Request(url);

    Object.defineProperty(request, 'mode', { value: 'navigate' });

    return request;
}

afterEach(() => {
    vi.unstubAllGlobals();
});

describe('isPwaLaunch', () => {
    it('matches only the navigation to the launch url', () => {
        expect(isPwaLaunch(navigation('https://h.test/?source=pwa'))).toBe(
            true,
        );
        expect(isPwaLaunch(navigation('https://h.test/?source=pwa&a=1'))).toBe(
            true,
        );
        expect(isPwaLaunch(navigation('https://h.test/'))).toBe(false);
        expect(isPwaLaunch(navigation('https://h.test/?source=x'))).toBe(false);
        expect(isPwaLaunch(navigation('https://h.test/a?source=pwa'))).toBe(
            false,
        );
        expect(isPwaLaunch(new Request('https://h.test/?source=pwa'))).toBe(
            false,
        );
    });
});

describe('handlePwaLaunch', () => {
    const launch = () => navigation('https://h.test/?source=pwa');

    function stubCaches(entries: Record<string, Response>) {
        vi.stubGlobal('caches', {
            open: async () => ({ match: async (key: string) => entries[key] }),
        });
    }

    it('returns the network response when online', async () => {
        const response = new Response('live');
        vi.stubGlobal('fetch', vi.fn().mockResolvedValue(response));

        expect(await handlePwaLaunch(launch())).toBe(response);
    });

    it('falls back to the cached dashboard when offline', async () => {
        const dashboard = new Response('dashboard');
        vi.stubGlobal('fetch', vi.fn().mockRejectedValue(new TypeError('x')));
        stubCaches({ '/dashboard': dashboard, '/': new Response('home') });

        expect(await handlePwaLaunch(launch())).toBe(dashboard);
    });

    it('falls back to the cached welcome page without a dashboard', async () => {
        const home = new Response('home');
        vi.stubGlobal('fetch', vi.fn().mockRejectedValue(new TypeError('x')));
        stubCaches({ '/': home });

        expect(await handlePwaLaunch(launch())).toBe(home);
    });

    it('fails the navigation when nothing is cached', async () => {
        vi.stubGlobal('fetch', vi.fn().mockRejectedValue(new TypeError('x')));
        stubCaches({});

        await expect(handlePwaLaunch(launch())).rejects.toThrow(TypeError);
    });
});
