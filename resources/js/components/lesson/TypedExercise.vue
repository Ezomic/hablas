<script setup lang="ts">
import { nextTick, onMounted, ref } from 'vue';
import AccentKeys from '@/components/lesson/AccentKeys.vue';
import { Input } from '@/components/ui/input';

const props = defineProps<{
    modelValue: string;
    prompt: string;
    instruction: string;
    locale: string | null;
    pattern?: string | null;
    hint?: string | null;
    gap?: boolean;
    disabled?: boolean;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
    submit: [];
}>();

const field = ref<{ $el: HTMLInputElement } | null>(null);

function input(): HTMLInputElement | null {
    return field.value?.$el ?? null;
}

onMounted(() => input()?.focus());

async function insert(character: string) {
    const element = input();

    if (element === null) {
        return;
    }

    const start = element.selectionStart ?? props.modelValue.length;
    const end = element.selectionEnd ?? start;

    emit(
        'update:modelValue',
        props.modelValue.slice(0, start) +
            character +
            props.modelValue.slice(end),
    );

    await nextTick();
    element.focus();
    element.setSelectionRange(
        start + character.length,
        start + character.length,
    );
}
</script>

<template>
    <section class="flex flex-col gap-4">
        <p class="text-sm text-muted-foreground">{{ props.instruction }}</p>
        <h2 class="text-2xl font-semibold" data-testid="prompt">
            {{ props.prompt }}
        </h2>
        <p
            v-if="props.pattern"
            class="font-mono text-lg tracking-widest whitespace-pre text-muted-foreground"
            data-testid="pattern"
        >
            {{ props.pattern }}
        </p>
        <p
            v-if="props.hint"
            class="text-sm text-amber-700 dark:text-amber-300"
            data-testid="hint"
        >
            {{ props.hint }}
        </p>
        <Input
            ref="field"
            :model-value="props.modelValue"
            :lang="props.locale ?? undefined"
            :disabled="props.disabled"
            class="h-12 text-lg"
            aria-label="Your answer"
            autocomplete="off"
            autocapitalize="off"
            autocorrect="off"
            spellcheck="false"
            enterkeyhint="done"
            @update:model-value="emit('update:modelValue', String($event))"
            @keydown.enter.prevent="emit('submit')"
        />
        <AccentKeys :locale="props.locale" @insert="insert" />
    </section>
</template>
