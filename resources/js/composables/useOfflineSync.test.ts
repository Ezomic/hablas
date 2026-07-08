import 'fake-indexeddb/auto';
import { mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { defineComponent, h } from 'vue';
import type { useOfflineSync as UseOfflineSync } from './useOfflineSync';

function mountOfflineSync(useOfflineSync: typeof UseOfflineSync) {
    let exposed!: ReturnType<typeof UseOfflineSync>;

    const wrapper = mount(
        defineComponent({
            setup() {
                exposed = useOfflineSync();

                return () => h('div');
            },
        }),
    );

    return { wrapper, sync: exposed };
}

function setOnline(value: boolean) {
    Object.defineProperty(navigator, 'onLine', {
        configurable: true,
        value,
    });
}

beforeEach(() => {
    indexedDB = new IDBFactory();
    vi.resetModules();
    vi.stubGlobal('fetch', vi.fn());
    setOnline(true);
});

afterEach(() => {
    vi.unstubAllGlobals();
});

describe('useOfflineSync', () => {
    it('submits directly and does not queue when online and the request succeeds', async () => {
        const response = new Response('{}', { status: 200 });
        vi.mocked(fetch).mockResolvedValueOnce(response);

        const { useOfflineSync } = await import('./useOfflineSync');
        const { sync } = mountOfflineSync(useOfflineSync);

        const result = await sync.submitOrQueue('/writing/1/attempts', {
            response: 'hola',
        });

        expect(result).toEqual({ queued: false, response });
        expect(fetch).toHaveBeenCalledTimes(1);

        const { getPendingSubmissions } = await import('./../lib/offlineDb');
        expect(await getPendingSubmissions()).toEqual([]);
    });

    it('queues instead of submitting when offline', async () => {
        setOnline(false);

        const { useOfflineSync } = await import('./useOfflineSync');
        const { sync } = mountOfflineSync(useOfflineSync);

        const result = await sync.submitOrQueue('/writing/1/attempts', {
            response: 'hola',
        });

        expect(result).toEqual({ queued: true });
        expect(fetch).not.toHaveBeenCalled();

        const { getPendingSubmissions } = await import('./../lib/offlineDb');
        const pending = await getPendingSubmissions();
        expect(pending).toHaveLength(1);
        expect(pending[0].url).toBe('/writing/1/attempts');
    });

    it('queues when nominally online but the request throws', async () => {
        vi.mocked(fetch).mockRejectedValueOnce(
            new TypeError('Failed to fetch'),
        );

        const { useOfflineSync } = await import('./useOfflineSync');
        const { sync } = mountOfflineSync(useOfflineSync);

        const result = await sync.submitOrQueue('/writing/1/attempts', {
            response: 'hola',
        });

        expect(result).toEqual({ queued: true });

        const { getPendingSubmissions } = await import('./../lib/offlineDb');
        expect(await getPendingSubmissions()).toHaveLength(1);
    });

    it('replays queued submissions in order on mount when already online', async () => {
        const { queuePendingSubmission, getPendingSubmissions } =
            await import('./../lib/offlineDb');
        await queuePendingSubmission('/writing/1/attempts', '{"a":1}');
        await queuePendingSubmission('/writing/2/attempts', '{"a":2}');

        vi.mocked(fetch).mockResolvedValue(new Response('{}', { status: 200 }));

        const { useOfflineSync } = await import('./useOfflineSync');
        mountOfflineSync(useOfflineSync);

        await vi.waitFor(async () => {
            expect(await getPendingSubmissions()).toEqual([]);
        });

        expect(fetch).toHaveBeenNthCalledWith(
            1,
            '/writing/1/attempts',
            expect.anything(),
        );
        expect(fetch).toHaveBeenNthCalledWith(
            2,
            '/writing/2/attempts',
            expect.anything(),
        );
    });

    it('stops replaying at the first failure, leaving later items queued', async () => {
        const { queuePendingSubmission, getPendingSubmissions } =
            await import('./../lib/offlineDb');
        await queuePendingSubmission('/writing/1/attempts', '{"a":1}');
        await queuePendingSubmission('/writing/2/attempts', '{"a":2}');

        vi.mocked(fetch).mockResolvedValueOnce(
            new Response('{}', { status: 422 }),
        );

        const { useOfflineSync } = await import('./useOfflineSync');
        mountOfflineSync(useOfflineSync);

        await vi.waitFor(() => {
            expect(fetch).toHaveBeenCalledTimes(1);
        });

        const pending = await getPendingSubmissions();
        expect(pending).toHaveLength(2);
        expect(pending[0].url).toBe('/writing/1/attempts');
    });
});
