import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import SpeakButton from '@/components/SpeakButton.vue';
import type { Feedback } from '@/composables/useLessonRun';
import AccentKeys from './AccentKeys.vue';
import AnswerFeedback from './AnswerFeedback.vue';
import ChoiceExercise from './ChoiceExercise.vue';
import LessonSummary from './LessonSummary.vue';
import MatchExercise from './MatchExercise.vue';
import TeachCard from './TeachCard.vue';
import TypedExercise from './TypedExercise.vue';

describe('ChoiceExercise', () => {
    function mountChoice(modelValue: string | null = null, extra = {}) {
        return mount(ChoiceExercise, {
            attachTo: document.body,
            props: {
                prompt: 'la llave',
                instruction: 'Choose the meaning',
                options: ['key', 'room', 'hotel', 'night'],
                modelValue,
                ...extra,
            },
        });
    }

    it('picks an option with the numbers 1 to 4', async () => {
        const wrapper = mountChoice();

        window.dispatchEvent(new KeyboardEvent('keydown', { key: '2' }));

        expect(wrapper.emitted('update:modelValue')?.[0]).toEqual(['room']);

        window.dispatchEvent(new KeyboardEvent('keydown', { key: '5' }));
        window.dispatchEvent(
            new KeyboardEvent('keydown', { key: '1', ctrlKey: true }),
        );

        expect(wrapper.emitted('update:modelValue')).toHaveLength(1);
        wrapper.unmount();
    });

    it('picks an option by tapping it, and nothing while disabled', async () => {
        const wrapper = mountChoice(null, { disabled: false });

        await wrapper.findAll('button')[2].trigger('click');

        expect(wrapper.emitted('update:modelValue')?.[0]).toEqual(['hotel']);

        await wrapper.setProps({ disabled: true });
        window.dispatchEvent(new KeyboardEvent('keydown', { key: '1' }));

        expect(wrapper.emitted('update:modelValue')).toHaveLength(1);
        wrapper.unmount();
    });

    it('ignores the numbers typed into a field', () => {
        const wrapper = mountChoice();
        const input = document.createElement('input');
        document.body.append(input);

        input.dispatchEvent(
            new KeyboardEvent('keydown', { key: '1', bubbles: true }),
        );

        expect(wrapper.emitted('update:modelValue')).toBeUndefined();
        input.remove();
        wrapper.unmount();
    });

    it('marks the right option and the wrong pick once checked', () => {
        const wrapper = mountChoice('room', { answer: 'key' });
        const classes = wrapper
            .findAll('button')
            .map((button) => button.classes().join(' '));

        expect(classes[0]).toContain('border-green-600');
        expect(classes[1]).toContain('border-red-600');
        expect(classes[2]).toContain('opacity-60');
        wrapper.unmount();
    });

    it('names its radio group after the instruction and the prompt', () => {
        const wrapper = mountChoice();

        expect(
            wrapper.get('[role="radiogroup"]').attributes('aria-label'),
        ).toBe('Choose the meaning: la llave');
        wrapper.unmount();
    });

    it('highlights the selected option before it is checked', () => {
        const wrapper = mountChoice('hotel');

        expect(wrapper.findAll('button')[2].classes()).toContain(
            'border-primary',
        );
        expect(wrapper.findAll('button')[2].attributes('aria-checked')).toBe(
            'true',
        );
        wrapper.unmount();
    });
});

describe('MatchExercise', () => {
    const pairs = [
        { target: 'a', left: 'la llave', right: 'key' },
        { target: 'b', left: 'el hotel', right: 'hotel' },
    ];

    function mountMatch() {
        return mount(MatchExercise, {
            props: { pairs, instruction: 'Match', seed: 3 },
        });
    }

    async function pair(
        wrapper: ReturnType<typeof mountMatch>,
        left: string,
        right: string,
    ) {
        await wrapper.get(`[data-testid="left-${left}"]`).trigger('click');
        await wrapper.get(`[data-testid="right-${right}"]`).trigger('click');
    }

    it('reports complete with no wrong pair when every tile is matched', async () => {
        const wrapper = mountMatch();

        await pair(wrapper, 'a', 'a');
        await pair(wrapper, 'b', 'b');

        expect(wrapper.emitted('change')?.at(-1)).toEqual([
            { complete: true, wrong: [] },
        ]);
    });

    it('records both tiles of a wrong pair, which breaks the first try', async () => {
        const wrapper = mountMatch();

        await pair(wrapper, 'a', 'b');
        await pair(wrapper, 'a', 'a');
        await pair(wrapper, 'b', 'b');

        expect(wrapper.emitted('change')?.at(-1)).toEqual([
            { complete: true, wrong: ['a', 'b'] },
        ]);
    });

    it('is not complete until every pair is matched, and a matched tile cannot be picked again', async () => {
        const wrapper = mountMatch();

        await pair(wrapper, 'a', 'a');
        await wrapper.get('[data-testid="left-a"]').trigger('click');
        await wrapper.get('[data-testid="right-b"]').trigger('click');

        expect(wrapper.emitted('change')?.at(-1)).toEqual([
            { complete: false, wrong: [] },
        ]);
    });

    it('takes a matched pair out of the tab order and away from screen readers', async () => {
        const wrapper = mountMatch();

        await pair(wrapper, 'a', 'a');

        for (const id of ['left-a', 'right-a']) {
            const tile = wrapper.get(`[data-testid="${id}"]`);

            expect(tile.attributes('disabled')).toBeDefined();
            expect(tile.attributes('aria-hidden')).toBe('true');
            expect(tile.attributes('tabindex')).toBe('-1');
        }

        const open = wrapper.get('[data-testid="left-b"]');

        expect(open.attributes('disabled')).toBeUndefined();
        expect(open.attributes('aria-hidden')).toBeUndefined();
    });

    it('marks the selected tile as pressed', async () => {
        const wrapper = mountMatch();

        expect(
            wrapper.get('[data-testid="left-a"]').attributes('aria-pressed'),
        ).toBe('false');

        await wrapper.get('[data-testid="left-a"]').trigger('click');

        expect(
            wrapper.get('[data-testid="left-a"]').attributes('aria-pressed'),
        ).toBe('true');
        expect(
            wrapper.get('[data-testid="left-b"]').attributes('aria-pressed'),
        ).toBe('false');
    });

    it('does nothing while disabled', async () => {
        const wrapper = mount(MatchExercise, {
            props: { pairs, instruction: 'Match', seed: 3, disabled: true },
        });

        await pair(
            wrapper as unknown as ReturnType<typeof mountMatch>,
            'a',
            'a',
        );

        expect(wrapper.emitted('change')).toBeUndefined();
    });

    it('shows the right column in the same order for the same seed', () => {
        const order = () =>
            mountMatch()
                .findAll('[data-testid^="right-"]')
                .map((tile) => tile.text());

        expect(order()).toEqual(order());
    });
});

describe('TypedExercise', () => {
    function mountTyped(modelValue = '') {
        return mount(TypedExercise, {
            attachTo: document.body,
            props: {
                modelValue,
                prompt: 'receptionist',
                instruction: 'Type it',
                locale: 'es-ES',
            },
        });
    }

    it('shows a form that is new to the learner above the field, and nothing when it is not', () => {
        const withForm = mount(TypedExercise, {
            attachTo: document.body,
            props: {
                modelValue: '',
                prompt: 'Ellos ___ Ana y Luis.',
                instruction: 'Type the missing word',
                locale: 'es-ES',
                gap: true,
                introduce: 'son',
            },
        });

        expect(withForm.get('[data-testid="introduce"]').text()).toContain(
            'New form, type it to remember it:',
        );
        expect(withForm.get('[data-testid="introduce"]').text()).toContain(
            'son',
        );
        withForm.unmount();

        const without = mountTyped();

        expect(without.find('[data-testid="introduce"]').exists()).toBe(false);
        without.unmount();
    });

    it('sets the language of the field and switches off autocorrect', () => {
        const wrapper = mountTyped();
        const input = wrapper.get('input');

        expect(input.attributes('lang')).toBe('es-ES');
        expect(input.attributes('autocorrect')).toBe('off');
        expect(input.attributes('autocapitalize')).toBe('off');
        expect(input.attributes('spellcheck')).toBe('false');
        wrapper.unmount();
    });

    it('checks on Enter', async () => {
        const wrapper = mountTyped('hola');

        await wrapper.get('input').trigger('keydown.enter');

        expect(wrapper.emitted('submit')).toHaveLength(1);
        wrapper.unmount();
    });

    it('inserts an accent key at the cursor', async () => {
        const wrapper = mountTyped('habitacin');
        const input = wrapper.get('input').element;
        input.setSelectionRange(8, 8);

        await wrapper
            .findAll('button')
            .find((button) => button.text() === 'ó')
            ?.trigger('click');

        expect(wrapper.emitted('update:modelValue')?.[0]).toEqual([
            'habitación',
        ]);
        wrapper.unmount();
    });

    it('replaces the selection with the accent key', async () => {
        const wrapper = mountTyped('ab');
        wrapper.get('input').element.setSelectionRange(0, 1);

        await wrapper
            .findAll('button')
            .find((button) => button.text() === 'ñ')
            ?.trigger('click');

        expect(wrapper.emitted('update:modelValue')?.[0]).toEqual(['ñb']);
        wrapper.unmount();
    });

    it('shows the letters pattern and a hint when given', () => {
        const wrapper = mount(TypedExercise, {
            props: {
                modelValue: '',
                prompt: 'key',
                instruction: 'Type it',
                locale: 'es-ES',
                pattern: 'la  l _ _ _ _',
                hint: 'Starts with "l"',
            },
        });

        expect(wrapper.get('[data-testid="pattern"]').text()).toContain(
            'l _ _ _ _',
        );
        expect(wrapper.get('[data-testid="hint"]').text()).toBe(
            'Starts with "l"',
        );
    });
});

describe('AccentKeys', () => {
    it('offers the Spanish keys, and the Portuguese ones for pt-PT', () => {
        const spanish = mount(AccentKeys, { props: { locale: 'es-ES' } })
            .findAll('button')
            .map((button) => button.text());
        const portuguese = mount(AccentKeys, { props: { locale: 'pt-PT' } })
            .findAll('button')
            .map((button) => button.text());

        expect(spanish).toEqual(['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ', '¿', '¡']);
        expect(portuguese).toEqual([
            'á',
            'à',
            'â',
            'ã',
            'ç',
            'é',
            'ê',
            'í',
            'ó',
            'ô',
            'õ',
            'ú',
        ]);
    });
});

describe('AnswerFeedback', () => {
    function feedback(overrides: Partial<Feedback> = {}): Feedback {
        return {
            step: 's',
            correct: false,
            expected: 'la habitación',
            note: null,
            given: 'la habitacion zzz',
            details: null,
            why: null,
            flaggable: true,
            flagged: false,
            saving: false,
            ...overrides,
        };
    }

    it('shows the correct answer and marks the wrong words of a wrong answer', () => {
        const wrapper = mount(AnswerFeedback, {
            props: { feedback: feedback() },
        });

        expect(wrapper.text()).toContain('Correct answer: la habitación');
        const marked = wrapper
            .get('[data-testid="given"]')
            .findAll('span')
            .filter((span) => span.classes().includes('underline'));

        expect(marked.map((span) => span.text())).toEqual([
            'habitacion',
            'zzz',
        ]);
    });

    it('offers "My answer should count" on a wrong answer, and says thanks once flagged', async () => {
        const wrapper = mount(AnswerFeedback, {
            props: { feedback: feedback() },
        });

        await wrapper.get('button').trigger('click');
        expect(wrapper.emitted('flag')).toHaveLength(1);

        await wrapper.setProps({ feedback: feedback({ flagged: true }) });

        expect(wrapper.get('button').text()).toContain('Thanks');
        expect(wrapper.get('button').attributes('disabled')).toBeDefined();
    });

    it('does not offer the flag for a right answer', () => {
        const wrapper = mount(AnswerFeedback, {
            props: { feedback: feedback({ correct: true, flaggable: false }) },
        });

        expect(wrapper.text()).toContain('Right');
        expect(wrapper.find('button').exists()).toBe(false);
    });

    it('says that a forgiven other word counts, and shows the right spelling', () => {
        const kept = mount(AnswerFeedback, {
            props: {
                feedback: feedback({
                    correct: true,
                    note: 'other_word',
                    expected: '¿cómo estás?',
                }),
            },
        }).text();

        expect(kept).toContain('Right');
        expect(kept).toContain(
            'That counts, but the accent makes it a different word: ¿cómo estás?',
        );
    });

    it('explains an accent note, an article note and another word', () => {
        const text = (note: string) =>
            mount(AnswerFeedback, {
                props: {
                    feedback: feedback({ correct: note === 'accent', note }),
                },
            }).text();

        expect(text('accent')).toContain('Watch the accent: la habitación');
        expect(text('article')).toContain('Check the article');
        expect(text('other_word')).toContain('different word');
        expect(text('portunol')).toContain('Spanish, not Portuguese');
    });
});

describe('TeachCard', () => {
    it('shows a word with its meaning, part of speech, cognate badge and contrast note', () => {
        const wrapper = mount(TeachCard, {
            props: {
                locale: 'es-ES',
                exercise: {
                    id: 1,
                    key: 'k',
                    block: 'b',
                    format: 'teach_word',
                    payload: {
                        term: 'la llave',
                        translation: 'key',
                        part_of_speech: 'noun',
                        is_cognate: true,
                        contrast_note: 'Watch out',
                    },
                },
            },
        });

        expect(wrapper.text()).toContain('la llave');
        expect(wrapper.text()).toContain('key');
        expect(wrapper.text()).toContain('noun');
        expect(wrapper.text()).toContain('cognate');
        expect(wrapper.text()).toContain('Watch out');
    });

    it('shows the grammar card with its example sentences', () => {
        const wrapper = mount(TeachCard, {
            props: {
                locale: 'es-ES',
                exercise: {
                    id: 2,
                    key: 'g',
                    block: 'b',
                    format: 'teach_grammar',
                    payload: {
                        title: 'Estar',
                        explanation: 'For location.',
                        examples: [
                            {
                                text: 'El hotel está cerca.',
                                english: 'The hotel is near.',
                            },
                        ],
                    },
                },
            },
        });

        expect(wrapper.text()).toContain('Estar');
        expect(wrapper.text()).toContain('For location.');
        expect(wrapper.text()).toContain('El hotel está cerca.');
        expect(wrapper.text()).toContain('The hotel is near.');
    });

    it('hands the clips of a word and of each grammar example to the listen controls', () => {
        const word = mount(TeachCard, {
            props: {
                locale: 'es-ES',
                exercise: {
                    id: 1,
                    key: 'k',
                    block: 'b',
                    format: 'teach_word',
                    payload: {
                        term: 'la llave',
                        translation: 'key',
                        audioUrl: '/n.mp3',
                        audioSlowUrl: '/s.mp3',
                    },
                },
            },
        });
        const grammar = mount(TeachCard, {
            props: {
                locale: 'es-ES',
                exercise: {
                    id: 2,
                    key: 'g',
                    block: 'b',
                    format: 'teach_grammar',
                    payload: {
                        title: 'Estar',
                        explanation: 'For location.',
                        examples: [
                            {
                                text: 'Uno.',
                                english: 'One.',
                                audioUrl: '/u.mp3',
                            },
                            { text: 'Dos.', english: 'Two.' },
                        ],
                    },
                },
            },
        });

        expect(word.findComponent(SpeakButton).props()).toMatchObject({
            audioUrl: '/n.mp3',
            audioSlowUrl: '/s.mp3',
        });
        expect(
            grammar
                .findAllComponents(SpeakButton)
                .map((button) => [
                    button.props('audioUrl'),
                    button.props('audioSlowUrl'),
                ]),
        ).toEqual([
            ['/u.mp3', null],
            [null, null],
        ]);
    });
});

describe('LessonSummary', () => {
    const summary = {
        accuracy: { choice: 0.9, writing: 0.5 },
        retried: ['la llave'],
        items: [
            { term: 'el hotel', translation: 'hotel', mastered: true },
            { term: 'la llave', translation: 'key', mastered: false },
        ],
        answers: [
            {
                prompt: 'key',
                given: 'llave',
                expected: 'la llave',
                correct: false,
                learnedLanguage: true,
            },
        ],
        cardsEnrolled: 1,
        newlyKnown: [],
        milestones: [],
        unitCompleted: false,
    };

    it('lists the words that became known and a card for each milestone', () => {
        const wrapper = mount(LessonSummary, {
            props: {
                summary: {
                    ...summary,
                    newlyKnown: [
                        { term: 'la llave', translation: 'key' },
                        { term: 'el hotel', translation: 'hotel' },
                    ],
                    milestones: [
                        { type: 'first_word' },
                        { type: 'words', count: 10 },
                        { type: 'unit_words' },
                        { type: 'level_half', level: 'A1' },
                        { type: 'level_full', level: 'A1' },
                    ],
                },
                isCheck: false,
                next: null,
            },
        });

        expect(wrapper.get('[data-testid="newly-known"]').text()).toContain(
            'la llave',
        );
        expect(wrapper.text()).toContain('2 words you know now');

        const milestones = wrapper.get('[data-testid="milestones"]').text();

        expect(milestones).toContain('Your first word known from memory');
        expect(milestones).toContain('10 words known');
        expect(milestones).toContain('Every word of this unit known');
        expect(milestones).toContain('Halfway through A1');
        expect(milestones).toContain('All of A1 known');
    });

    it('shows neither section when nothing became known', () => {
        const wrapper = mount(LessonSummary, {
            props: { summary, isCheck: false, next: null },
        });

        expect(wrapper.find('[data-testid="newly-known"]').exists()).toBe(
            false,
        );
        expect(wrapper.find('[data-testid="milestones"]').exists()).toBe(false);
    });

    it('shows accuracy per type and the items that needed a second go after a lesson', () => {
        const wrapper = mount(LessonSummary, {
            props: {
                summary,
                isCheck: false,
                next: {
                    lessonId: 2,
                    title: 'Recall the words',
                    position: 2,
                    stage: 'recall',
                    state: 'available',
                },
            },
        });

        expect(wrapper.text()).toContain('Lesson complete');
        expect(wrapper.text()).toContain('90%');
        expect(wrapper.text()).toContain('Needed a second go');
        expect(wrapper.text()).not.toContain('Every answer');
        expect(wrapper.text()).toContain('Next lesson: Recall the words');
    });

    it('lists proven and missing items, every answer with the right one, and the cards after a check', async () => {
        const wrapper = mount(LessonSummary, {
            props: {
                summary,
                isCheck: true,
                next: {
                    lessonId: 5,
                    title: 'Unit check',
                    position: 5,
                    stage: 'check',
                    state: 'available',
                },
            },
        });

        expect(wrapper.text()).toContain('Check finished');
        expect(wrapper.text()).toContain('Proven (1)');
        expect(wrapper.text()).toContain('Not proven yet (1)');
        expect(wrapper.text()).toContain('Correct: la llave');
        expect(wrapper.text()).toContain('1 card joined your review deck');
        expect(wrapper.text()).toContain('Take the unit check');

        await wrapper.findAll('button')[0].trigger('click');
        await wrapper.findAll('button')[1].trigger('click');

        expect(wrapper.emitted('next')).toHaveLength(1);
        expect(wrapper.emitted('unit')).toHaveLength(1);
    });

    it('says the unit is complete', () => {
        const wrapper = mount(LessonSummary, {
            props: {
                summary: { ...summary, unitCompleted: true },
                isCheck: true,
                next: null,
            },
        });

        expect(wrapper.text()).toContain('Unit complete');
    });

    it('says the check opens tomorrow when the next lesson waits for another day', () => {
        const wrapper = mount(LessonSummary, {
            props: {
                summary,
                isCheck: false,
                next: {
                    lessonId: 5,
                    title: 'Unit check',
                    position: 5,
                    stage: 'check',
                    state: 'opens_tomorrow',
                },
            },
        });

        expect(wrapper.text()).toContain('opens tomorrow');
        expect(wrapper.text()).not.toContain('Take the unit check');
    });
});
