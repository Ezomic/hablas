import { ref } from 'vue';
import { fetchJson } from '@/lib/http';
import { destroy, store } from '@/routes/push-subscriptions';

// Web push subscriptions require the VAPID public key as a Uint8Array, but
// browsers hand it to us (and the backend stores it) as URL-safe base64.
function urlBase64ToUint8Array(base64: string): Uint8Array<ArrayBuffer> {
    const padding = '='.repeat((4 - (base64.length % 4)) % 4);
    const normalized = (base64 + padding).replace(/-/g, '+').replace(/_/g, '/');
    const raw = window.atob(normalized);
    const bytes = new Uint8Array(raw.length);

    for (let i = 0; i < raw.length; i++) {
        bytes[i] = raw.charCodeAt(i);
    }

    return bytes;
}

function toSubscriptionPayload(subscription: PushSubscription) {
    const json = subscription.toJSON();

    return {
        endpoint: json.endpoint,
        keys: { p256dh: json.keys?.p256dh, auth: json.keys?.auth },
    };
}

/**
 * Subscribes/unsubscribes the current browser to Web Push, mirroring THI-230's
 * service worker (resources/sw-src/sw.ts) which already handles the
 * `push`/`notificationclick` events this activates.
 */
export function useWebPush(vapidPublicKey: string) {
    const isSupported = 'serviceWorker' in navigator && 'PushManager' in window;
    const isSubscribing = ref(false);
    const error = ref<string | null>(null);

    async function subscribe(): Promise<boolean> {
        error.value = null;

        if (!isSupported) {
            error.value =
                'Push notifications are not supported in this browser.';

            return false;
        }

        isSubscribing.value = true;

        try {
            const permission = await Notification.requestPermission();

            if (permission !== 'granted') {
                error.value = 'Notification permission was not granted.';

                return false;
            }

            const registration = await navigator.serviceWorker.ready;
            const subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
            });

            const response = await fetchJson(
                store().url,
                'POST',
                JSON.stringify(toSubscriptionPayload(subscription)),
            );

            if (!response.ok) {
                // The browser subscription itself already succeeded — leaving
                // it in place with no server-side record would orphan it, so
                // roll it back rather than silently drifting out of sync.
                await subscription.unsubscribe();
                error.value = 'Failed to save the push subscription.';

                return false;
            }

            return true;
        } catch (caughtError) {
            console.error(
                'Failed to subscribe to push notifications:',
                caughtError,
            );
            error.value = 'Failed to subscribe to push notifications.';

            return false;
        } finally {
            isSubscribing.value = false;
        }
    }

    async function unsubscribe(): Promise<boolean> {
        error.value = null;

        if (!isSupported) {
            return false;
        }

        isSubscribing.value = true;

        try {
            const registration = await navigator.serviceWorker.ready;
            const subscription =
                await registration.pushManager.getSubscription();

            if (subscription) {
                const endpoint = subscription.endpoint;
                await subscription.unsubscribe();

                const response = await fetchJson(
                    destroy().url,
                    'DELETE',
                    JSON.stringify({ endpoint }),
                );

                if (!response.ok) {
                    error.value = 'Failed to remove the push subscription.';

                    return false;
                }
            }

            return true;
        } catch (caughtError) {
            console.error(
                'Failed to unsubscribe from push notifications:',
                caughtError,
            );
            error.value = 'Failed to unsubscribe from push notifications.';

            return false;
        } finally {
            isSubscribing.value = false;
        }
    }

    return { isSupported, isSubscribing, error, subscribe, unsubscribe };
}
