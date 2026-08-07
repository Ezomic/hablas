import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { SidebarProvider } from '@/components/ui/sidebar';
import AppSidebar from './AppSidebar.vue';

vi.mock('@inertiajs/vue3', () => ({
    Link: {
        props: ['href'],
        template:
            '<a :href="typeof href === \'string\' ? href : href.url"><slot /></a>',
    },
    usePage: () => ({ url: '/dashboard' }),
}));

vi.mock('@/components/NavUser.vue', () => ({
    default: { template: '<div data-test="nav-user" />' },
}));

vi.mock('@/components/AppLogo.vue', () => ({
    default: { template: '<div data-test="app-logo" />' },
}));

function mountSidebar() {
    return mount(SidebarProvider, {
        slots: { default: AppSidebar },
        attachTo: document.body,
    });
}

function hrefs(wrapper: ReturnType<typeof mountSidebar>): string[] {
    return wrapper
        .findAll('a')
        .map((anchor) => anchor.attributes('href') ?? '');
}

describe('app sidebar', () => {
    it('groups the destinations under study and practice', () => {
        const text = mountSidebar().text();

        expect(text).toContain('Study');
        expect(text).toContain('Practice');
    });

    it('links every study surface', () => {
        const links = hrefs(mountSidebar());

        expect(links).toContain('/dashboard');
        expect(links).toContain('/review');
        expect(links).toContain('/review/weak-spots');
    });

    it('links every practice surface', () => {
        const links = hrefs(mountSidebar());

        expect(links).toContain('/shadowing');
        expect(links).toContain('/writing');
        expect(links).toContain('/scripted-prompts');
        expect(links).toContain('/pronunciation-drills');
        expect(links).toContain('/reflections');
    });

    it('labels each destination', () => {
        const text = mountSidebar().text();

        for (const label of [
            'Dashboard',
            'Review',
            'Weak spots',
            'Shadowing',
            'Writing',
            'Scripted prompts',
            'Pronunciation drills',
            'Weekly reflection',
        ]) {
            expect(text).toContain(label);
        }
    });

    it('carries no starter-kit links', () => {
        const wrapper = mountSidebar();

        expect(hrefs(wrapper).join(' ')).not.toMatch(
            /vue-starter-kit|laravel\.com\/docs/,
        );
        expect(wrapper.text()).not.toContain('Repository');
        expect(wrapper.text()).not.toContain('Documentation');
    });

    it('marks only the current destination active', () => {
        const wrapper = mountSidebar();

        const active = wrapper
            .findAll('[data-active="true"]')
            .map((el) => el.text().trim());

        expect(active).toEqual(['Dashboard']);
    });
});
