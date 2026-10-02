import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { clearPageCache, cachePage } from '@/lib/pageCache';
import InterfaceLocaleSwitcher from './InterfaceLocaleSwitcher.vue';

const inertia = vi.hoisted(() => ({
    props: {
        interfaceLocale: 'en',
        supportedLocales: ['en', 'nl'],
        auth: { user: { id: 1 } as unknown },
    },
    patch: vi.fn(),
}));

vi.mock('@inertiajs/vue3', () => ({
    usePage: () => ({ props: inertia.props }),
    router: { patch: inertia.patch },
}));

vi.mock('@/routes/interface-locale', () => ({
    update: () => ({ url: '/settings/interface-locale', method: 'patch' }),
}));

vi.mock('@/routes/interface-locale/guest', () => ({
    update: () => ({ url: '/locale', method: 'patch' }),
}));

vi.mock('@/lib/pageCache', () => ({
    clearPageCache: vi.fn(() => Promise.resolve()),
    cachePage: vi.fn(() => Promise.resolve()),
}));

beforeEach(() => {
    inertia.props.interfaceLocale = 'en';
    inertia.props.supportedLocales = ['en', 'nl'];
    inertia.props.auth = { user: { id: 1 } };
    inertia.patch.mockReset();
    vi.mocked(clearPageCache).mockClear();
    vi.mocked(cachePage).mockClear();
});

describe('interface locale switcher', () => {
    it('renders nothing while only one locale is supported', () => {
        inertia.props.supportedLocales = ['en'];

        const wrapper = mount(InterfaceLocaleSwitcher);

        expect(
            wrapper.find('[data-test="interface-locale-switcher"]').exists(),
        ).toBe(false);
    });

    it('labels each option in its own language and marks the current one', () => {
        const wrapper = mount(InterfaceLocaleSwitcher);
        const english = wrapper.get('[data-test="interface-locale-en"]');
        const dutch = wrapper.get('[data-test="interface-locale-nl"]');

        expect(english.text()).toBe('English');
        expect(dutch.text()).toBe('Nederlands');
        expect(dutch.attributes('lang')).toBe('nl');
        expect(english.attributes('aria-pressed')).toBe('true');
        expect(dutch.attributes('aria-pressed')).toBe('false');
    });

    it('does nothing when the current locale is chosen again', async () => {
        const wrapper = mount(InterfaceLocaleSwitcher);

        await wrapper.get('[data-test="interface-locale-en"]').trigger('click');

        expect(inertia.patch).not.toHaveBeenCalled();
    });

    it('patches the account route when signed in, then switches and clears the page cache', async () => {
        const wrapper = mount(InterfaceLocaleSwitcher);

        await wrapper.get('[data-test="interface-locale-nl"]').trigger('click');

        const [url, data, options] = inertia.patch.mock.calls[0];

        expect(url).toBe('/settings/interface-locale');
        expect(data).toEqual({ interface_locale: 'nl' });

        await options.onSuccess();

        expect(document.documentElement.lang).toBe('nl');
        expect(clearPageCache).toHaveBeenCalledOnce();
        expect(cachePage).toHaveBeenCalledOnce();

        await wrapper.vm.$nextTick();

        expect(wrapper.get('[data-test="interface-locale-nl"]').text()).toBe(
            'Nederlands',
        );
        expect(
            wrapper.find('[aria-label="Taal van de interface"]').exists(),
        ).toBe(true);
    });

    it('patches the guest route when signed out', async () => {
        inertia.props.auth = { user: null };

        const wrapper = mount(InterfaceLocaleSwitcher);

        await wrapper.get('[data-test="interface-locale-nl"]').trigger('click');

        expect(inertia.patch.mock.calls[0][0]).toBe('/locale');
    });
});
