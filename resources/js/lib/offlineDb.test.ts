import 'fake-indexeddb/auto';
import { openDB } from 'idb';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import type * as OfflineDb from './offlineDb';

let offlineDb: typeof OfflineDb;

beforeEach(async () => {
    indexedDB = new IDBFactory();
    vi.resetModules();
    offlineDb = await import('./offlineDb');
});

describe('offlineDb', () => {
    it('returns queued submissions oldest-first', async () => {
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/1/attempts',
            '{"a":1}',
        );
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/2/attempts',
            '{"a":2}',
        );
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/3/attempts',
            '{"a":3}',
        );

        const pending = await offlineDb.getPendingSubmissions(1);

        expect(pending.map((submission) => submission.url)).toEqual([
            '/writing/1/attempts',
            '/writing/2/attempts',
            '/writing/3/attempts',
        ]);
    });

    it('returns only the oldest submissions when given a limit', async () => {
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/1/attempts',
            '{"a":1}',
        );
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/2/attempts',
            '{"a":2}',
        );

        const pending = await offlineDb.getPendingSubmissions(1, 1);

        expect(pending.map((submission) => submission.url)).toEqual([
            '/writing/1/attempts',
        ]);
    });

    it('returns and counts only the submissions of the given user', async () => {
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/1/attempts',
            '{"a":1}',
        );
        await offlineDb.queuePendingSubmission(
            2,
            '/writing/2/attempts',
            '{"b":2}',
        );
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/3/attempts',
            '{"a":3}',
        );

        const pending = await offlineDb.getPendingSubmissions(2, 1);

        expect(
            pending.map((submission) => [submission.userId, submission.url]),
        ).toEqual([[2, '/writing/2/attempts']]);
        expect(await offlineDb.countPendingSubmissions(1)).toBe(2);
        expect(await offlineDb.countPendingSubmissions(2)).toBe(1);
        expect(await offlineDb.countPendingSubmissions(3)).toBe(0);
    });

    it('removes a sent submission without disturbing the others', async () => {
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/1/attempts',
            '{"a":1}',
        );
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/2/attempts',
            '{"a":2}',
        );

        const [first, second] = await offlineDb.getPendingSubmissions(1);
        const removed = await offlineDb.removeSentSubmission(first);

        const remaining = await offlineDb.getPendingSubmissions(1);

        expect(removed).toBe(true);
        expect(remaining).toEqual([second]);
    });

    it('keeps a sent submission whose row was requeued with a different answer', async () => {
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/1/attempts',
            '{"a":1}',
        );

        const [sent] = await offlineDb.getPendingSubmissions(1);
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/1/attempts',
            '{"a":2}',
        );
        const removed = await offlineDb.removeSentSubmission(sent);

        const pending = await offlineDb.getPendingSubmissions(1);

        expect(removed).toBe(false);
        expect(
            pending.map((submission) => [submission.id, submission.body]),
        ).toEqual([[sent.id, '{"a":2}']]);
    });

    it('replaces an earlier queued submission for the same url in place', async () => {
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/1/attempts',
            '{"a":1}',
        );
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/2/attempts',
            '{"a":2}',
        );
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/1/attempts',
            '{"a":3}',
        );

        const pending = await offlineDb.getPendingSubmissions(1);

        expect(
            pending.map((submission) => [submission.url, submission.body]),
        ).toEqual([
            ['/writing/1/attempts', '{"a":3}'],
            ['/writing/2/attempts', '{"a":2}'],
        ]);
    });

    it('does not replace a submission another user queued for the same url', async () => {
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/1/attempts',
            '{"a":1}',
        );
        await offlineDb.queuePendingSubmission(
            2,
            '/writing/1/attempts',
            '{"b":1}',
        );

        expect(
            (await offlineDb.getPendingSubmissions(1)).map(
                (submission) => submission.body,
            ),
        ).toEqual(['{"a":1}']);
        expect(
            (await offlineDb.getPendingSubmissions(2)).map(
                (submission) => submission.body,
            ),
        ).toEqual(['{"b":1}']);
    });

    it('removes every submission the given user did not queue', async () => {
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/1/attempts',
            '{"a":1}',
        );
        await offlineDb.queuePendingSubmission(
            2,
            '/writing/2/attempts',
            '{"b":2}',
        );
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/3/attempts',
            '{"a":3}',
        );

        await offlineDb.removeOtherUsersSubmissions(2);

        expect(await offlineDb.countPendingSubmissions(1)).toBe(0);
        expect(
            (await offlineDb.getPendingSubmissions(2)).map(
                (submission) => submission.url,
            ),
        ).toEqual(['/writing/2/attempts']);
    });

    it('clears every queued submission', async () => {
        await offlineDb.queuePendingSubmission(
            1,
            '/writing/1/attempts',
            '{"a":1}',
        );
        await offlineDb.queuePendingSubmission(
            2,
            '/writing/2/attempts',
            '{"b":2}',
        );

        await offlineDb.clearPendingSubmissions();

        expect(await offlineDb.countPendingSubmissions(1)).toBe(0);
        expect(await offlineDb.countPendingSubmissions(2)).toBe(0);
    });

    it('treats submissions queued before they carried a user as belonging to nobody', async () => {
        const legacy = await openDB('hablas-offline', 1, {
            upgrade(db) {
                db.createObjectStore('pendingSubmissions', {
                    keyPath: 'id',
                    autoIncrement: true,
                });
            },
        });
        await legacy.add('pendingSubmissions', {
            url: '/writing/1/attempts',
            body: '{"a":1}',
            queuedAt: 1,
        });
        legacy.close();

        await offlineDb.queuePendingSubmission(
            1,
            '/writing/1/attempts',
            '{"a":2}',
        );

        expect(
            (await offlineDb.getPendingSubmissions(1)).map((submission) => [
                submission.url,
                submission.body,
            ]),
        ).toEqual([['/writing/1/attempts', '{"a":2}']]);

        await offlineDb.removeOtherUsersSubmissions(1);

        const rows = await (
            await openDB('hablas-offline')
        ).getAll('pendingSubmissions');

        expect(rows.map((row) => row.body)).toEqual(['{"a":2}']);
    });
});
