import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { i18n, setLocale } from '@/i18n';
import type { LessonState, UnitLessonOverview } from '@/types/lesson';
import UnitLessonList from './UnitLessonList.vue';

vi.mock('@inertiajs/vue3', () => ({
    router: { post: vi.fn() },
}));

vi.mock('@/routes/lessons/runs', () => ({
    store: () => ({ url: '/runs' }),
}));

function row(
    stage: string,
    state: LessonState,
    bestAccuracy: number | null = null,
) {
    return {
        stage,
        title: `Lesson ${stage}`,
        position: 1,
        lessonId: 1,
        state,
        bestAccuracy,
        mastered: false,
        missed: 0,
    };
}

function mountList(
    lessons: UnitLessonOverview['lessons'],
    skipped = { listening: 0, speaking: 0 },
    isHeldBack = false,
) {
    return mount(UnitLessonList, {
        props: {
            unitId: 1,
            isHeldBack,
            overview: {
                lessons,
                mastery: { mastered: 3, total: 10 },
                unlock: {
                    lessonsMastered: 3,
                    lessonsTotal: 4,
                    skillsMastered: 1,
                    skillsTotal: 4,
                    met: false,
                },
                skipped,
                contentPending: true,
                canTestOut: false,
                remediation: null,
            },
        },
        global: { stubs: { RemediationActions: true } },
    });
}

describe('unit lesson list', () => {
    it('keeps the English text of every row state', () => {
        const wrapper = mountList([
            row('words', 'completed', 0.82),
            row('words-2', 'completed'),
            row('sentences', 'in_progress'),
            row('dialogue', 'available'),
            row('grammar', 'locked'),
            row('check', 'locked'),
            row('extra', 'opens_tomorrow'),
            row('later', 'coming'),
        ]);
        const text = (stage: string) =>
            wrapper.get(`[data-testid="lesson-${stage}"]`).text();

        expect(text('words')).toContain('Done, 82% right first time');
        expect(text('words')).toContain('Replay');
        expect(text('words-2')).toContain('Done');
        expect(text('sentences')).toContain('In progress');
        expect(text('sentences')).toContain('Continue');
        expect(text('dialogue')).toContain('Ready');
        expect(text('dialogue')).toContain('Start');
        expect(text('grammar')).toContain('Finish the lesson before it first');
        expect(text('check')).toContain('Opens after the lessons before it');
        expect(text('extra')).toContain('Opens tomorrow');
        expect(text('later')).toContain('Coming soon');
        expect(text('later')).toContain('Soon');
        expect(wrapper.get('[data-testid="unlock-lessons"]').text()).toBe(
            'Lessons at 100% first time: 3 of 4',
        );
        expect(wrapper.get('[data-testid="unlock-skills"]').text()).toBe(
            'Skills mastered by their final test: 1 of 4',
        );
    });

    it('says which exercises were skipped', () => {
        const both = mountList([], { listening: 2, speaking: 3 });
        const listening = mountList([], { listening: 2, speaking: 0 });
        const speaking = mountList([], { listening: 0, speaking: 3 });
        const none = mountList([]);

        expect(both.text()).toContain(
            'Skipped listening in 2 and speaking in 3 exercises.',
        );
        expect(listening.text()).toContain('Skipped listening in 2 exercises.');
        expect(speaking.text()).toContain('Skipped speaking in 3 exercises.');
        expect(none.text()).not.toContain('Skipped');
    });

    it('shows the held-back and pending notes in English', () => {
        const text = mountList([], undefined, true).text();

        expect(text).toContain(
            'Clear your reviews first, then start this unit.',
        );
        expect(text).toContain(
            'Sentence and grammar lessons for this unit are coming.',
        );
    });

    it('renders every row state in Dutch', () => {
        setLocale('nl');

        const wrapper = mountList(
            [row('words', 'completed', 0.82), row('later', 'coming')],
            { listening: 2, speaking: 3 },
        );

        expect(wrapper.get('[data-testid="lesson-words"]').text()).toContain(
            i18n.global.t('unitLessons.status.doneBest', { percent: 82 }),
        );
        expect(wrapper.get('[data-testid="lesson-later"]').text()).toContain(
            i18n.global.t('unitLessons.soon'),
        );
        expect(wrapper.get('[data-testid="unlock-lessons"]').text()).toBe(
            i18n.global.t('unitLessons.unlock.lessons', {
                mastered: 3,
                total: 4,
            }),
        );
        expect(wrapper.text()).toContain(
            'Overgeslagen: 2 luisteroefeningen en 3 spreekoefeningen.',
        );
    });

    it('pluralises both halves of the skipped note in Dutch', () => {
        setLocale('nl');

        const one = mountList([], { listening: 1, speaking: 1 });

        expect(one.text()).toContain(
            'Overgeslagen: 1 luisteroefening en 1 spreekoefening.',
        );
    });

    it('uses the singular for a single skipped exercise in Dutch', () => {
        setLocale('nl');

        const wrapper = mountList([row('words', 'completed', 0.82)], {
            listening: 1,
            speaking: 0,
        });

        expect(wrapper.text()).toContain('1 luisteroefening.');
    });

    it('offers to redo only the missed exercises of a lesson that is not mastered', () => {
        const wrapper = mountList([
            { ...row('words', 'completed', 0.9), missed: 2 },
            { ...row('sentences', 'completed', 1), mastered: true },
        ]);

        expect(wrapper.get('[data-testid="lesson-words"]').text()).toContain(
            'Redo the 2 missed',
        );
        expect(
            wrapper.get('[data-testid="lesson-sentences"]').text(),
        ).toContain('Replay');
    });
});
