<script setup lang="ts">
import { Pause } from '@lucide/vue';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { ExerciseFamily } from '@/types/lesson';

const props = defineProps<{
    paused: Record<ExerciseFamily, boolean>;
}>();

const emit = defineEmits<{
    pause: [family: ExerciseFamily];
    resume: [family: ExerciseFamily];
}>();

const { t } = useI18n();

const families: ExerciseFamily[] = ['listening', 'speaking'];

const labels = computed(() => ({
    listening: props.paused.listening
        ? t('lesson.pause.resumeListening')
        : t('lesson.pause.startListening'),
    speaking: props.paused.speaking
        ? t('lesson.pause.resumeSpeaking')
        : t('lesson.pause.startSpeaking'),
}));
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button
                variant="ghost"
                size="icon"
                :aria-label="t('lesson.pause.menu')"
                data-testid="pause-menu"
            >
                <Pause />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuItem
                v-for="family in families"
                :key="family"
                :data-testid="`pause-${family}`"
                @select="
                    props.paused[family]
                        ? emit('resume', family)
                        : emit('pause', family)
                "
            >
                {{ labels[family] }}
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
