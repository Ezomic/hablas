import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { clearOfflineData } from '@/composables/useOfflineSync';
import UserMenuContent from './UserMenuContent.vue';

vi.mock('@inertiajs/vue3', () => ({
    Link: {
        name: 'Link',
        props: ['href'],
        emits: ['success'],
        template: '<a><slot /></a>',
    },
    router: { flushAll: vi.fn() },
    usePage: () => ({ props: { supportedLocales: ['en'] } }),
}));

vi.mock('@/components/ui/dropdown-menu', () => {
    const passthrough = { template: '<div><slot /></div>' };

    return {
        DropdownMenuGroup: passthrough,
        DropdownMenuItem: passthrough,
        DropdownMenuLabel: passthrough,
        DropdownMenuSeparator: passthrough,
    };
});

vi.mock('@/components/UserInfo.vue', () => ({
    default: { template: '<div />' },
}));

vi.mock('@/composables/useOfflineSync', () => ({
    clearOfflineData: vi.fn(),
}));

function logoutLink() {
    const wrapper = mount(UserMenuContent, {
        props: {
            user: {
                id: 1,
                name: 'Test User',
                email: 'test@example.com',
            } as never,
        },
    });

    return wrapper
        .findAllComponents({ name: 'Link' })
        .find((link) => link.attributes('data-test') === 'logout-button')!;
}

beforeEach(() => {
    vi.mocked(clearOfflineData).mockClear();
});

describe('user menu', () => {
    it('clears offline data once the logout succeeds', async () => {
        const link = logoutLink();

        await link.trigger('click');
        expect(clearOfflineData).not.toHaveBeenCalled();

        link.vm.$emit('success');
        expect(clearOfflineData).toHaveBeenCalledOnce();
    });
});
