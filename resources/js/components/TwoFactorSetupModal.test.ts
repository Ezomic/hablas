import { flushPromises, mount } from '@vue/test-utils';
import { afterEach, describe, expect, it, vi } from 'vitest';
import { nextTick } from 'vue';
import { i18n, setLocale } from '@/i18n';
import TwoFactorSetupModal from './TwoFactorSetupModal.vue';

vi.mock('@inertiajs/vue3', () => ({
    Form: { render: () => null },
    useHttp: () => ({
        submit: vi.fn().mockResolvedValue({ svg: '<svg />', secretKey: 'KEY' }),
    }),
}));

vi.mock('@/routes/two-factor', () => ({
    confirm: { form: () => ({ action: '/confirm', method: 'post' }) },
    qrCode: () => ({ url: '/qr' }),
    secretKey: () => ({ url: '/secret' }),
    recoveryCodes: () => ({ url: '/codes' }),
}));

vi.mock('@/composables/useAppearance', () => ({
    useAppearance: () => ({ resolvedAppearance: { value: 'light' } }),
}));

function mountModal(twoFactorEnabled: boolean) {
    return mount(TwoFactorSetupModal, {
        props: {
            requiresConfirmation: true,
            twoFactorEnabled,
            isOpen: true,
        },
        attachTo: document.body,
    });
}

afterEach(() => {
    document.body.innerHTML = '';
});

describe('two-factor setup modal', () => {
    it('keeps the English setup text', async () => {
        mountModal(false);
        await flushPromises();

        expect(document.body.textContent).toContain(
            'Enable two-factor authentication',
        );
        expect(document.body.textContent).toContain(
            'or, enter the code manually',
        );
        expect(document.body.textContent).toContain('Continue');
    });

    it('shows the enabled copy with a Close button', async () => {
        mountModal(true);
        await flushPromises();

        expect(document.body.textContent).toContain(
            'Two-factor authentication enabled',
        );
        expect(document.body.textContent).toContain('Close');
    });

    it('switches to the interface language', async () => {
        mountModal(false);
        setLocale('nl');
        await nextTick();

        expect(document.body.textContent).toContain(
            i18n.global.t('twoFactorSetup.enableTitle'),
        );
        expect(document.body.textContent).toContain(
            i18n.global.t('twoFactorSetup.manualEntry'),
        );
        expect(document.body.textContent).not.toContain(
            'Enable two-factor authentication',
        );
    });
});
