<script setup lang="ts">
import { Volume2 } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { useSpeech } from '@/composables/useSpeech';

const props = withDefaults(
    defineProps<{
        text: string;
        locale: string | null;
        audioUrl?: string | null;
        label?: string;
    }>(),
    { audioUrl: null, label: '' },
);

const { isSupported, isSpeaking, speak } = useSpeech(() => props.locale);
</script>

<template>
    <Button
        v-if="isSupported || props.audioUrl"
        type="button"
        variant="ghost"
        size="sm"
        :aria-label="props.label || `Listen to ${props.text}`"
        :disabled="isSpeaking"
        @click="speak(props.text, props.audioUrl)"
    >
        <Volume2 class="size-4" />
        <span v-if="props.label">{{ props.label }}</span>
    </Button>
</template>
