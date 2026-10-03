import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import InterfaceLocaleMenuItems from './InterfaceLocaleMenuItems.vue';

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

vi.mock('@/components/ui/dropdown-menu', () => ({
    DropdownMenuLabel: { template: '<div><slot /></div>' },
    DropdownMenuSeparator: { template: '<hr />' },
    DropdownMenuRadioGroup: {
        name: 'DropdownMenuRadioGroup',
        props: ['modelValue'],
        emits: ['update:modelValue'],
        template: '<div role="group"><slot /></div>',
    },
    DropdownMenuRadioItem: {
        props: ['value'],
        template: '<div role="menuitemradio"><slot /></div>',
    },
}));

beforeEach(() => {
    inertia.props.interfaceLocale = 'en';
    inertia.props.supportedLocales = ['en', 'nl'];
    inertia.patch.mockReset();
});

describe('interface locale menu items', () => {
    it('renders nothing while only one locale is supported', () => {
        inertia.props.supportedLocales = ['en'];

        expect(mount(InterfaceLocaleMenuItems).html()).toBe('<!--v-if-->');
    });

    it('offers each locale as a menu item labelled in its own language', () => {
        const wrapper = mount(InterfaceLocaleMenuItems);
        const items = wrapper.findAll('[role="menuitemradio"]');

        expect(items.map((item) => item.text())).toEqual([
            'English',
            'Nederlands',
        ]);
        expect(items.map((item) => item.attributes('lang'))).toEqual([
            'en',
            'nl',
        ]);
        expect(wrapper.text()).toContain('Interface language');
    });

    it('patches the chosen locale when the group changes', () => {
        const wrapper = mount(InterfaceLocaleMenuItems);

        wrapper
            .findComponent({ name: 'DropdownMenuRadioGroup' })
            .vm.$emit('update:modelValue', 'nl');

        expect(inertia.patch.mock.calls[0][1]).toEqual({
            interface_locale: 'nl',
        });
    });
});
