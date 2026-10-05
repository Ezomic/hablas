<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        value: number;
        size?: number;
        label: string;
    }>(),
    { size: 88 },
);

const stroke = 8;
const radius = computed(() => (props.size - stroke) / 2);
const circumference = computed(() => 2 * Math.PI * radius.value);
const offset = computed(
    () =>
        circumference.value *
        (1 - Math.min(100, Math.max(0, props.value)) / 100),
);
</script>

<template>
    <div
        class="relative shrink-0"
        :style="{ width: `${props.size}px`, height: `${props.size}px` }"
        role="img"
        :aria-label="props.label"
        data-testid="progress-ring"
    >
        <svg
            :width="props.size"
            :height="props.size"
            :viewBox="`0 0 ${props.size} ${props.size}`"
            class="-rotate-90"
            aria-hidden="true"
        >
            <circle
                :cx="props.size / 2"
                :cy="props.size / 2"
                :r="radius"
                fill="none"
                class="stroke-muted"
                :stroke-width="stroke"
            />
            <circle
                :cx="props.size / 2"
                :cy="props.size / 2"
                :r="radius"
                fill="none"
                class="stroke-primary transition-[stroke-dashoffset] duration-700"
                :stroke-width="stroke"
                stroke-linecap="round"
                :stroke-dasharray="circumference"
                :stroke-dashoffset="offset"
            />
        </svg>
        <span
            class="absolute inset-0 flex items-center justify-center text-lg font-semibold"
        >
            {{ props.value }}%
        </span>
    </div>
</template>
