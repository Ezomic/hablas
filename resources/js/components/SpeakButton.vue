<script setup lang="ts">
import { LoaderCircle, Turtle, Volume2 } from '@lucide/vue';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { useSpeech } from '@/composables/useSpeech';
import { useSpeechSpeed } from '@/composables/useSpeechSpeed';
import type { SpeechSpeed } from '@/types/speech';

const props = withDefaults(
    defineProps<{
        text: string;
        locale: string | null;
        audioUrl?: string | null;
        audioSlowUrl?: string | null;
        label?: string;
    }>(),
    { audioUrl: null, audioSlowUrl: null, label: '' },
);

const { isSupported, isSpeaking, isLoading, speak, prefetch } = useSpeech(
    () => props.locale,
);
const { speed, remember } = useSpeechSpeed();

const active = ref<SpeechSpeed>('normal');

const busy = computed(() => isSpeaking.value || isLoading.value);
const normalLoading = computed(
    () => isLoading.value && active.value === 'normal',
);
const slowLoading = computed(() => isLoading.value && active.value === 'slow');

function play(requested: SpeechSpeed): void {
    remember(requested);
    active.value = requested;
    void speak(
        props.text,
        requested === 'slow' ? props.audioSlowUrl : props.audioUrl,
        { speed: requested },
    );
}
</script>

<template>
    <Button
        v-if="isSupported || props.audioUrl"
        type="button"
        variant="ghost"
        size="sm"
        :aria-label="
            normalLoading ? undefined : props.label || `Listen to ${props.text}`
        "
        :aria-busy="normalLoading"
        :disabled="busy"
        @pointerenter="prefetch(props.audioUrl)"
        @focus="prefetch(props.audioUrl)"
        @click="play('normal')"
    >
        <LoaderCircle v-if="normalLoading" class="size-4 animate-spin" />
        <Volume2 v-else class="size-4" />
        <span v-if="normalLoading" class="sr-only">Loading audio</span>
        <span v-if="props.label">{{ props.label }}</span>
    </Button>
    <Button
        v-if="props.audioSlowUrl"
        type="button"
        :variant="speed === 'slow' ? 'secondary' : 'ghost'"
        size="icon-sm"
        :aria-label="slowLoading ? undefined : `Listen to ${props.text} slowly`"
        :aria-busy="slowLoading"
        :disabled="busy"
        @pointerenter="prefetch(props.audioSlowUrl)"
        @focus="prefetch(props.audioSlowUrl)"
        @click="play('slow')"
    >
        <LoaderCircle v-if="slowLoading" class="size-4 animate-spin" />
        <Turtle v-else class="size-4" />
        <span v-if="slowLoading" class="sr-only">Loading audio</span>
    </Button>
</template>
