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
            '/writing/1/attempts',
            '{"a":1}',
        );
        await offlineDb.queuePendingSubmission(
            '/writing/2/attempts',
            '{"a":2}',
        );
        await offlineDb.queuePendingSubmission(
            '/writing/3/attempts',
            '{"a":3}',
        );

        const pending = await offlineDb.getPendingSubmissions();

        expect(pending.map((submission) => submission.url)).toEqual([
            '/writing/1/attempts',
            '/writing/2/attempts',
            '/writing/3/attempts',
        ]);
    });

    it('removes a submission by id without disturbing the others', async () => {
        await offlineDb.queuePendingSubmission(
            '/writing/1/attempts',
            '{"a":1}',
        );
        await offlineDb.queuePendingSubmission(
            '/writing/2/attempts',
            '{"a":2}',
        );

        const [first, second] = await offlineDb.getPendingSubmissions();
        await offlineDb.removePendingSubmission(first.id);

        const remaining = await offlineDb.getPendingSubmissions();

        expect(remaining).toEqual([second]);
    });

    it('replaces an earlier queued submission for the same url in place', async () => {
        await offlineDb.queuePendingSubmission(
            '/writing/1/attempts',
            '{"a":1}',
        );
        await offlineDb.queuePendingSubmission(
            '/writing/2/attempts',
            '{"a":2}',
        );
        await offlineDb.queuePendingSubmission(
            '/writing/1/attempts',
            '{"a":3}',
        );

        const pending = await offlineDb.getPendingSubmissions();

        expect(
            pending.map((submission) => [submission.url, submission.body]),
        ).toEqual([
            ['/writing/1/attempts', '{"a":3}'],
            ['/writing/2/attempts', '{"a":2}'],
        ]);
    });

    it('counts the queued submissions', async () => {
        await offlineDb.queuePendingSubmission(
            '/writing/1/attempts',
            '{"a":1}',
        );
        await offlineDb.queuePendingSubmission(
            '/writing/2/attempts',
            '{"a":2}',
        );

        expect(await offlineDb.countPendingSubmissions()).toBe(2);
    });

    it('clears every queued submission', async () => {
        await offlineDb.queuePendingSubmission(
            '/writing/1/attempts',
            '{"a":1}',
        );

        await offlineDb.clearPendingSubmissions();

        expect(await offlineDb.getPendingSubmissions()).toEqual([]);
    });

    it('keeps submissions queued before the url index existed', async () => {
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
            '/writing/1/attempts',
            '{"a":2}',
        );

        const pending = await offlineDb.getPendingSubmissions();

        expect(
            pending.map((submission) => [submission.url, submission.body]),
        ).toEqual([['/writing/1/attempts', '{"a":2}']]);
    });
});
