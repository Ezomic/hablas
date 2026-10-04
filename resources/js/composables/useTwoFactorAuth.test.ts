import { beforeEach, describe, expect, it, vi } from 'vitest';
import { i18n, setLocale } from '@/i18n';
import { useTwoFactorAuth } from './useTwoFactorAuth';

const { submit } = vi.hoisted(() => ({ submit: vi.fn() }));

vi.mock('@inertiajs/vue3', () => ({
    useHttp: () => ({ submit }),
}));

vi.mock('@/routes/two-factor', () => ({
    qrCode: () => ({ url: '/qr' }),
    secretKey: () => ({ url: '/secret' }),
    recoveryCodes: () => ({ url: '/codes' }),
}));

beforeEach(() => {
    submit.mockReset();
    useTwoFactorAuth().clearTwoFactorAuthData();
});

describe('two-factor setup errors', () => {
    it('keeps the English error text', async () => {
        submit.mockRejectedValue(new Error('down'));

        const auth = useTwoFactorAuth();
        await auth.fetchSetupData();

        expect(auth.errors.value).toEqual([
            'Failed to fetch QR code',
            'Failed to fetch a setup key',
        ]);

        await auth.fetchRecoveryCodes();

        expect(auth.errors.value).toEqual(['Failed to fetch recovery codes']);
    });

    it('words the errors in the interface language', async () => {
        setLocale('nl');
        submit.mockRejectedValue(new Error('down'));

        const auth = useTwoFactorAuth();
        await auth.fetchRecoveryCodes();

        expect(auth.errors.value).toEqual([
            i18n.global.t('twoFactorSetup.errors.recoveryCodes'),
        ]);
    });

    it('loads the QR code and key when the requests succeed', async () => {
        submit
            .mockResolvedValueOnce({ svg: '<svg />', url: 'otpauth://x' })
            .mockResolvedValueOnce({ secretKey: 'ABCD' });

        const auth = useTwoFactorAuth();
        await auth.fetchSetupData();

        expect(auth.hasSetupData.value).toBe(true);
        expect(auth.errors.value).toEqual([]);
    });
});
