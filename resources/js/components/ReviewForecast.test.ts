import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import { setLocale } from '@/i18n';
import type { ReviewForecast as Forecast } from '@/types/forecast';
import ReviewForecast from './ReviewForecast.vue';

function forecast(cards: number[], newWaiting = 0): Forecast {
    return {
        days: Array.from({ length: 14 }, (_, offset) => ({
            date: `2026-10-${String(offset + 1).padStart(2, '0')}`,
            cards: cards[offset] ?? 0,
        })),
        newWaiting,
    };
}

function mountForecast(cards: number[], newWaiting = 0, weakSpots = 0) {
    return mount(ReviewForecast, {
        props: { forecast: forecast(cards, newWaiting), weakSpots },
    });
}

function barHeights(wrapper: ReturnType<typeof mountForecast>): string[] {
    return wrapper
        .findAll('[data-test="forecast-bar"]')
        .map((bar) => (bar.element as HTMLElement).style.height);
}

describe('review forecast', () => {
    it('renders nothing when nothing is scheduled, waiting or set aside', () => {
        expect(mountForecast([]).text()).toBe('');
    });

    it('totals the cards due over the fourteen days', () => {
        expect(mountForecast([3, 0, 5]).text()).toContain(
            '8 cards due in the next 14 days',
        );
        expect(mountForecast([0, 1]).text()).toContain(
            '1 card due in the next 14 days',
        );
    });

    it('scales each bar against the busiest day', () => {
        expect(barHeights(mountForecast([2, 0, 4, 1]))).toEqual([
            '50%',
            '0%',
            '100%',
            '25%',
            ...Array(10).fill('0%'),
        ]);
    });

    it('labels the busiest bar with its count, and no other', () => {
        const labels = mountForecast([2, 0, 4, 1]).findAll(
            '[data-test="forecast-peak"]',
        );

        expect(labels).toHaveLength(1);
        expect(labels[0].text()).toBe('4');
    });

    it('gives screen readers every day in words, today first', () => {
        const days = mountForecast([3, 1])
            .findAll('li .sr-only')
            .map((day) => day.text());

        expect(days).toHaveLength(14);
        expect(days[0]).toBe('Today: 3 cards');
        expect(days[1]).toBe('Fri 2 Oct: 1 card');
        expect(days[13]).toBe('Wed 14 Oct: 0 cards');
    });

    it('reads out today until another day is pointed at', async () => {
        const wrapper = mountForecast([3, 1, 7]);
        const readout = () => wrapper.get('[data-test="forecast-readout"]');

        expect(readout().text()).toBe('Today: 3 cards');

        await wrapper.findAll('li')[2].trigger('pointerenter');
        expect(readout().text()).toBe('Sat 3 Oct: 7 cards');

        await wrapper.get('ol').trigger('pointerleave');
        expect(readout().text()).toBe('Today: 3 cards');
    });

    it('steps through the days with the arrow keys', async () => {
        const wrapper = mountForecast([3, 1, 7]);
        const chart = wrapper.get('ol');
        const readout = () => wrapper.get('[data-test="forecast-readout"]');

        await chart.trigger('keydown', { key: 'ArrowRight' });
        expect(readout().text()).toBe('Fri 2 Oct: 1 card');

        await chart.trigger('keydown', { key: 'ArrowLeft' });
        await chart.trigger('keydown', { key: 'ArrowLeft' });
        expect(readout().text()).toBe('Today: 3 cards');

        await chart.trigger('keydown', { key: 'End' });
        expect(readout().text()).toBe('Wed 14 Oct: 0 cards');

        await chart.trigger('keydown', { key: 'ArrowRight' });
        expect(readout().text()).toBe('Wed 14 Oct: 0 cards');

        await chart.trigger('keydown', { key: 'Home' });
        expect(readout().text()).toBe('Today: 3 cards');
    });

    it('adds the weak spots and new cards that no due date covers', () => {
        expect(mountForecast([2], 10, 3).text()).toContain(
            'Plus 3 weak spots to clear and 10 new cards waiting.',
        );
        expect(mountForecast([2], 1).text()).toContain(
            'Plus 1 new card waiting.',
        );
        expect(mountForecast([2], 0, 1).text()).toContain(
            'Plus 1 weak spot to clear.',
        );
        expect(mountForecast([2]).text()).not.toContain('Plus');
    });

    it('skips the chart when only new cards and weak spots are waiting', () => {
        const wrapper = mountForecast([], 10, 3);

        expect(wrapper.text()).toContain('No cards due in the next 14 days');
        expect(wrapper.text()).toContain(
            '3 weak spots to clear and 10 new cards waiting.',
        );
        expect(wrapper.text()).not.toContain('Plus');
        expect(wrapper.find('ol').exists()).toBe(false);
    });

    it('names the days and counts the cards in the interface language', () => {
        setLocale('nl');

        const wrapper = mountForecast([3, 0, 5], 2, 1);

        expect(wrapper.text()).toContain(
            '8 kaarten te herhalen in de komende 14 dagen',
        );
        expect(wrapper.text()).toContain(
            'Daarnaast 1 zwak punt om weg te werken en 2 nieuwe kaarten wachten.',
        );
        expect(wrapper.text()).toContain('Vandaag: 3 kaarten');
        expect(wrapper.text()).toContain('vr 2 okt: 0 kaarten');
    });
});
