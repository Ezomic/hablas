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

function deferredResponse() {
    let resolve!: (response: Response) => void;
    const promise = new Promise<Response>((settle) => {
        resolve = settle;
    });

    return { promise, resolve };
}

function postedBodies() {
    return vi.mocked(fetch).mock.calls.map(([, init]) => init?.body);
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

        // Mounted offline so no replay runs alongside the submission.
        setOnline(false);
        const { useOfflineSync } = await import('./useOfflineSync');
        const { sync } = mountOfflineSync(useOfflineSync);
        setOnline(true);

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

    it.each([500, 503, 401, 408, 419, 429])(
        'stops replaying at a retryable %i, leaving it and later items queued',
        async (status) => {
            const { queuePendingSubmission, getPendingSubmissions } =
                await import('./../lib/offlineDb');
            await queuePendingSubmission('/writing/1/attempts', '{"a":1}');
            await queuePendingSubmission('/writing/2/attempts', '{"a":2}');

            vi.mocked(fetch).mockResolvedValueOnce(
                new Response('{}', { status }),
            );

            const { useOfflineSync } = await import('./useOfflineSync');
            const { sync } = mountOfflineSync(useOfflineSync);

            await vi.waitFor(() => {
                expect(sync.pendingCount.value).toBe(2);
            });

            expect(fetch).toHaveBeenCalledTimes(1);
            expect(sync.rejectedCount.value).toBe(0);

            const pending = await getPendingSubmissions();
            expect(pending.map((submission) => submission.url)).toEqual([
                '/writing/1/attempts',
                '/writing/2/attempts',
            ]);
        },
    );

    it('stops replaying when the network fails, leaving later items queued', async () => {
        const { queuePendingSubmission, getPendingSubmissions } =
            await import('./../lib/offlineDb');
        await queuePendingSubmission('/writing/1/attempts', '{"a":1}');
        await queuePendingSubmission('/writing/2/attempts', '{"a":2}');

        vi.mocked(fetch).mockRejectedValueOnce(
            new TypeError('Failed to fetch'),
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

    it('drops a submission the server rejects outright and replays the rest', async () => {
        const { queuePendingSubmission, getPendingSubmissions } =
            await import('./../lib/offlineDb');
        await queuePendingSubmission('/writing/1/attempts', '{"a":1}');
        await queuePendingSubmission('/writing/2/attempts', '{"a":2}');

        vi.mocked(fetch)
            .mockResolvedValueOnce(new Response('{}', { status: 422 }))
            .mockResolvedValueOnce(new Response('{}', { status: 200 }));

        const { useOfflineSync } = await import('./useOfflineSync');
        const { sync } = mountOfflineSync(useOfflineSync);

        await vi.waitFor(() => {
            expect(sync.rejectedCount.value).toBe(1);
            expect(sync.pendingCount.value).toBe(0);
        });

        expect(fetch).toHaveBeenCalledTimes(2);
        expect(await getPendingSubmissions()).toEqual([]);
    });

    it('reads the pending count from IndexedDB when it mounts', async () => {
        setOnline(false);
        const { queuePendingSubmission } = await import('./../lib/offlineDb');
        await queuePendingSubmission('/writing/1/attempts', '{"a":1}');
        await queuePendingSubmission('/writing/2/attempts', '{"a":2}');

        const { useOfflineSync } = await import('./useOfflineSync');
        const { sync } = mountOfflineSync(useOfflineSync);

        await vi.waitFor(() => {
            expect(sync.pendingCount.value).toBe(2);
        });
    });

    it('keeps the pending count after a full reload', async () => {
        setOnline(false);
        const first = await import('./useOfflineSync');
        const { wrapper, sync } = mountOfflineSync(first.useOfflineSync);

        await sync.submitOrQueue('/writing/1/attempts', { response: 'hola' });
        wrapper.unmount();

        vi.resetModules();
        const reloaded = await import('./useOfflineSync');
        const { sync: afterReload } = mountOfflineSync(reloaded.useOfflineSync);

        await vi.waitFor(() => {
            expect(afterReload.pendingCount.value).toBe(1);
        });
    });

    it('shares the pending count between every component using it', async () => {
        setOnline(false);
        const { useOfflineSync } = await import('./useOfflineSync');
        const { sync: page } = mountOfflineSync(useOfflineSync);
        const { sync: banner } = mountOfflineSync(useOfflineSync);

        await page.submitOrQueue('/writing/1/attempts', { response: 'hola' });

        expect(banner.pendingCount.value).toBe(1);
    });

    it('queues a repeat submission for the same exercise only once', async () => {
        setOnline(false);
        const { useOfflineSync } = await import('./useOfflineSync');
        const { sync } = mountOfflineSync(useOfflineSync);

        await sync.submitOrQueue('/writing/1/attempts', { response: 'hola' });
        await sync.submitOrQueue('/writing/1/attempts', { response: 'adiós' });

        const { getPendingSubmissions } = await import('./../lib/offlineDb');
        const pending = await getPendingSubmissions();

        expect(sync.pendingCount.value).toBe(1);
        expect(pending.map((submission) => submission.body)).toEqual([
            '{"response":"adiós"}',
        ]);
    });

    it('replays a newer answer queued while the older one was being sent', async () => {
        const { queuePendingSubmission, getPendingSubmissions } =
            await import('./../lib/offlineDb');
        await queuePendingSubmission(
            '/writing/1/attempts',
            '{"response":"old"}',
        );

        const inFlight = deferredResponse();
        vi.mocked(fetch)
            .mockReturnValueOnce(inFlight.promise)
            .mockResolvedValue(new Response('{}', { status: 200 }));

        const { useOfflineSync } = await import('./useOfflineSync');
        const { sync } = mountOfflineSync(useOfflineSync);

        await vi.waitFor(() => {
            expect(fetch).toHaveBeenCalledTimes(1);
        });

        setOnline(false);
        await sync.submitOrQueue('/writing/1/attempts', { response: 'new' });
        inFlight.resolve(new Response('{}', { status: 200 }));

        await vi.waitFor(async () => {
            expect(await getPendingSubmissions()).toEqual([]);
        });

        expect(postedBodies()).toEqual([
            '{"response":"old"}',
            '{"response":"new"}',
        ]);
    });

    it('replays the latest answer for a row requeued while an earlier row was being sent', async () => {
        const { queuePendingSubmission, getPendingSubmissions } =
            await import('./../lib/offlineDb');
        await queuePendingSubmission('/writing/1/attempts', '{"a":1}');
        await queuePendingSubmission(
            '/writing/2/attempts',
            '{"response":"old"}',
        );

        const inFlight = deferredResponse();
        vi.mocked(fetch)
            .mockReturnValueOnce(inFlight.promise)
            .mockResolvedValue(new Response('{}', { status: 200 }));

        const { useOfflineSync } = await import('./useOfflineSync');
        const { sync } = mountOfflineSync(useOfflineSync);

        await vi.waitFor(() => {
            expect(fetch).toHaveBeenCalledTimes(1);
        });

        setOnline(false);
        await sync.submitOrQueue('/writing/2/attempts', { response: 'new' });
        inFlight.resolve(new Response('{}', { status: 200 }));

        await vi.waitFor(async () => {
            expect(await getPendingSubmissions()).toEqual([]);
        });

        expect(postedBodies()).toEqual(['{"a":1}', '{"response":"new"}']);
    });

    it('does not count a rejected answer as discarded once a newer one replaced it', async () => {
        const { queuePendingSubmission, getPendingSubmissions } =
            await import('./../lib/offlineDb');
        await queuePendingSubmission(
            '/writing/1/attempts',
            '{"response":"old"}',
        );

        const inFlight = deferredResponse();
        vi.mocked(fetch)
            .mockReturnValueOnce(inFlight.promise)
            .mockResolvedValue(new Response('{}', { status: 200 }));

        const { useOfflineSync } = await import('./useOfflineSync');
        const { sync } = mountOfflineSync(useOfflineSync);

        await vi.waitFor(() => {
            expect(fetch).toHaveBeenCalledTimes(1);
        });

        setOnline(false);
        await sync.submitOrQueue('/writing/1/attempts', { response: 'new' });
        inFlight.resolve(new Response('{}', { status: 422 }));

        await vi.waitFor(async () => {
            expect(await getPendingSubmissions()).toEqual([]);
        });

        expect(postedBodies()).toEqual([
            '{"response":"old"}',
            '{"response":"new"}',
        ]);
        expect(sync.rejectedCount.value).toBe(0);
    });

    it('does not resend an identical answer queued while it was being sent', async () => {
        const { queuePendingSubmission, getPendingSubmissions } =
            await import('./../lib/offlineDb');
        await queuePendingSubmission(
            '/writing/1/attempts',
            '{"response":"hola"}',
        );

        const inFlight = deferredResponse();
        vi.mocked(fetch).mockReturnValueOnce(inFlight.promise);

        const { useOfflineSync } = await import('./useOfflineSync');
        const { sync } = mountOfflineSync(useOfflineSync);

        await vi.waitFor(() => {
            expect(fetch).toHaveBeenCalledTimes(1);
        });

        setOnline(false);
        await sync.submitOrQueue('/writing/1/attempts', { response: 'hola' });
        inFlight.resolve(new Response('{}', { status: 200 }));

        await vi.waitFor(async () => {
            expect(await getPendingSubmissions()).toEqual([]);
        });

        expect(fetch).toHaveBeenCalledTimes(1);
    });

    it('replays each submission once when several components mount together', async () => {
        const { queuePendingSubmission, getPendingSubmissions } =
            await import('./../lib/offlineDb');
        await queuePendingSubmission('/writing/1/attempts', '{"a":1}');

        vi.mocked(fetch).mockResolvedValue(new Response('{}', { status: 200 }));

        const { useOfflineSync } = await import('./useOfflineSync');
        mountOfflineSync(useOfflineSync);
        mountOfflineSync(useOfflineSync);

        await vi.waitFor(async () => {
            expect(await getPendingSubmissions()).toEqual([]);
        });

        expect(fetch).toHaveBeenCalledTimes(1);
    });

    it('clears the queue, the page cache and the counts', async () => {
        const deleteCache = vi.fn().mockResolvedValue(true);
        vi.stubGlobal('caches', { delete: deleteCache });
        setOnline(false);

        const { useOfflineSync, clearOfflineData } =
            await import('./useOfflineSync');
        const { sync } = mountOfflineSync(useOfflineSync);
        await sync.submitOrQueue('/writing/1/attempts', { response: 'hola' });
        sync.rejectedCount.value = 2;

        await clearOfflineData();

        const { getPendingSubmissions } = await import('./../lib/offlineDb');
        expect(await getPendingSubmissions()).toEqual([]);
        expect(deleteCache).toHaveBeenCalledWith('pages');
        expect(sync.pendingCount.value).toBe(0);
        expect(sync.rejectedCount.value).toBe(0);
    });
});
