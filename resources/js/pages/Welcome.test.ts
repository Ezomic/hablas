import { mount } from '@vue/test-utils';
import { afterEach, describe, expect, it, vi } from 'vitest';
import { setLocale } from '@/i18n';
import Welcome from './Welcome.vue';

const inertia = vi.hoisted(() => ({
    props: {
        interfaceLocale: 'en',
        supportedLocales: ['en'],
        auth: { user: null as unknown },
    },
}));

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    Link: { props: ['href'], template: '<a><slot /></a>' },
    router: { patch: vi.fn() },
    usePage: () => ({ props: inertia.props }),
}));

vi.mock('@/routes', () => ({
    dashboard: () => ({ url: '/dashboard' }),
    login: () => ({ url: '/login' }),
    register: () => ({ url: '/register' }),
}));

vi.mock('@/routes/interface-locale', () => ({
    update: () => ({ url: '/settings/interface-locale' }),
}));

vi.mock('@/routes/interface-locale/guest', () => ({
    update: () => ({ url: '/locale' }),
}));

function mountPage() {
    return mount(Welcome, {
        global: { mocks: { $page: { props: inertia.props } } },
    });
}

afterEach(() => {
    inertia.props.supportedLocales = ['en'];
});

describe('Welcome', () => {
    it('reads in English by default and hides the switcher while only English is supported', () => {
        const wrapper = mountPage();

        expect(wrapper.text()).toContain('Learn Spanish the way the research');
        expect(wrapper.text()).toContain(
            'Built on the CEFR and FSI pacing data.',
        );
        expect(
            wrapper.find('[data-test="interface-locale-switcher"]').exists(),
        ).toBe(false);
    });

    it('shows the switcher once Dutch is supported', () => {
        inertia.props.supportedLocales = ['en', 'nl'];

        expect(
            mountPage()
                .find('[data-test="interface-locale-switcher"]')
                .exists(),
        ).toBe(true);
    });

    it('renders in Dutch, links kept, with no em-dash in the Dutch copy', () => {
        setLocale('nl');
        const wrapper = mountPage();

        expect(wrapper.text()).toContain('Leer Spaans op de manier');
        expect(wrapper.text()).toContain(
            'Gebouwd op de CEFR- en FSI-tempogegevens.',
        );
        expect(wrapper.findAll('a[target="_blank"]')).toHaveLength(2);
        expect(wrapper.text()).not.toContain('—');
    });
});
