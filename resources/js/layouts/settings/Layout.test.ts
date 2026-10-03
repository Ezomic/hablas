import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import Layout from './Layout.vue';

const inertia = vi.hoisted(() => ({
    props: {
        interfaceLocale: 'en',
        supportedLocales: ['en'],
        auth: { user: { id: 1 } as unknown },
    },
}));

vi.mock('@inertiajs/vue3', () => ({
    usePage: () => ({ props: inertia.props }),
    router: { patch: vi.fn() },
    Link: {
        props: ['href'],
        template:
            '<a :href="typeof href === \'string\' ? href : href.url"><slot /></a>',
    },
}));

vi.mock('@/composables/useCurrentUrl', () => ({
    useCurrentUrl: () => ({ isCurrentOrParentUrl: () => false }),
}));

const { route } = vi.hoisted(() => ({
    route: (url: string) => () => ({ url, method: 'get' }),
}));

vi.mock('@/routes', () => ({ credits: () => ({ url: '/credits' }) }));
vi.mock('@/routes/appearance', () => ({ edit: route('/settings/appearance') }));
vi.mock('@/routes/learning', () => ({ edit: route('/settings/learning') }));
vi.mock('@/routes/profile', () => ({ edit: route('/settings/profile') }));
vi.mock('@/routes/security', () => ({ edit: route('/settings/security') }));
vi.mock('@/routes/interface-locale', () => ({
    update: route('/settings/interface-locale'),
}));
vi.mock('@/routes/interface-locale/guest', () => ({
    update: route('/locale'),
}));

beforeEach(() => {
    inertia.props.supportedLocales = ['en'];
});

describe('settings layout', () => {
    it('has no interface language item while only one locale is supported', () => {
        expect(mount(Layout).text()).not.toContain('Interface language');
    });

    it('links the interface language item to its section on the profile page', () => {
        inertia.props.supportedLocales = ['en', 'nl'];

        const link = mount(Layout)
            .findAll('a')
            .find((anchor) => anchor.text() === 'Interface language');

        expect(link?.attributes('href')).toBe(
            '/settings/profile#interface-language',
        );
    });
});
