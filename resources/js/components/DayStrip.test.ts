import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';

vi.mock('@inertiajs/vue3', () => ({
    Link: { props: ['href'], template: '<a :href="href"><slot /></a>' },
}));

vi.mock('@/routes/review', () => ({
    index: () => ({ url: '/review', method: 'get' }),
}));

import DayStrip from './DayStrip.vue';

function strip(
    day: Partial<{
        words: number;
        goal: number;
        streak: number;
        due: number;
    }> = {},
) {
    return mount(DayStrip, {
        props: { day: { words: 4, goal: 10, streak: 3, due: 7, ...day } },
    });
}

describe('DayStrip', () => {
    it('shows the words of today against the goal', () => {
        const wrapper = strip();

        expect(wrapper.get('[data-testid="day-goal"]').text()).toBe(
            '4 of 10 words today',
        );
        expect(wrapper.get('[data-testid="progress-ring"]').text()).toBe(
            '4/10',
        );
    });

    it('says so, and fills the ring, once the goal is reached', () => {
        const wrapper = strip({ words: 12 });

        expect(wrapper.get('[data-testid="day-goal"]').text()).toBe(
            'Daily goal reached',
        );
        expect(wrapper.get('[data-testid="progress-ring"]').text()).toBe('✓');
    });

    it('shows the streak and links the reviews due to the review tab', () => {
        const wrapper = strip();

        expect(wrapper.get('[data-testid="day-streak"]').text()).toBe('3');
        expect(wrapper.get('[data-testid="day-due"]').text()).toBe('7');
        expect(wrapper.get('[data-testid="day-due"]').attributes('href')).toBe(
            '/review',
        );
    });

    it('mutes the streak at zero', () => {
        expect(
            strip({ streak: 0 }).get('[data-testid="day-streak"]').classes(),
        ).toContain('text-muted-foreground');
    });
});
