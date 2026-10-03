<script setup lang="ts">
import { LoaderCircle, Turtle, Volume2 } from '@lucide/vue';
import { computed, onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { Button } from '@/components/ui/button';
import { useSpeech } from '@/composables/useSpeech';
import type { SpeechSpeed } from '@/types/speech';

const props = withDefaults(
    defineProps<{
        text?: string;
        locale: string | null;
        audioUrl?: string | null;
        audioSlowUrl?: string | null;
        speed?: SpeechSpeed;
        replayLimit?: number | null;
        offersSlower?: boolean;
        autoplay?: boolean;
    }>(),
    {
        text: '',
        audioUrl: null,
        audioSlowUrl: null,
        speed: 'normal',
        replayLimit: null,
        offersSlower: false,
        autoplay: true,
    },
);

const { t } = useI18n();
const { isLoading, isSpeaking, speak, prefetch } = useSpeech(
    () => props.locale,
);

const plays = ref(0);
const failed = ref(false);

const busy = computed(() => isLoading.value || isSpeaking.value);
const left = computed(() =>
    props.replayLimit === null
        ? null
        : Math.max(0, 1 + props.replayLimit - plays.value),
);
const canPlay = computed(() => !busy.value && left.value !== 0);
const canSlow = computed(
    () =>
        props.offersSlower &&
        props.speed !== 'slow' &&
        (props.audioSlowUrl !== null || props.text !== ''),
);

function urlFor(speed: SpeechSpeed): string | null {
    return speed === 'slow'
        ? (props.audioSlowUrl ?? props.audioUrl)
        : props.audioUrl;
}

async function play(speed: SpeechSpeed) {
    if (!canPlay.value) {
        return;
    }

    const started = await speak(props.text, urlFor(speed), { speed });

    failed.value = !started;

    if (started) {
        plays.value++;
    }
}

onMounted(() => prefetch(urlFor(props.speed)));

watch(
    () => props.autoplay,
    (autoplay) => {
        if (autoplay && plays.value === 0) {
            void play(props.speed);
        }
    },
    { immediate: true },
);
</script>

<template>
    <div class="flex flex-col items-center gap-3" data-testid="listen-player">
        <Button
            type="button"
            size="icon"
            class="size-24 rounded-full"
            :aria-label="
                plays === 0
                    ? t('lesson.listen.play')
                    : t('lesson.listen.replay')
            "
            :aria-busy="isLoading"
            :aria-disabled="!canPlay"
            data-testid="listen-play"
            @click="play(props.speed)"
        >
            <LoaderCircle v-if="isLoading" class="size-10 animate-spin" />
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
