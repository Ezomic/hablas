import { flushPromises, mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import type { Feedback } from '@/composables/useLessonRun';
import { FakeAudio } from '@/test/fakeAudio';
import AnswerFeedback from './AnswerFeedback.vue';
import LessonSummary from './LessonSummary.vue';
import PassageExercise from './PassageExercise.vue';
import TilesExercise from './TilesExercise.vue';
import TypedExercise from './TypedExercise.vue';

beforeEach(() => {
    FakeAudio.reset();
    vi.stubGlobal('Audio', FakeAudio);
    vi.stubGlobal(
        'SpeechSynthesisUtterance',
        class {
            constructor(public text: string) {}
        },
    );
    vi.stubGlobal('speechSynthesis', {
        speak: vi.fn(),
        cancel: vi.fn(),
        getVoices: () => [],
    });
});

afterEach(() => {
    vi.unstubAllGlobals();
});

describe('TilesExercise', () => {
    function mountTiles(extra = {}) {
        return mount(TilesExercise, {
            props: {
                prompt: 'The key is in the room.',
                instruction: 'Tap the words',
                tiles: ['la', 'llave', 'está', 'en', 'la', 'habitación'],
                ...extra,
            },
        });
    }

    const texts = (wrapper: ReturnType<typeof mountTiles>, id: string) =>
        wrapper.findAll(`[data-testid="${id}"]`).map((tile) => tile.text());

    it('greys a tapped tile out in place and frees it again when taken back', async () => {
        const wrapper = mountTiles();

        await wrapper.findAll('[data-testid="tile"]')[1].trigger('click');

        expect(texts(wrapper, 'placed')).toEqual(['llave']);
        expect(texts(wrapper, 'tile')).toEqual([
            'la',
            'llave',
            'está',
            'en',
            'la',
            'habitación',
        ]);
        expect(
            wrapper
                .findAll('[data-testid="tile"]')
                .map((tile) => tile.attributes('data-used')),
        ).toEqual([
            undefined,
            'true',
            undefined,
            undefined,
            undefined,
            undefined,
        ]);

        await wrapper.findAll('[data-testid="tile"]')[1].trigger('click');

        expect(texts(wrapper, 'placed')).toEqual(['llave']);

        await wrapper.get('[data-testid="placed"]').trigger('click');

        expect(texts(wrapper, 'placed')).toEqual([]);
        expect(wrapper.findAll('[data-used]')).toHaveLength(0);
    });

    it('shows tiles without the sentence capital or punctuation, but keeps a name', () => {
        const wrapper = mountTiles({
            prompt: 'Good afternoon, we are Pablo and Luis.',
            tiles: ['Buenas', 'tardes,', 'somos', 'Pablo', 'y', 'Luis.'],
        });

        expect(texts(wrapper, 'tile')).toEqual([
            'buenas',
            'tardes',
            'somos',
            'Pablo',
            'y',
            'Luis',
        ]);
    });

    it('reports the placed words joined by a single space, untouched', async () => {
        const wrapper = mountTiles();
        const tiles = () => wrapper.findAll('[data-testid="tile"]');

        await tiles()[0].trigger('click');
        await tiles()[1].trigger('click');

        expect(wrapper.emitted('change')?.at(-1)).toEqual(['la llave']);
    });

    it('keeps repeated words apart, so removing one leaves the other', async () => {
        const wrapper = mountTiles();

        await wrapper.findAll('[data-testid="tile"]')[0].trigger('click');
        await wrapper.findAll('[data-testid="tile"]')[4].trigger('click');

        expect(wrapper.emitted('change')?.at(-1)).toEqual(['la la']);

        await wrapper.findAll('[data-testid="placed"]')[0].trigger('click');

        expect(wrapper.emitted('change')?.at(-1)).toEqual(['la']);
        expect(
            wrapper
                .findAll('[data-testid="tile"]')
                .map((tile) => tile.attributes('data-used')),
        ).toEqual([
            undefined,
            undefined,
            undefined,
            undefined,
            'true',
            undefined,
        ]);
    });

    it('reports an empty answer once every tile is taken back', async () => {
        const wrapper = mountTiles();

        await wrapper.get('[data-testid="tile"]').trigger('click');
        await wrapper.get('[data-testid="placed"]').trigger('click');

        expect(wrapper.emitted('change')?.at(-1)).toEqual(['']);
    });

    it('does nothing while disabled, and shows the English line and glosses', async () => {
        const wrapper = mountTiles({
            disabled: true,
            english: 'La llave está en la habitación.',
            glosses: [['llave', 'key']],
        });

        await wrapper.get('[data-testid="tile"]').trigger('click');

        expect(wrapper.emitted('change')).toBeUndefined();
        expect(wrapper.get('[data-testid="english"]').text()).toContain(
            'La llave',
        );
        expect(wrapper.get('[data-testid="glosses"]').text()).toBe(
            'llave: key',
        );
    });
});

describe('PassageExercise', () => {
    const lines = [
        { speaker: 'Ana', text: 'Hola.', audioUrl: null, audioSlowUrl: null },
        {
            speaker: 'Luis',
            text: 'Buenas.',
            audioUrl: null,
            audioSlowUrl: null,
        },
    ];
    const questions = [
        { prompt: 'Who greets?', options: ['Ana', 'Luis'] },
        { prompt: 'Where?', options: ['hotel', 'bar', 'beach'] },
    ];

    function mountPassage(extra: Record<string, unknown> = {}) {
        return mount(PassageExercise, {
            props: {
                lines,
                questions,
                modelValue: [],
                instruction: 'Read',
                locale: 'es-ES',
                ...extra,
            },
        });
    }

    it('shows the dialogue as speaker-labelled lines and each question with its options', () => {
        const wrapper = mountPassage({ glosses: [['buenas', 'hi']] });

        expect(wrapper.findAll('[data-testid="dialogue"] li')).toHaveLength(2);
        expect(wrapper.get('[data-testid="dialogue"]').text()).toContain(
            'Luis: Buenas.',
        );
        expect(wrapper.findAll('[data-testid="question"]')).toHaveLength(2);
        expect(wrapper.findAll('[role="radio"]')).toHaveLength(5);
        expect(wrapper.get('[data-testid="glosses"]').text()).toBe(
            'buenas: hi',
        );
    });

    it('emits every question in order, keeping the other picks', async () => {
        const wrapper = mountPassage({ modelValue: ['Ana', null] });

        await wrapper.findAll('[role="radio"]')[3].trigger('click');

        expect(wrapper.emitted('update:modelValue')?.[0]).toEqual([
            ['Ana', 'bar'],
        ]);
    });

    it('colours the right option green and a wrong pick red once the answers are given', () => {
        const wrapper = mountPassage({
            modelValue: ['Luis', 'hotel'],
            answers: ['Ana', 'hotel'],
        });
        const classes = wrapper
            .findAll('[role="radio"]')
            .map((button) => button.classes().join(' '));

        expect(classes[0]).toContain('border-green-600');
        expect(classes[1]).toContain('border-red-600');
        expect(classes[2]).toContain('border-green-600');
        expect(classes[3]).toContain('opacity-60');
    });

    it('picks nothing while disabled', async () => {
        const wrapper = mountPassage({ disabled: true });

        await wrapper.get('[role="radio"]').trigger('click');

        expect(wrapper.emitted('update:modelValue')).toBeUndefined();
    });

    describe('heard', () => {
        const heard = [
            {
                speaker: 'Ana',
                text: '',
                audioUrl: '/a.mp3',
                audioSlowUrl: '/a-slow.mp3',
            },
            {
                speaker: 'Luis',
                text: '',
                audioUrl: '/b.mp3',
                audioSlowUrl: '/b-slow.mp3',
            },
        ];

        function mountHeard(extra: Record<string, unknown> = {}) {
            return mountPassage({ listen: true, lines: heard, ...extra });
        }

        async function finishLine(index: number) {
            FakeAudio.instances[index].onplaying?.();
            await flushPromises();
            FakeAudio.instances[index].onended?.();
            await flushPromises();
        }

        it('plays every line in turn from one tap, and shows no text', async () => {
            const wrapper = mountHeard();

            await wrapper.get('[data-testid="listen-play"]').trigger('click');
            await flushPromises();

            expect(FakeAudio.instances.map((audio) => audio.src)).toEqual([
                '/a.mp3',
            ]);

            await finishLine(0);

            expect(FakeAudio.instances.map((audio) => audio.src)).toEqual([
                '/a.mp3',
                '/b.mp3',
            ]);
            await finishLine(1);

            expect(wrapper.find('[data-testid="dialogue"]').exists()).toBe(
                false,
            );
            expect(wrapper.text()).not.toContain('Ana:');
        });

        it('counts one play for the whole passage and stops at the replay limit', async () => {
            const wrapper = mountHeard({ replayLimit: 1 });
            const play = () =>
                wrapper.get('[data-testid="listen-play"]').trigger('click');

            await play();
            await finishLine(0);
            await finishLine(1);

            expect(wrapper.get('[data-testid="listen-left"]').text()).toBe(
                '1 replay left',
            );

            await play();
            await finishLine(0);
            await finishLine(1);

            expect(wrapper.get('[data-testid="listen-left"]').text()).toBe(
                'No replays left',
            );

            await play();

            expect(FakeAudio.instances[0].play).toHaveBeenCalledTimes(2);
        });

        it('offers a slower play from the slow clips only where it is allowed', async () => {
            const without = mountHeard();

            expect(without.find('[data-testid="listen-slower"]').exists()).toBe(
                false,
            );

            const wrapper = mountHeard({ offersSlower: true });

            await wrapper.get('[data-testid="listen-slower"]').trigger('click');

            expect(FakeAudio.last().src).toBe('/a-slow.mp3');
        });

        it('shows the transcript only when told to and only where the text exists', async () => {
            const withText = heard.map((line) => ({ ...line, text: 'Hola.' }));
            const wrapper = mountHeard({ lines: withText });

            expect(wrapper.find('[data-testid="dialogue"]').exists()).toBe(
                false,
            );

            await wrapper.setProps({ showTranscript: true });

            expect(wrapper.get('[data-testid="dialogue"]').text()).toContain(
                'Ana: Hola.',
            );

            const check = mountHeard({ showTranscript: true });

            expect(check.find('[data-testid="dialogue"]').exists()).toBe(false);
        });

        it('says so when a line cannot be played, and does not count the play', async () => {
            FakeAudio.playError = new DOMException(
                'blocked',
                'NotAllowedError',
            );
            const wrapper = mountHeard({ replayLimit: 1 });

            await wrapper.get('[data-testid="listen-play"]').trigger('click');
            await flushPromises();

            expect(wrapper.text()).toContain('Tap play to hear it.');
            expect(wrapper.get('[data-testid="listen-left"]').text()).toBe(
                '2 replays left',
            );
        });
    });
});

describe('TypedExercise formats', () => {
    function mountTyped(extra: Record<string, unknown> = {}) {
        return mount(TypedExercise, {
            attachTo: document.body,
            props: {
                modelValue: '',
                prompt: 'La llave ___ en la habitación.',
                instruction: 'Type the missing word',
                locale: 'es-ES',
                ...extra,
            },
        });
    }

    it('puts the input inline where the gap is, with the English line beneath', async () => {
        const wrapper = mountTyped({
            gap: true,
            english: 'The key is in the room.',
        });
        const prompt = wrapper.get('[data-testid="prompt"]');

        expect(prompt.text()).toBe('La llave  en la habitación.');
        expect(prompt.find('input').exists()).toBe(true);
        expect(wrapper.findAll('input')).toHaveLength(1);
        expect(wrapper.get('[data-testid="english"]').text()).toBe(
            'The key is in the room.',
        );

        await prompt.get('input').setValue('está');

        expect(wrapper.emitted('update:modelValue')?.[0]).toEqual(['está']);

        await prompt.get('input').trigger('keydown', { key: 'Enter' });

        expect(wrapper.emitted('submit')).toHaveLength(1);
        wrapper.unmount();
    });

    it('falls back to the normal layout when the prompt has no gap', () => {
        const wrapper = mountTyped({ gap: true, prompt: 'La llave' });

        expect(
            wrapper.get('[data-testid="prompt"]').find('input').exists(),
        ).toBe(false);
        expect(wrapper.findAll('input')).toHaveLength(1);
        wrapper.unmount();
    });

    it('inserts an accent into the inline input', async () => {
        const wrapper = mountTyped({ gap: true, modelValue: 'esta' });

        await wrapper
            .findAll('button')
            .find((button) => button.text() === 'á')
            ?.trigger('click');

        expect(wrapper.emitted('update:modelValue')?.at(-1)).toEqual(['estaá']);
        wrapper.unmount();
    });

    it('writes into a textarea where Enter adds a line and does not submit', async () => {
        const wrapper = mountTyped({
            multiline: true,
            prompt: 'Say hello to the receptionist.',
            chips: ['hola', 'buenas'],
        });

        expect(wrapper.find('input').exists()).toBe(false);
        expect(wrapper.find('textarea').exists()).toBe(true);
        expect(wrapper.get('[data-testid="chips"]').text()).toContain(
            'Use these words',
        );
        expect(wrapper.findAll('[data-testid="chips"] li')).toHaveLength(2);

        await wrapper.get('textarea').trigger('keydown', { key: 'Enter' });

        expect(wrapper.emitted('submit')).toBeUndefined();
        wrapper.unmount();
    });

    it('shows glosses under the prompt and no chips when there are none', () => {
        const wrapper = mountTyped({
            multiline: true,
            glosses: [
                ['recepción', 'reception'],
                ['llave', 'key'],
            ],
        });

        expect(wrapper.get('[data-testid="glosses"]').text()).toBe(
            'recepción: reception, llave: key',
        );
        expect(wrapper.find('[data-testid="chips"]').exists()).toBe(false);
        wrapper.unmount();
    });
});

describe('AnswerFeedback formats', () => {
    function feedback(overrides: Partial<Feedback> = {}): Feedback {
        return {
            step: 's',
            correct: false,
            expected: 'Hola, quiero una habitación.',
            note: null,
            given: 'hola',
            details: null,
            why: null,
            flaggable: true,
            flagged: false,
            saving: false,
            ...overrides,
        };
    }

    it('shows the words used, the words still missing and the model answer, even when right', () => {
        const wrong = mount(AnswerFeedback, {
            props: {
                guided: true,
                feedback: feedback({
                    details: { found: ['hola', 'quiero'], missing: ['llave'] },
                }),
            },
        });

        expect(wrong.text()).toContain('Used: hola, quiero');
        expect(wrong.text()).toContain('Still missing: llave');
        expect(wrong.get('[data-testid="model"]').text()).toContain(
            'Model answer: Hola, quiero una habitación.',
        );
        expect(wrong.text()).not.toContain('Correct answer');
        expect(wrong.find('[data-testid="given"]').exists()).toBe(false);

        const right = mount(AnswerFeedback, {
            props: {
                guided: true,
                feedback: feedback({
                    correct: true,
                    flaggable: false,
                    details: { found: ['hola'], missing: [] },
                }),
            },
        });

        expect(right.text()).toContain('Right');
        expect(right.text()).toContain('Used: hola');
        expect(right.text()).not.toContain('Still missing');
        expect(right.text()).toContain('Model answer');
    });

    it('shows the why of a wrong answer, and not of a right one', () => {
        const wrong = mount(AnswerFeedback, {
            props: { feedback: feedback({ why: 'Location takes estar' }) },
        });
        const right = mount(AnswerFeedback, {
            props: {
                feedback: feedback({
                    correct: true,
                    why: 'Location takes estar',
                }),
            },
        });

        expect(wrong.get('[data-testid="why"]').text()).toBe(
            'Location takes estar',
        );
        expect(right.find('[data-testid="why"]').exists()).toBe(false);
    });
});

describe('LessonSummary remediation', () => {
    const summary = {
        accuracy: {},
        retried: [],
        items: [],
        answers: [],
        cardsEnrolled: 0,
        newlyKnown: [],
        milestones: [],
        unitCompleted: false,
    };

    function mountSummary(remediation: unknown, isCheck = true) {
        return mount(LessonSummary, {
            props: {
                summary,
                isCheck,
                next: null,
                remediation: remediation as never,
            },
        });
    }

    it('offers practice and the retake after a check, and emits both', async () => {
        const wrapper = mountSummary({
            lessonId: 5,
            missing: 3,
            retake: 'open',
        });

        expect(wrapper.text()).toContain('3 items not proven yet');

        const button = (label: string) =>
            wrapper.findAll('button').find((item) => item.text() === label);

        await button('Practise the missed items')?.trigger('click');
        await button('Retake')?.trigger('click');

        expect(wrapper.emitted('practice')).toHaveLength(1);
        expect(wrapper.emitted('retake')).toHaveLength(1);
    });

    it('shows the actions after a practice run too, and says the retake waits for tomorrow', () => {
        const wrapper = mountSummary(
            { lessonId: 5, missing: 1, retake: 'opens_tomorrow' },
            false,
        );

        expect(wrapper.text()).toContain('1 item not proven yet');
        expect(wrapper.text()).toContain('The retake opens tomorrow');
        expect(wrapper.text()).not.toContain('Retake');
    });

    it('shows nothing about remediation without any', () => {
        expect(
            mountSummary(null).find('[data-testid="remediation"]').exists(),
        ).toBe(false);
    });
});
