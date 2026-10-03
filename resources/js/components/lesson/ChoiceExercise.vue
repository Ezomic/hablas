<script setup lang="ts">
import { onMounted, onUnmounted } from 'vue';
import GlossLine from '@/components/lesson/GlossLine.vue';
import { choiceButtonClass, choiceState } from '@/lib/choiceStyle';
import { cn } from '@/lib/utils';

const props = defineProps<{
    prompt: string;
    instruction: string;
    options: string[];
    modelValue: string | null;
    // Set once the answer has been checked, to colour the options.
    answer?: string | null;
    english?: string;
    glosses?: [string, string][];
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
        <p
            v-if="props.english"
            class="text-sm text-muted-foreground"
            data-testid="english"
        >
            {{ props.english }}
        </p>
        <GlossLine :glosses="props.glosses" />
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
                        choiceButtonClass,
                        choiceState(option, props.modelValue, props.answer),
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
