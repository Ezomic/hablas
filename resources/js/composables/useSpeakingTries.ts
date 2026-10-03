import { computed, ref } from 'vue';
import { useSpeechRecognition } from '@/composables/useSpeechRecognition';
import { fetchJson } from '@/lib/http';
import type { RecognizerFactory } from '@/lib/speechRecognizer';
import type { SpeakingTryResult, SpokenTry } from '@/types/lesson';

export const MAX_TRIES = 3;

/**
 * The spoken tries of one exercise, up to three. Each is scored at once by a
 * call that records nothing, so the learner sees every result, unless there
 * is no score url, as in a check, which gives no verdict; the answer is
 * sent once afterwards with every transcript. A try that could not be scored
 * is kept, and is scored when the answer is sent.
 */
export function useSpeakingTries(
    locale: () => string | null,
    scoreUrl: () => string,
    factory?: RecognizerFactory,
) {
    const recognition = useSpeechRecognition(locale, factory);
    const tries = ref<SpokenTry[]>([]);

    const canTry = computed(() => tries.value.length < MAX_TRIES);
    const transcripts = computed(() =>
        tries.value.map((spoken) => spoken.transcript),
    );
    const best = computed(() =>
        tries.value.reduce<SpeakingTryResult | null>(
            (top, spoken) =>
                spoken.result !== null &&
                (top === null || spoken.result.score > top.score)
                    ? spoken.result
                    : top,
            null,
        ),
    );

    async function score(index: number, transcript: string) {
        if (scoreUrl() === '') {
            return;
        }

        try {
            const response = await fetchJson(
                scoreUrl(),
                'POST',
                JSON.stringify({ transcript }),
            );

            if (response.ok) {
                const result = (await response.json()) as SpeakingTryResult;

                tries.value = tries.value.map((spoken, position) =>
                    position === index ? { ...spoken, result } : spoken,
                );
            }
        } catch {
            return;
        }
    }

    function record() {
        if (!canTry.value) {
            return;
        }

        recognition.start((transcript) => {
            if (transcript.trim() === '' || !canTry.value) {
                return;
            }

            const index = tries.value.length;

            tries.value = [...tries.value, { transcript, result: null }];
            void score(index, transcript);
        });
    }

    return {
        isSupported: recognition.isSupported,
        isListening: recognition.isListening,
        failure: recognition.failure,
        tries,
        canTry,
        transcripts,
        best,
        record,
        stop: recognition.stop,
    };
}
