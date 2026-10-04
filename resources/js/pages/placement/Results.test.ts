import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { nextTick } from 'vue';
import RetakeSkillButton from '@/components/RetakeSkillButton.vue';
import { i18n, setLocale } from '@/i18n';
import Results from './Results.vue';

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    setLayoutProps: vi.fn(),
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
    results: () => ({ url: '/placement/results', method: 'get' }),
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

    it('shows the breakdown labels in the interface language', async () => {
        const wrapper = mount(Results, {
            props: {
                language: { code: 'es', name: 'Spanish' },
                result: {
                    completedAt: null,
                    blendedLevel: 'A1',
                    skipped: false,
                    skills: [
                        {
                            skill: 'reading',
                            level: 'A1',
                            items: [
                                {
                                    prompt: '¿Cómo te llamas?',
                                    yourAnswer: null,
                                    correctAnswer: 'What is your name?',
                                    status: 'dont_know',
                                },
                            ],
                        },
                    ],
                },
                retakeAvailableOn: {},
                openAttempt: null,
            },
            global: { stubs: { RetakeSkillButton: true } },
        });

        expect(wrapper.text()).toContain('Spanish placement results');
        expect(wrapper.text()).toContain('Question by question');
        expect(wrapper.text()).toContain("didn't know");
        expect(wrapper.find('[aria-label="Didn\'t know"]').exists()).toBe(true);

        setLocale('nl');

        await nextTick();
        expect(wrapper.text()).toContain(
            i18n.global.t('placement.results.questionByQuestion'),
        );
        expect(wrapper.text()).toContain('What is your name?');
        expect(wrapper.text()).toContain('¿Cómo te llamas?');
        expect(
            wrapper
                .find(
                    `[aria-label="${i18n.global.t('placement.results.status.dontKnow')}"]`,
                )
                .exists(),
        ).toBe(true);
    });
});
