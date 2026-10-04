import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { i18n, setLocale } from '@/i18n';
import { useWebPush } from './useWebPush';

const { fetchJson } = vi.hoisted(() => ({ fetchJson: vi.fn() }));

vi.mock('@/lib/http', () => ({ fetchJson }));

vi.mock('@/routes/push-subscriptions', () => ({
    store: () => ({ url: '/push' }),
    destroy: () => ({ url: '/push' }),
}));

const unsubscribe = vi.fn();
const subscription = {
    endpoint: 'https://push.example/1',
    toJSON: () => ({ endpoint: 'https://push.example/1', keys: {} }),
    unsubscribe,
};

function stubBrowser(permission: NotificationPermission = 'granted') {
    vi.stubGlobal('PushManager', class {});
    vi.stubGlobal('Notification', {
        requestPermission: vi.fn().mockResolvedValue(permission),
    });
    Object.defineProperty(navigator, 'serviceWorker', {
        configurable: true,
        value: {
            ready: Promise.resolve({
                pushManager: {
                    subscribe: vi.fn().mockResolvedValue(subscription),
                    getSubscription: vi.fn().mockResolvedValue(subscription),
                },
            }),
        },
    });
}

beforeEach(() => {
    fetchJson.mockReset();
    unsubscribe.mockReset();
    vi.spyOn(console, 'error').mockImplementation(() => undefined);
});

afterEach(() => {
    vi.unstubAllGlobals();
    Reflect.deleteProperty(navigator, 'serviceWorker');
});

describe('web push errors', () => {
    it('says push is unsupported', async () => {
        const push = useWebPush('AAAA');

        expect(await push.subscribe()).toBe(false);
        expect(push.error.value).toBe(
            'Push notifications are not supported in this browser.',
        );
    });

    it('says when permission was refused', async () => {
        stubBrowser('denied');

        const push = useWebPush('AAAA');

        expect(await push.subscribe()).toBe(false);
        expect(push.error.value).toBe(
            'Notification permission was not granted.',
        );
    });

    it('rolls the subscription back when the server refuses it', async () => {
        stubBrowser();
        fetchJson.mockResolvedValue({ ok: false });

        const push = useWebPush('AAAA');

        expect(await push.subscribe()).toBe(false);
        expect(unsubscribe).toHaveBeenCalled();
        expect(push.error.value).toBe('Failed to save the push subscription.');
    });

    it('reports a subscribe that throws and a failed removal', async () => {
        stubBrowser();
        fetchJson.mockRejectedValue(new Error('offline'));

        const push = useWebPush('AAAA');

        expect(await push.subscribe()).toBe(false);
        expect(push.error.value).toBe(
            'Failed to subscribe to push notifications.',
        );

        fetchJson.mockResolvedValue({ ok: false });

        expect(await push.unsubscribe()).toBe(false);
        expect(push.error.value).toBe(
            'Failed to remove the push subscription.',
        );

        fetchJson.mockRejectedValue(new Error('offline'));

        expect(await push.unsubscribe()).toBe(false);
        expect(push.error.value).toBe(
            'Failed to unsubscribe from push notifications.',
        );
    });

    it('words the errors in the interface language', async () => {
        setLocale('nl');

        const push = useWebPush('AAAA');
        await push.subscribe();

        expect(push.error.value).toBe(i18n.global.t('push.unsupported'));
        expect(push.error.value).not.toBe(
            'Push notifications are not supported in this browser.',
        );
    });
});
