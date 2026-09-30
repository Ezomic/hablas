import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import RetakeSkillButton from '@/components/RetakeSkillButton.vue';
import Results from './Results.vue';

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    Link: {
        props: ['href'],
        template: '<a :href="href"><slot /></a>',
    },
}));

vi.mock('@/routes', () => ({
    dashboard: () => ({ url: '/dashboard', method: 'get' }),
}));

vi.mock('@/routes/placement', () => ({
    index: () => ({ url: '/placement', method: 'get' }),
}));

const skills = ['reading', 'listening', 'speaking', 'writing'].map((skill) => ({
    skill,
    level: 'A2.2',
    items: [],
}));

function mountPage(
    openAttempt: { skill: string | null } | null,
    skipped = false,
) {
    return mount(Results, {
        props: {
            language: { code: 'es', name: 'Spanish' },
            result: {
                completedAt: null,
                blendedLevel: 'A2.2',
                skipped,
                skills,
            },
            retakeAvailableOn: {
                reading: null,
                listening: '2026-10-07',
                speaking: null,
                writing: null,
            },
            openAttempt,
        },
        global: { stubs: { RetakeSkillButton: true } },
    });
}

describe('placement results page', () => {
    it('offers a re-take for every skill, with its cooldown', () => {
        const buttons = mountPage(null).findAllComponents(RetakeSkillButton);

        expect(buttons.map((button) => button.props('skill'))).toEqual([
            'reading',
            'listening',
            'speaking',
            'writing',
        ]);
        expect(buttons[1].props('availableOn')).toBe('2026-10-07');
        expect(buttons[0].props('availableOn')).toBeNull();
    });

    it('sends the learner back to a re-take still in progress instead', () => {
        const wrapper = mountPage({ skill: 'speaking' });

        expect(wrapper.findAllComponents(RetakeSkillButton)).toHaveLength(0);
        expect(wrapper.text()).toContain(
            'Your speaking re-take is still open.',
        );
        expect(wrapper.get('a[href="/placement"]').text()).toBe('Continue');
    });

    it('links a skipped placement to the full test', () => {
        const wrapper = mountPage(null, true);

        expect(wrapper.get('a[href="/placement"]').text()).toBe(
            'Take the test',
        );
    });
});
