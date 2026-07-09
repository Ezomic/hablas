/// <reference lib="webworker" />

import { cleanupOutdatedCaches, precacheAndRoute } from 'workbox-precaching';
import { registerRoute } from 'workbox-routing';
import { NetworkFirst } from 'workbox-strategies';

declare const self: ServiceWorkerGlobalScope;

precacheAndRoute(self.__WB_MANIFEST);
cleanupOutdatedCaches();

// Cache the last-seen render of each visited page so it can be revisited
// offline, falling back to the network first since page data should stay
// fresh whenever connectivity is available. Mirrors the previous generateSW
// `runtimeCaching` rule for navigation requests (see vite.config.ts history).
registerRoute(
    ({ request }) => request.mode === 'navigate',
    new NetworkFirst({
        cacheName: 'pages',
        networkTimeoutSeconds: 3,
    }),
);

// Matches the wire shape laravel-notification-channels/webpush's
// WebPushMessage::toArray() sends (title/body/icon/data/etc, mirroring
// ServiceWorkerRegistration.showNotification's own options almost 1:1) —
// `data.url` is where DailyDigestNotification::toWebPush() puts the
// notification's target link.
interface PushPayload {
    title: string;
    body?: string;
    icon?: string;
    data?: { url?: string };
}

self.addEventListener('push', (event) => {
    if (!event.data) {
        return;
    }

    const payload = event.data.json() as PushPayload;

    event.waitUntil(
        self.registration.showNotification(payload.title, {
            body: payload.body,
            icon: payload.icon,
            data: { url: payload.data?.url ?? '/dashboard' },
        }),
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const url =
        (event.notification.data as { url?: string } | undefined)?.url ??
        '/dashboard';
    // clients.matchAll() always returns absolute client.url values, while the
    // notification payload's url is a relative path — resolve both against
    // the SW's own origin before comparing, or an already-open tab is never
    // matched and a duplicate tab opens on every click.
    const absoluteUrl = new URL(url, self.location.origin).href;

    event.waitUntil(
        self.clients
            .matchAll({ type: 'window', includeUncontrolled: true })
            .then((clientList) => {
                for (const client of clientList) {
                    if (client.url === absoluteUrl && 'focus' in client) {
                        return client.focus();
                    }
                }

                return self.clients.openWindow(url);
            }),
    );
});
