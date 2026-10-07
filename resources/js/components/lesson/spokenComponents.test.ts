import { flushPromises, mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import type {
    RecognizerFactory,
    RecognizerHandlers,
} from '@/lib/speechRecognizer';
import { FakeAudio } from '@/test/fakeAudio';
import ListenPlayer from './ListenPlayer.vue';
import PauseMenu from './PauseMenu.vue';
import SpeakExercise from './SpeakExercise.vue';

const mocks = vi.hoisted(() => ({ fetchJson: vi.fn() }));

vi.mock('@/components/ui/dropdown-menu', () => ({
    DropdownMenu: { template: '<div><slot /></div>' },
    DropdownMenuTrigger: { template: '<div><slot /></div>' },
    DropdownMenuContent: { template: '<div><slot /></div>' },
    DropdownMenuItem: {
        emits: ['select'],
        template: '<button @click="$emit(\'select\')"><slot /></button>',
    },
}));

vi.mock('@/lib/http', () => ({ fetchJson: mocks.fetchJson }));

const spoken: { text: string; rate: number }[] = [];

class FakeUtterance {
    lang = '';
    rate = 1;
    voice = null;
    onstart: (() => void) | null = null;
    onend: (() => void) | null = null;
    onerror: (() => void) | null = null;

    constructor(public text: string) {}
}

beforeEach(() => {
    spoken.length = 0;
    FakeAudio.reset();
    mocks.fetchJson.mockReset();

    vi.stubGlobal('webkitSpeechRecognition', class {});
    vi.stubGlobal('Audio', FakeAudio);
    vi.stubGlobal('SpeechSynthesisUtterance', FakeUtterance);
    vi.stubGlobal('speechSynthesis', {
        speak: (utterance: FakeUtterance) => {
            spoken.push(utterance);
            queueMicrotask(() => utterance.onstart?.());
        },
        cancel: vi.fn(),
        getVoices: () => [],
    });
});

afterEach(() => {
    vi.unstubAllGlobals();
});

function play(wrapper: ReturnType<typeof mountPlayer>) {
    return wrapper.get('[data-testid="listen-play"]').trigger('click');
}

function mountPlayer(props: Record<string, unknown> = {}) {
    return mount(ListenPlayer, {
        props: { text: 'la llave', locale: 'es-ES', autoplay: false, ...props },
    });
}

async function finishClip() {
    FakeAudio.last().onplaying?.();
    FakeAudio.last().onended?.();
    await flushPromises();
}

describe('ListenPlayer', () => {
    it('plays on arrival, and counts that as the first play', async () => {
        const wrapper = mountPlayer({
            autoplay: true,
            replayLimit: 2,
            audioUrl: '/c.mp3',
        });

        await flushPromises();
        await finishClip();

        expect(FakeAudio.instances).toHaveLength(1);
        expect(wrapper.get('[data-testid="listen-left"]').text()).toBe(
            '2 replays left',
        );
    });

    it('allows the replays the stage gives and then stops', async () => {
        const wrapper = mountPlayer({ replayLimit: 1, audioUrl: '/c.mp3' });

        await play(wrapper);
        await finishClip();

        expect(wrapper.get('[data-testid="listen-left"]').text()).toBe(
            '1 replay left',
        );

        await play(wrapper);
        await finishClip();

        expect(wrapper.get('[data-testid="listen-left"]').text()).toBe(
            'No replays left',
        );

        await play(wrapper);

        expect(FakeAudio.last().play).toHaveBeenCalledTimes(2);
    });

    it('shows no counter when replays are unlimited', async () => {
        const wrapper = mountPlayer({ audioUrl: '/c.mp3' });

        expect(wrapper.find('[data-testid="listen-left"]').exists()).toBe(
            false,
        );
    });

    it('does not charge a play that never came out', async () => {
        FakeAudio.playError = new DOMException('blocked', 'NotAllowedError');
        const wrapper = mountPlayer({ replayLimit: 1, audioUrl: '/c.mp3' });

        await play(wrapper);
        await flushPromises();

        expect(wrapper.get('[data-testid="listen-left"]').text()).toBe(
            '2 replays left',
        );
        expect(wrapper.text()).toContain('Tap play to hear it.');
    });

    it('offers the slower clip only where the stage does, and plays it', async () => {
        const wrapper = mountPlayer({
            audioUrl: '/n.mp3',
            audioSlowUrl: '/s.mp3',
            offersSlower: true,
        });

        await wrapper.get('[data-testid="listen-slower"]').trigger('click');

        expect(FakeAudio.last().src).toBe('/s.mp3');

        const without = mountPlayer({ audioSlowUrl: '/s.mp3' });

        expect(without.find('[data-testid="listen-slower"]').exists()).toBe(
            false,
        );
    });

    it('starts slow, and then has no slower button, where the stage plays slowly', async () => {
        const wrapper = mountPlayer({
            audioUrl: '/n.mp3',
            audioSlowUrl: '/s.mp3',
            offersSlower: true,
            speed: 'slow',
        });

        await play(wrapper);

        expect(FakeAudio.last().src).toBe('/s.mp3');
        expect(wrapper.find('[data-testid="listen-slower"]').exists()).toBe(
            false,
        );
    });

    it('plays the normal clip at slow speed when there is no slow one, and the text when there is no clip', async () => {
        const clip = mountPlayer({ audioUrl: '/n.mp3', speed: 'slow' });

        await play(clip);

        expect(FakeAudio.last().src).toBe('/n.mp3');

        const browser = mountPlayer({ text: 'hola' });

        await play(browser);
        await flushPromises();

        expect(spoken.at(-1)).toMatchObject({ text: 'hola' });
    });

    it('offers a slower play for a dictation with a slow clip but no text', () => {
        const wrapper = mountPlayer({
            text: '',
            audioUrl: '/n.mp3',
            audioSlowUrl: '/s.mp3',
            offersSlower: true,
        });

        expect(wrapper.find('[data-testid="listen-slower"]').exists()).toBe(
            true,
        );
        expect(
            mountPlayer({ text: '', offersSlower: true })
                .find('[data-testid="listen-slower"]')
                .exists(),
        ).toBe(false);
    });
});

function fakeRecognizer() {
    const state: { handlers: RecognizerHandlers | null } = { handlers: null };
    const factory: RecognizerFactory = (_locale, handlers) => {
        state.handlers = handlers;

        return { start: vi.fn(), stop: vi.fn(), abort: vi.fn() };
    };

    return { state, factory };
}

function mountSpeak(
    payload: Record<string, unknown>,
    format = 'speak_repeat',
    extra: Record<string, unknown> = {},
) {
    const { state, factory } = fakeRecognizer();
    const wrapper = mount(SpeakExercise, {
        props: {
            format,
            payload,
            locale: 'es-ES',
            scoreUrl: '/score',
            replayLimit: null,
            factory,
            ...extra,
        },
    });

    return {
        wrapper,
        say: async (transcript: string) => {
            await wrapper.get('[data-testid="speak-mic"]').trigger('click');
            state.handlers?.onResult(transcript);
            state.handlers?.onEnd();
            await flushPromises();
        },
        state,
    };
}

function scored(extra = {}) {
    return {
        ok: true,
        json: async () => ({
            heard: 'la llave',
            score: 100,
            correct: true,
            words: [
                { word: 'la', verdict: 'exact' },
                { word: 'llave', verdict: 'accent' },
            ],
            missed: 0,
            ...extra,
        }),
    };
}

describe('SpeakExercise', () => {
    it('shows the text to repeat with its model audio, and nothing said yet', () => {
        const { wrapper } = mountSpeak({
            text: 'la llave',
            english: 'the key',
            audioUrl: '/m.mp3',
        });

        expect(wrapper.get('[data-testid="prompt"]').text()).toBe('la llave');
        expect(wrapper.text()).toContain('the key');
        expect(wrapper.text()).toContain('Hear it first');
        expect(wrapper.text()).toContain('Say it out loud');
        expect(wrapper.find('[data-testid="speak-result"]').exists()).toBe(
            false,
        );
        expect(wrapper.emitted('change')?.[0]).toEqual([[]]);
    });

    it('shows what a question means, and keeps the answer hidden until asked, even after a miss', async () => {
        mocks.fetchJson.mockResolvedValue(
            scored({ correct: false, score: 0, missed: 2, words: [] }),
        );
        const { wrapper, say } = mountSpeak(
            {
                prompt: '¿Cómo estás?',
                english: 'How are you?',
                model: 'Estoy bien, gracias.',
            },
            'speak_answer',
        );

        expect(wrapper.text()).toContain('How are you?');
        expect(wrapper.find('[data-testid="speak-hint"]').exists()).toBe(false);

        await say('cómo estás');

        expect(wrapper.find('[data-testid="speak-hint"]').exists()).toBe(false);

        await wrapper.setProps({ showModel: true });

        expect(wrapper.get('[data-testid="speak-hint"]').text()).toBe(
            'Say something like: Estoy bien, gracias.',
        );
    });

    it('gives no hint for a repeat, a pass or a check without a model', async () => {
        mocks.fetchJson.mockResolvedValue(scored());
        const { wrapper, say } = mountSpeak(
            { prompt: '¿Cómo estás?', model: 'Estoy bien.' },
            'speak_answer',
        );

        await say('estoy bien');

        expect(wrapper.find('[data-testid="speak-hint"]').exists()).toBe(false);
    });

    it('scores each try, marks each word and tells the transcripts to its parent', async () => {
        mocks.fetchJson.mockResolvedValue(scored());
        const { wrapper, say } = mountSpeak({ text: 'la llave' });

        await say('la llave');

        expect(wrapper.get('[data-testid="speak-heard"]').text()).toBe(
            'la llave',
        );
        expect(
            wrapper
                .findAll('[data-verdict]')
                .map((word) => word.attributes('data-verdict')),
        ).toEqual(['exact', 'accent']);
        expect(wrapper.get('[data-testid="speak-score"]').text()).toBe(
            'Good, 100%',
        );
        expect(wrapper.get('[data-testid="speak-tries"]').text()).toBe(
            'Try 1 of 3',
        );
        expect(wrapper.emitted('change')?.at(-1)).toEqual([['la llave']]);
    });

    it('scores nothing and shows no verdict in a check, only what was heard and the try counter', async () => {
        const { wrapper, say } = mountSpeak(
            { text: 'la llave' },
            'speak_repeat',
            {
                scoreUrl: '',
            },
        );

        await say('la llave');

        expect(mocks.fetchJson).not.toHaveBeenCalled();
        expect(wrapper.get('[data-testid="speak-heard"]').text()).toBe(
            'la llave',
        );
        expect(wrapper.get('[data-testid="speak-tries"]').text()).toBe(
            'Try 1 of 3',
        );
        expect(wrapper.find('[data-verdict]').exists()).toBe(false);
        expect(wrapper.find('[data-testid="speak-score"]').exists()).toBe(
            false,
        );
        expect(wrapper.text()).not.toContain('scored when you press Check');
        expect(wrapper.emitted('change')?.at(-1)).toEqual([['la llave']]);
    });

    it('says how many words were missed, without naming them', async () => {
        mocks.fetchJson.mockResolvedValue(
            scored({
                score: 50,
                correct: false,
                words: [{ word: 'la', verdict: 'exact' }],
                missed: 1,
            }),
        );
        const { wrapper, say } = mountSpeak({ text: 'la llave' });

        await say('la');

        expect(wrapper.text()).toContain('1 word to say again');
        expect(wrapper.text()).not.toContain('llave llave');
    });

    it('invites another try after a low score, and stops after three', async () => {
        mocks.fetchJson.mockResolvedValue(
            scored({ score: 50, correct: false }),
        );
        const { wrapper, say } = mountSpeak({ text: 'la llave' });

        await say('a');

        expect(wrapper.get('[data-testid="speak-score"]').text()).toContain(
            'Not quite, 50%',
        );

        await say('b');
        await say('c');

        expect(
            wrapper.get('[data-testid="speak-mic"]').attributes('disabled'),
        ).toBeDefined();
        expect(wrapper.text()).toContain('No tries left');
        expect(wrapper.emitted('change')?.at(-1)).toEqual([['a', 'b', 'c']]);
    });

    it('keeps a try it could not score, and says so', async () => {
        mocks.fetchJson.mockRejectedValue(new Error('offline'));
        const { wrapper, say } = mountSpeak({ text: 'la llave' });

        await say('la llave');

        expect(wrapper.text()).toContain('scored when you press Check');
        expect(wrapper.emitted('change')?.at(-1)).toEqual([['la llave']]);
    });

    it('says what the keywords of an answer were, without naming the missed one', async () => {
        mocks.fetchJson.mockResolvedValue(
            scored({
                score: 50,
                correct: false,
                words: [{ word: 'tengo', verdict: 'exact' }],
                missed: 1,
            }),
        );
        const { wrapper, say } = mountSpeak(
            { prompt: 'key', audioRole: 'model' },
            'speak_answer',
        );

        await say('tengo');

        expect(wrapper.text()).toContain('1 keyword missing');
        expect(wrapper.text()).toContain(
            'Say it out loud in the language you are learning',
        );
    });

    it('plays a spoken question first, with the replays the stage allows', async () => {
        const { wrapper } = mountSpeak(
            {
                prompt: '¿Tiene una reserva?',
                audioRole: 'prompt',
                audioUrl: '/q.mp3',
            },
            'speak_answer',
            { replayLimit: 2 },
        );

        await flushPromises();

        expect(FakeAudio.last().src).toBe('/q.mp3');
        expect(wrapper.find('[data-testid="listen-left"]').exists()).toBe(true);
        expect(wrapper.text()).toContain('answer the question out loud');
        expect(wrapper.get('[data-testid="prompt"]').text()).toBe(
            '¿Tiene una reserva?',
        );
    });

    it('plays a question that is only heard, with no text', () => {
        const { wrapper } = mountSpeak(
            { audioRole: 'prompt', audioUrl: '/q.mp3' },
            'speak_answer',
        );

        expect(wrapper.find('[data-testid="prompt"]').exists()).toBe(false);
        expect(wrapper.find('[data-testid="listen-play"]').exists()).toBe(true);
    });

    it('asks the parent to swap it when the microphone is refused, and says when it was not heard', async () => {
        const { wrapper, state } = mountSpeak({ text: 'la llave' });

        await wrapper.get('[data-testid="speak-mic"]').trigger('click');
        state.handlers?.onFailure('failed');
        await flushPromises();

        expect(wrapper.text()).toContain("We couldn't hear that clearly");
        expect(wrapper.emitted('denied')).toBeUndefined();

        await wrapper.get('[data-testid="speak-mic"]').trigger('click');
        state.handlers?.onFailure('denied');
        await flushPromises();

        expect(wrapper.emitted('denied')).toHaveLength(1);
    });

    it('listens while the microphone is open, and cannot be tapped again', async () => {
        const { wrapper } = mountSpeak({ text: 'la llave' });

        await wrapper.get('[data-testid="speak-mic"]').trigger('click');

        expect(wrapper.text()).toContain('Listening…');
        expect(
            wrapper.get('[data-testid="speak-mic"]').attributes('disabled'),
        ).toBeDefined();
    });

    it('cannot be recorded once the answer is being checked', () => {
        const { wrapper } = mountSpeak({ text: 'la llave' }, 'speak_repeat', {
            disabled: true,
        });

        expect(
            wrapper.get('[data-testid="speak-mic"]').attributes('disabled'),
        ).toBeDefined();
    });
});

describe('PauseMenu', () => {
    it('offers to pause listening and speaking, and turns a pause back on', async () => {
        const wrapper = mount(PauseMenu, {
            props: { paused: { listening: true, speaking: false } },
        });

        const listening = wrapper.get('[data-testid="pause-listening"]');
        const speaking = wrapper.get('[data-testid="pause-speaking"]');

        expect(
            wrapper.get('[data-testid="pause-menu"]').attributes('aria-label'),
        ).toBe('Pause listening or speaking');
        expect(listening.text()).toBe('Turn listening back on');
        expect(speaking.text()).toBe("Can't speak now (1 hour)");

        await listening.trigger('click');
        await speaking.trigger('click');

        expect(wrapper.emitted('resume')?.[0]).toEqual(['listening']);
        expect(wrapper.emitted('pause')?.[0]).toEqual(['speaking']);
    });

    it('offers to pause listening when it is on, and to turn speaking back on when it is off', () => {
        const wrapper = mount(PauseMenu, {
            props: { paused: { listening: false, speaking: true } },
        });

        expect(wrapper.get('[data-testid="pause-listening"]').text()).toBe(
            "Can't listen now (1 hour)",
        );
        expect(wrapper.get('[data-testid="pause-speaking"]').text()).toBe(
            'Turn speaking back on',
        );
    });
});
