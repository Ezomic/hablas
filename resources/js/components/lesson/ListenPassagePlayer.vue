<script setup lang="ts">
import { LoaderCircle, Turtle, Volume2 } from '@lucide/vue';
import { computed, onUnmounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { Button } from '@/components/ui/button';
import { useSpeech } from '@/composables/useSpeech';
import type { PassageLine } from '@/lib/lessonPayload';
import type { SpeechSpeed } from '@/types/speech';

const props = withDefaults(
    defineProps<{
        lines: PassageLine[];
        locale: string | null;
        speed?: SpeechSpeed;
        replayLimit?: number | null;
        offersSlower?: boolean;
    }>(),
    { speed: 'normal', replayLimit: null, offersSlower: false },
);

const { t } = useI18n();
const { isSpeaking, speak, cancel } = useSpeech(() => props.locale);

const plays = ref(0);
const playing = ref(false);
const failed = ref(false);
let alive = true;

const left = computed(() =>
    props.replayLimit === null
        ? null
        : Math.max(0, 1 + props.replayLimit - plays.value),
);
const canPlay = computed(() => !playing.value && left.value !== 0);
const canSlow = computed(
    () =>
        props.offersSlower &&
        props.speed !== 'slow' &&
        props.lines.some((line) => line.audioSlowUrl !== null || line.text),
);

function urlFor(line: PassageLine, speed: SpeechSpeed): string | null {
    return speed === 'slow'
        ? (line.audioSlowUrl ?? line.audioUrl)
        : line.audioUrl;
}

function untilQuiet(): Promise<void> {
    return new Promise((resolve) => {
        if (!isSpeaking.value) {
            resolve();

            return;
        }

        const stop = watch(isSpeaking, (speaking) => {
            if (!speaking) {
                stop();
                resolve();
            }
        });
    });
}

async function play(speed: SpeechSpeed) {
    if (!canPlay.value) {
        return;
    }

    playing.value = true;
    failed.value = false;

    for (const [index, line] of props.lines.entries()) {
        const started = await speak(line.text, urlFor(line, speed), { speed });

        if (!alive) {
            return;
        }

        if (!started) {
            failed.value = true;
            break;
        }

        if (index === 0) {
            plays.value++;
        }

        await untilQuiet();

        if (!alive) {
            return;
        }
    }

    playing.value = false;
}

onUnmounted(() => {
    alive = false;
    cancel();
});
</script>

<template>
    <div
        class="flex flex-col items-center gap-3"
        data-testid="listen-passage-player"
    >
        <Button
            type="button"
            size="icon"
            class="size-24 rounded-full"
            :aria-label="
                plays === 0
                    ? t('lesson.listen.play')
                    : t('lesson.listen.replay')
            "
            :aria-busy="playing"
            :aria-disabled="!canPlay"
            data-testid="listen-play"
            @click="play(props.speed)"
        >
            <LoaderCircle v-if="playing" class="size-10 animate-spin" />
            <Volume2 v-else class="size-10" />
        </Button>
        <div class="flex items-center gap-3 text-sm text-muted-foreground">
            <Button
                v-if="canSlow"
                type="button"
                variant="outline"
                size="sm"
                :aria-disabled="!canPlay"
                data-testid="listen-slower"
                @click="play('slow')"
            >
                <Turtle class="size-4" />
                {{ t('lesson.listen.slower') }}
            </Button>
            <span v-if="left !== null" data-testid="listen-left">
                {{
                    left === 0
                        ? t('lesson.listen.noReplays')
                        : t('lesson.listen.replaysLeft', left)
                }}
            </span>
        </div>
        <p
            v-if="failed"
            class="text-sm text-amber-700 dark:text-amber-300"
            role="status"
        >
            {{ t('lesson.listen.failed') }}
        </p>
    </div>
</template>
