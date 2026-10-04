import { flushPromises, mount } from '@vue/test-utils';
import { afterEach, describe, expect, it } from 'vitest';
import { defineComponent, h } from 'vue';
import AppSpinner from '@/components/AppSpinner.vue';
import { Sheet } from '@/components/ui/sheet';
import { i18n, setLocale } from '@/i18n';
import AppSheetContent from './AppSheetContent.vue';

function mountSheet() {
    return mount(
        defineComponent({
            render: () =>
                h(Sheet, { open: true }, () =>
                    h(AppSheetContent, null, () => 'body'),
                ),
        }),
        { attachTo: document.body, global: { plugins: [i18n] } },
    );
}

describe('translated primitives', () => {
    afterEach(() => {
        setLocale('en');
        document.body.innerHTML = '';
    });

    it('labels the sheet close button in English', async () => {
        mountSheet();
        await flushPromises();

        expect(document.body.textContent).toContain('Close');
    });

    it('labels the sheet close button in Dutch', async () => {
        setLocale('nl');
        mountSheet();
        await flushPromises();

        expect(document.body.textContent).toContain('Sluiten');
        expect(document.body.textContent).not.toContain('Close');
    });

    it('labels the spinner in both languages', () => {
        const english = mount(AppSpinner, { global: { plugins: [i18n] } });

        expect(english.attributes('aria-label')).toBe('Loading');

        setLocale('nl');

        expect(
            mount(AppSpinner, {
                global: { plugins: [i18n] },
            }).attributes('aria-label'),
        ).toBe('Laden');
    });
});
