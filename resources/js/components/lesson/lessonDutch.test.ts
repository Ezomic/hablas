import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it } from 'vitest';
import type { Feedback } from '@/composables/useLessonRun';
import { setLocale } from '@/i18n';
import AccentKeys from './AccentKeys.vue';
import AnswerFeedback from './AnswerFeedback.vue';
import ListenPlayer from './ListenPlayer.vue';
import PassageExercise from './PassageExercise.vue';
import TeachCard from './TeachCard.vue';
import TilesExercise from './TilesExercise.vue';
import TypedExercise from './TypedExercise.vue';

beforeEach(() => setLocale('nl'));

function feedback(overrides: Partial<Feedback> = {}): Feedback {
    return {
        step: 's',
        correct: false,
        expected: 'la habitación',
        note: null,
        given: 'la habitacion',
        details: null,
        why: null,
        flaggable: true,
        flagged: false,
        saving: false,
        ...overrides,
    };
}

describe('the lesson components in Dutch', () => {
    it('says Goed for a right answer and shows no correction', () => {
        const wrapper = mount(AnswerFeedback, {
            props: { feedback: feedback({ correct: true }) },
        });

        expect(wrapper.get('[data-testid="feedback"]').text()).toBe('Goed');
    });

    it('shows the guided writing details and the model answer in Dutch', () => {
        const wrapper = mount(AnswerFeedback, {
            props: {
                guided: true,
                feedback: feedback({
                    details: { found: ['hotel'], missing: ['llave', 'noche'] },
                }),
            },
        });
        const text = wrapper.text();

        expect(text).toContain('Gebruikt: hotel');
        expect(text).toContain('Nog niet gebruikt: llave, noche');
        expect(wrapper.get('[data-testid="model"]').text()).toBe(
            'Voorbeeldantwoord: la habitación',
        );
    });

    it('labels the flag button as flagged', () => {
        const wrapper = mount(AnswerFeedback, {
            props: { feedback: feedback({ flagged: true }) },
        });

        expect(wrapper.get('button').text()).toBe('Bedankt, we kijken ernaar');
    });

    it('names the accent keys and the answer field in Dutch', () => {
        const keys = mount(AccentKeys, { props: { locale: 'es-ES' } });
        const typed = mount(TypedExercise, {
            props: {
                modelValue: '',
                prompt: 'la llave',
                instruction: '',
                locale: 'es-ES',
            },
        });

        expect(keys.get('[role="group"]').attributes('aria-label')).toBe(
            'Accenttoetsen',
        );
        expect(typed.get('input').attributes('aria-label')).toBe(
            'Jouw antwoord',
        );
    });

    it('labels the word and grammar cards in Dutch and marks the Spanish text', () => {
        const word = mount(TeachCard, {
            props: {
                locale: 'es-ES',
                exercise: {
                    id: 1,
                    key: 'k',
                    block: 'b',
                    format: 'teach_word',
                    payload: {
                        term: 'el hotel',
                        translation: 'hotel',
                        part_of_speech: 'noun',
                        is_cognate: true,
                    },
                },
            },
        });
        const grammar = mount(TeachCard, {
            props: {
                locale: 'es-ES',
                exercise: {
                    id: 2,
                    key: 'k',
                    block: 'b',
                    format: 'teach_grammar',
                    payload: {
                        title: 'Ser',
                        explanation: 'x',
                        examples: [{ text: 'Soy Ana.', english: 'I am Ana.' }],
                    },
                },
            },
        });

        expect(word.text()).toContain('Nieuw woord');
        expect(word.text()).toContain('verwant woord');
        expect(word.get('[lang="es-ES"]').text()).toBe('el hotel');
        expect(grammar.text()).toContain('Grammatica');
        expect(grammar.get('[lang="es-ES"]').text()).toBe('Soy Ana.');
    });

    it('labels the tile lists and the empty answer line in Dutch', () => {
        const wrapper = mount(TilesExercise, {
            props: {
                prompt: '',
                instruction: '',
                tiles: ['la', 'llave'],
            },
        });

        expect(
            wrapper.get('[data-testid="answer-line"]').attributes('aria-label'),
        ).toBe('Jouw zin');
        expect(
            wrapper.get('[data-testid="tiles"]').attributes('aria-label'),
        ).toBe('Woorden om uit te kiezen');
        expect(wrapper.get('[data-testid="answer-line"]').text()).toBe(
            'Tik op de woorden hieronder',
        );
    });

    it('labels the dialogue and the transcript in Dutch and marks the lines as Spanish', () => {
        const lines = [
            {
                speaker: 'Ana',
                text: 'Hola.',
                audioUrl: null,
                audioSlowUrl: null,
            },
        ];
        const read = mount(PassageExercise, {
            props: {
                lines,
                questions: [],
                modelValue: [],
                instruction: '',
                locale: 'es-ES',
            },
        });
        const heard = mount(PassageExercise, {
            props: {
                lines,
                questions: [],
                modelValue: [],
                instruction: '',
                locale: 'es-ES',
                listen: true,
                showTranscript: true,
            },
        });

        expect(
            read.get('[data-testid="dialogue"]').attributes('aria-label'),
        ).toBe('Dialoog');
        expect(
            heard.get('[data-testid="dialogue"]').attributes('aria-label'),
        ).toBe('Uitgeschreven tekst');
        expect(read.get('li').attributes('lang')).toBe('es-ES');
        expect(read.get('li').text()).toBe('Ana: Hola.');
    });

    it('counts the replays left in Dutch, one and many', () => {
        const wrapper = mount(ListenPlayer, {
            props: {
                locale: 'es-ES',
                text: 'la llave',
                replayLimit: 0,
                autoplay: false,
            },
        });

        expect(wrapper.get('[data-testid="listen-left"]').text()).toBe(
            'Nog 1 herhaling',
        );
    });
});
