<script setup lang="ts">
import { onMounted, onUnmounted } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
    prompt: string;
    instruction: string;
    options: string[];
    modelValue: string | null;
    // Set once the answer has been checked, to colour the options.
    answer?: string | null;
    disabled?: boolean;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

function pick(option: string) {
    if (!props.disabled) {
        emit('update:modelValue', option);
    }
}

function onKey(event: KeyboardEvent) {
    const index = Number(event.key) - 1;
    const target = event.target as HTMLElement | null;

    if (
        event.altKey ||
        event.ctrlKey ||
        event.metaKey ||
        ['INPUT', 'TEXTAREA'].includes(target?.tagName ?? '') ||
        !Number.isInteger(index) ||
        index < 0 ||
        index >= props.options.length
    ) {
        return;
    }

    pick(props.options[index]);
}

onMounted(() => window.addEventListener('keydown', onKey));
onUnmounted(() => window.removeEventListener('keydown', onKey));

function state(option: string): string {
    if (
        props.answer === undefined ||
        props.answer === null ||
        props.answer === ''
    ) {
        return option === props.modelValue
            ? 'border-primary bg-primary/10'
            : 'hover:bg-accent';
    }

    if (option === props.answer) {
        return 'border-green-600 bg-green-50 text-green-900 dark:bg-green-950 dark:text-green-100';
    }

    return option === props.modelValue
        ? 'border-red-600 bg-red-50 text-red-900 dark:bg-red-950 dark:text-red-100'
        : 'opacity-60';
}
</script>

<template>
    <section class="flex flex-col gap-4">
        <p v-if="props.instruction" class="text-sm text-muted-foreground">
            {{ props.instruction }}
        </p>
        <h2
            v-if="props.prompt"
            class="text-2xl font-semibold"
            data-testid="prompt"
        >
            {{ props.prompt }}
        </h2>
        <div
            class="flex flex-col gap-2"
            role="radiogroup"
            :aria-label="
                [props.instruction, props.prompt].filter(Boolean).join(': ')
            "
        >
            <button
                v-for="(option, index) in props.options"
                :key="option"
                type="button"
                role="radio"
                :aria-checked="option === props.modelValue"
                :disabled="props.disabled"
                :class="
                    cn(
                        'flex min-h-12 w-full items-center gap-3 rounded-lg border bg-background px-4 py-3 text-left text-base transition-colors outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50',
                        state(option),
                    )
                "
                @click="pick(option)"
            >
                <span
                    class="hidden size-6 shrink-0 items-center justify-center rounded border text-xs text-muted-foreground md:inline-flex"
                    >{{ index + 1 }}</span
                >
                <span>{{ option }}</span>
            </button>
        </div>
    </section>
</template>
