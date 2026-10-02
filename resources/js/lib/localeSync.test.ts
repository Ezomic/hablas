import { beforeEach, describe, expect, it, vi } from 'vitest';
import { i18n } from '@/i18n';
import { initializeLocaleSync } from './localeSync';

const inertia = vi.hoisted(() => ({
    handler: undefined as ((event: unknown) => void) | undefined,
}));

vi.mock('@inertiajs/vue3', () => ({
    router: {
        on: (_event: string, handler: (event: unknown) => void) => {
            inertia.handler = handler;
        },
    },
}));

function navigateTo(interfaceLocale: string | undefined) {
    inertia.handler?.({ detail: { page: { props: { interfaceLocale } } } });
}

describe('locale sync', () => {
    beforeEach(() => {
        initializeLocaleSync();
    });

    it('follows the shared interfaceLocale prop after a visit', () => {
        navigateTo('nl');
        expect(i18n.global.locale.value).toBe('nl');

        navigateTo('en');
        expect(i18n.global.locale.value).toBe('en');
    });

    it('leaves the locale alone when the prop is missing', () => {
        navigateTo('nl');
        navigateTo(undefined);

        expect(i18n.global.locale.value).toBe('nl');
    });
});
