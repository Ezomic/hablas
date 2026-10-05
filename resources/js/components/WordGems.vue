<script setup lang="ts">
import { useI18n } from 'vue-i18n';

const props = defineProps<{
    words: { id: number; state: string }[];
}>();

const { t } = useI18n();

const states = ['new', 'seen', 'typing', 'known', 'solid'] as const;

const colors: Record<string, string> = {
    new: 'border-2 border-dashed border-muted-foreground/40',
    seen: 'bg-muted-foreground/40',
    typing: 'bg-amber-400',
    known: 'bg-primary',
    solid: 'bg-emerald-500',
};
</script>

<template>
    <div class="flex flex-col gap-2" data-testid="word-gems">
        <ul class="flex flex-wrap gap-2">
            <li
                v-for="word in props.words"
                :key="word.id"
                class="size-5 rounded-full"
                :class="colors[word.state]"
                role="img"
                :aria-label="t(`progress.word.${word.state}`)"
                :data-state="word.state"
            />
        </ul>
        <ul
            class="flex flex-wrap gap-x-3 gap-y-1 text-xs text-muted-foreground"
        >
            <li
                v-for="state in states"
                :key="state"
                class="flex items-center gap-1"
            >
                <span class="size-2.5 rounded-full" :class="colors[state]" />
                {{ t(`progress.word.${state}`) }}
            </li>
        </ul>
    </div>
</template>
