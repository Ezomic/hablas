import { onMounted, onUnmounted, ref } from 'vue';
import { getCsrfToken } from '@/lib/csrf';
import {
    getPendingSubmissions,
    queuePendingSubmission,
    removePendingSubmission,
} from '@/lib/offlineDb';

export type SubmitResult =
    { queued: true } | { queued: false; response: Response };

function postJson(url: string, body: string): Promise<Response> {
    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-XSRF-TOKEN': getCsrfToken(),
        },
        body,
    });
}

// Web Locks isn't supported in every browser (or test environment) — fall
// back to running the callback unguarded rather than throwing.
async function withReplayLock(callback: () => Promise<void>): Promise<void> {
    if (!navigator.locks) {
        await callback();

        return;
    }

    await navigator.locks.request('hablas-offline-replay', callback);
}

/**
 * Submits a POST as JSON, falling back to an IndexedDB queue when offline
 * (or when the request fails outright) rather than losing the attempt. The
 * queue is replayed in order whenever the browser comes back online.
 */
export function useOfflineSync() {
    const isOnline = ref(navigator.onLine);

    async function replayQueue(): Promise<void> {
        // Guards against two tabs both reconnecting and racing to replay the
        // same queued rows before either has deleted them.
        await withReplayLock(async () => {
            const pending = await getPendingSubmissions();

            for (const submission of pending) {
                try {
                    const response = await postJson(
                        submission.url,
                        submission.body,
                    );

                    if (!response.ok) {
                        break;
                    }

                    await removePendingSubmission(submission.id);
                } catch {
                    // Still offline, or the request failed again — stop here
                    // rather than replaying out of order.
                    break;
                }
            }
        });
    }

    async function submitOrQueue(
        url: string,
        payload: unknown,
    ): Promise<SubmitResult> {
        const body = JSON.stringify(payload);

        if (!navigator.onLine) {
            await queuePendingSubmission(url, body);

            return { queued: true };
        }

        try {
            const response = await postJson(url, body);

            return { queued: false, response };
        } catch {
            await queuePendingSubmission(url, body);

            return { queued: true };
        }
    }

    function handleOnline() {
        isOnline.value = true;
        void replayQueue();
    }

    function handleOffline() {
        isOnline.value = false;
    }

    onMounted(() => {
        window.addEventListener('online', handleOnline);
        window.addEventListener('offline', handleOffline);

        if (isOnline.value) {
            void replayQueue();
        }
    });

    onUnmounted(() => {
        window.removeEventListener('online', handleOnline);
        window.removeEventListener('offline', handleOffline);
    });

    return { isOnline, submitOrQueue };
}
