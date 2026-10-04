import { onUnmounted, reactive, ref } from 'vue';
import type { SubmitResult } from '@/composables/useOfflineSync';
import { i18n } from '@/i18n';
import { intlTag } from '@/lib/intlLocale';
import { store as storePause } from '@/routes/exercise-pauses';
import type { ExerciseFamily } from '@/types/lesson';

const PAUSE_MINUTES = 60;
const TICK_MS = 15000;

type Submit = (url: string, payload: unknown) => Promise<SubmitResult>;

/**
 * Listening and speaking can each be switched off for an hour, across
 * lessons and devices. The pause shows at once on this device and is sent
 * like an answer, so it is kept when the connection is down.
 */
export function useExercisePauses(
    initial: Record<ExerciseFamily, string | null>,
    submit: Submit,
) {
    const until = reactive<Record<ExerciseFamily, Date | null>>({
        listening:
            initial.listening === null ? null : new Date(initial.listening),
        speaking: initial.speaking === null ? null : new Date(initial.speaking),
    });
    const now = ref(Date.now());
    const timer = setInterval(() => (now.value = Date.now()), TICK_MS);

    onUnmounted(() => clearInterval(timer));

    function isPaused(family: ExerciseFamily): boolean {
        const end = until[family];

        return end !== null && end.getTime() > now.value;
    }

    function endsAt(family: ExerciseFamily): string {
        // English keeps the browser's own clock format, as before the interface had languages.
        const locale = i18n.global.locale.value === 'en' ? [] : intlTag();

        return (until[family] ?? new Date(now.value)).toLocaleTimeString(
            locale,
            { hour: '2-digit', minute: '2-digit' },
        );
    }

    async function send(family: ExerciseFamily, minutes: number) {
        const result = await submit(storePause({ family }).url, { minutes });

        if (!result.queued && result.response.ok) {
            const body = (await result.response.json()) as {
                until: string | null;
            };

            until[family] = body.until === null ? null : new Date(body.until);
        }
    }

    async function pause(family: ExerciseFamily) {
        now.value = Date.now();
        until[family] = new Date(now.value + PAUSE_MINUTES * 60000);
        await send(family, PAUSE_MINUTES);
    }

    async function resume(family: ExerciseFamily) {
        until[family] = null;
        await send(family, 0);
    }

    return { isPaused, endsAt, pause, resume };
}
