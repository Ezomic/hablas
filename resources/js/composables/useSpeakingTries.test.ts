import { flushPromises, mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { defineComponent } from 'vue';
import type {
    RecognizerFactory,
    RecognizerHandlers,
} from '@/lib/speechRecognizer';
import { useSpeakingTries } from './useSpeakingTries';

const mocks = vi.hoisted(() => ({ fetchJson: vi.fn() }));

vi.mock('@/lib/http', () => ({ fetchJson: mocks.fetchJson }));

let handlers: RecognizerHandlers | null = null;
const factory: RecognizerFactory = (_locale, given) => {
    handlers = given;

    return { start: vi.fn(), stop: vi.fn(), abort: vi.fn() };
};

function harness() {
    let api!: ReturnType<typeof useSpeakingTries>;

    mount(
        defineComponent({
            setup() {
                api = useSpeakingTries(
                    () => 'es-ES',
                    () => '/score',
                    factory,
                );

                return () => null;
            },
        }),
    );

    return api;
}

function result(score: number, correct = score >= 80) {
    return {
        heard: 'x',
        score,
        correct,
        words: [{ word: 'la', verdict: 'exact' }],
        missed: 0,
    };
}

function say(api: ReturnType<typeof useSpeakingTries>, transcript: string) {
    api.record();
    handlers?.onResult(transcript);
    handlers?.onEnd();
}

// jsdom-style environments have no SpeechRecognition, so the composable is
// told it is supported through its default; the factory replaces the engine.
beforeEach(() => {
    mocks.fetchJson.mockReset();
    handlers = null;
    vi.stubGlobal('webkitSpeechRecognition', class {});
});

describe('useSpeakingTries', () => {
    it('scores a try at once and keeps what was said', async () => {
        mocks.fetchJson.mockResolvedValue({
            ok: true,
            json: async () => result(100),
        });
        const api = harness();

        say(api, 'la llave');
        await flushPromises();

        expect(mocks.fetchJson).toHaveBeenCalledWith(
            '/score',
            'POST',
            JSON.stringify({ transcript: 'la llave' }),
        );
        expect(api.transcripts.value).toEqual(['la llave']);
        expect(api.tries.value[0].result?.score).toBe(100);
        expect(api.best.value?.score).toBe(100);
    });

    it('allows three tries and keeps the best score', async () => {
        mocks.fetchJson
            .mockResolvedValueOnce({ ok: true, json: async () => result(40) })
            .mockResolvedValueOnce({ ok: true, json: async () => result(90) })
            .mockResolvedValueOnce({ ok: true, json: async () => result(60) });
        const api = harness();

        for (const phrase of ['a', 'b', 'c', 'd']) {
            say(api, phrase);
            await flushPromises();
        }

        expect(api.transcripts.value).toEqual(['a', 'b', 'c']);
        expect(api.canTry.value).toBe(false);
        expect(api.best.value?.score).toBe(90);
        expect(mocks.fetchJson).toHaveBeenCalledTimes(3);
    });

    it('keeps a try that could not be scored, to be scored when the answer is sent', async () => {
        mocks.fetchJson.mockRejectedValueOnce(new Error('offline'));
        const api = harness();

        say(api, 'la llave');
        await flushPromises();

        expect(api.transcripts.value).toEqual(['la llave']);
        expect(api.tries.value[0].result).toBeNull();
        expect(api.best.value).toBeNull();
    });

    it('keeps a try the server refused to score', async () => {
        mocks.fetchJson.mockResolvedValueOnce({ ok: false });
        const api = harness();

        say(api, 'la llave');
        await flushPromises();

        expect(api.tries.value[0].result).toBeNull();
    });

    it('ignores a try with nothing in it', async () => {
        const api = harness();

        say(api, '   ');
        await flushPromises();

        expect(api.tries.value).toEqual([]);
        expect(mocks.fetchJson).not.toHaveBeenCalled();
    });
});
