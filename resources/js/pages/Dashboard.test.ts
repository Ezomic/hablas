import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { setLocale } from '@/i18n';
import Dashboard from './Dashboard.vue';

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    setLayoutProps: vi.fn(),
    router: { post: vi.fn() },
    useForm: () => ({ errors: {}, processing: false, post: vi.fn() }),
    Link: {
        props: ['href'],
        template:
            '<a :href="typeof href === \'string\' ? href : href.url"><slot /></a>',
    },
}));

const { url } = vi.hoisted(() => ({
    url: (path: string) => () => ({ url: path, method: 'get' }),
}));

vi.mock('@/routes', () => ({ dashboard: url('/dashboard') }));
vi.mock('@/routes/language', () => ({ activate: url('/lang') }));
vi.mock('@/routes/lesson-runs', () => ({ show: url('/run') }));
vi.mock('@/routes/lessons/runs', () => ({ store: url('/start') }));
vi.mock('@/routes/placement', () => ({ results: url('/placement') }));
vi.mock('@/routes/placement/skills', () => ({ store: url('/skill') }));
vi.mock('@/routes/progress/share', () => ({ show: url('/share') }));
vi.mock('@/routes/review', () => ({ index: url('/review') }));
vi.mock('@/routes/review/weak-spots', () => ({ index: url('/weak') }));
vi.mock('@/routes/units', () => ({ show: url('/unit') }));

const props = {
    language: { code: 'es', name: 'Spanish' },
    blendedLevel: 'A2',
    blendedLevelCeiling: ['reading', 'writing'],
    skillLevels: { reading: 'A1' },
    dueReviewCount: 1,
    weakSpotReviewCount: 3,
    streak: {
        currentLength: 1,
        longestLength: 5,
        freezeDaysRemaining: 2,
        daysUntilNextFreezeDay: 3,
    },
    nextUnit: {
        id: 4,
        title: 'At the airport',
        taskDescription: 'Check in',
        lesson: {
            lessonId: 9,
            title: 'Meet',
            number: 2,
            count: 5,
            resumes: false,
            remediation: null,
            missing: 0,
        },
    },
};

describe('dashboard', () => {
    it('reads in English', () => {
        const text = mount(Dashboard, { props }).text();

        expect(text).toContain(
            'Your overall level is held by reading and writing.',
        );
        expect(text).toContain('1 card due');
        expect(text).toContain('3 cards to revisit');
        expect(text).toContain('1 day');
        expect(text).toContain('Longest streak: 5 days');
        expect(text).toContain('Earn one back in 3 more active days.');
        expect(text).toContain('Start lesson 2 of 5: Meet');
    });

    it('reads in Dutch with the plural forms and list conjunction of the interface language', () => {
        setLocale('nl');

        const text = mount(Dashboard, { props }).text();

        expect(text).toContain(
            'Je totaalniveau wordt tegengehouden door lezen en schrijven.',
        );
        expect(text).toContain('1 kaart te herhalen');
        expect(text).toContain('3 kaarten om opnieuw te bekijken');
        expect(text).toContain('Langste streak: 5 dagen');
        expect(text).toContain('Begin met les 2 van 5: Meet');
    });

    it('re-renders when the locale switches', async () => {
        const wrapper = mount(Dashboard, { props });

        expect(wrapper.text()).toContain('Start review');

        setLocale('nl');
        await wrapper.vm.$nextTick();

        expect(wrapper.text()).toContain('Begin met herhalen');
    });

    it('offers each activatable language and posts to its own route', async () => {
        const { router } = await import('@inertiajs/vue3');
        const wrapper = mount(Dashboard, {
            props: {
                ...props,
                activatableLanguages: [{ code: 'pt', name: 'Portuguese' }],
            },
        });

        expect(wrapper.text()).toContain('Ready to start Portuguese?');

        await wrapper
            .findAll('button')
            .find((button) => button.text() === 'Start learning Portuguese')
            ?.trigger('click');

        expect(router.post).toHaveBeenCalledWith('/lang');
    });

    it('shows no activation card without activatable languages', () => {
        expect(mount(Dashboard, { props }).text()).not.toContain(
            'Ready to start',
        );
    });
});
