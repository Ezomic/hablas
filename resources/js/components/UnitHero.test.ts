import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import UnitHero from './UnitHero.vue';

const progress = {
    percent: 100,
    known: 10,
    total: 10,
    stars: 3,
    words: [{ id: 1, state: 'solid' }],
    level: { code: 'A1', known: 10, total: 80, percent: 12 },
};

describe('UnitHero', () => {
    it('says Start before the unit is started and Continue after', () => {
        const fresh = mount(UnitHero, {
            props: {
                progress,
                percent: 40,
                skills: { total: 4, mastered: 1 },
                canContinue: true,
                started: false,
                busy: false,
            },
        });
        const going = mount(UnitHero, {
            props: {
                progress,
                percent: 40,
                skills: { total: 4, mastered: 1 },
                canContinue: true,
                started: true,
                busy: false,
            },
        });

        expect(fresh.find('[data-testid="continue-button"]').text()).toBe(
            'Start',
        );
        expect(going.find('[data-testid="continue-button"]').text()).toBe(
            'Continue',
        );
    });

    it('fills the ring and earns the stars', () => {
        const wrapper = mount(UnitHero, {
            props: {
                progress,
                percent: 40,
                skills: { total: 4, mastered: 1 },
                canContinue: false,
                started: true,
                busy: false,
            },
        });

        expect(
            wrapper
                .find('[data-testid="progress-ring"]')
                .attributes('aria-label'),
        ).toBe('40% of this unit done');
        expect(
            wrapper.find('[data-testid="star-row"]').attributes('aria-label'),
        ).toBe('3 stars');
        expect(wrapper.find('[data-testid="continue-button"]').exists()).toBe(
            false,
        );
    });

    it('shows a gem for every word with its state', () => {
        const wrapper = mount(UnitHero, {
            props: {
                progress,
                percent: 40,
                skills: { total: 4, mastered: 1 },
                canContinue: false,
                started: true,
                busy: false,
            },
        });

        expect(
            wrapper
                .find('[data-testid="word-gems"] [data-state="solid"]')
                .exists(),
        ).toBe(true);
    });
});
