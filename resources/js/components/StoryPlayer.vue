<script setup lang="ts">
import { Pause, Play } from '@lucide/vue';
import { computed, onBeforeUnmount, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { Button } from '@/components/ui/button';

export interface StorySegment {
    speaker: string;
    text: string;
    audioUrl: string | null;
}

const props = defineProps<{
    segments: StorySegment[];
}>();

const { t } = useI18n();

const current = ref<number | null>(null);
let audio: HTMLAudioElement | null = null;

const playing = computed(() => current.value !== null);
const speaker = computed(() =>
    current.value === null ? null : props.segments[current.value]?.speaker,
);

function stop() {
    audio?.pause();
    audio = null;
    current.value = null;
}

function next(from: number) {
    const index = props.segments.findIndex(
        (segment, i) => i >= from && segment.audioUrl !== null,
    );

    if (index === -1) {
        stop();

        return;
    }

    current.value = index;
    audio = new Audio(props.segments[index].audioUrl ?? '');
    audio.addEventListener('ended', () => next(index + 1));
    audio.addEventListener('error', () => next(index + 1));
    void audio.play().catch(() => next(index + 1));
}

function toggle() {
    if (playing.value) {
        stop();
    } else {
        next(0);
    }
}

onBeforeUnmount(stop);
</script>

<template>
    <div class="flex items-center gap-2" data-testid="story-player">
        <Button
            type="button"
            variant="ghost"
            size="sm"
            :aria-label="t('reading.listen')"
            data-testid="story-play"
            @click="toggle"
        >
            <Pause v-if="playing" />
            <Play v-else />
            {{ playing ? t('reading.stop') : t('reading.listen') }}
        </Button>
        <span
            v-if="speaker"
            class="text-sm text-muted-foreground"
            data-testid="story-speaker"
            >{{ speaker }}</span
        >
    </div>
</template>
