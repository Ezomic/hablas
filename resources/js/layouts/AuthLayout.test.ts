import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import AuthLayout from './AuthLayout.vue';

const inertia = vi.hoisted(() => ({
    props: {
        interfaceLocale: 'en',
        supportedLocales: ['en'],
        auth: { user: null as unknown },
    },
}));

vi.mock('@inertiajs/vue3', () => ({
    usePage: () => ({ props: inertia.props }),
    router: { patch: vi.fn() },
    Link: { template: '<a><slot /></a>' },
}));

vi.mock('@/routes', () => ({ home: () => ({ url: '/', method: 'get' }) }));

vi.mock('@/routes/interface-locale', () => ({
    update: () => ({ url: '/settings/interface-locale', method: 'patch' }),
}));

vi.mock('@/routes/interface-locale/guest', () => ({
    update: () => ({ url: '/locale', method: 'patch' }),
}));

beforeEach(() => {
    inertia.props.supportedLocales = ['en'];
});

describe('auth layout', () => {
    it('does not reserve a corner for the switcher while only one locale is supported', () => {
        const wrapper = mount(AuthLayout);

        expect(wrapper.find('.absolute').exists()).toBe(false);
        expect(
            wrapper.find('[data-test="interface-locale-switcher"]').exists(),
        ).toBe(false);
    });

    it('shows the switcher in the corner once a second locale is supported', () => {
        inertia.props.supportedLocales = ['en', 'nl'];

        const wrapper = mount(AuthLayout);

        expect(wrapper.find('.absolute').exists()).toBe(true);
        expect(
            wrapper.find('[data-test="interface-locale-switcher"]').exists(),
        ).toBe(true);
    });
});
