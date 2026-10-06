<script setup lang="ts">
import { computed, nextTick, onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import AccentKeys from '@/components/lesson/AccentKeys.vue';
import GlossLine from '@/components/lesson/GlossLine.vue';
import LetterBoxes from '@/components/lesson/LetterBoxes.vue';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';

const GAP = '___';

const props = defineProps<{
    modelValue: string;
    prompt: string;
    instruction: string;
    locale: string | null;
    pattern?: string | null;
    mask?: (string | null)[] | null;
    given?: { index: number; char: string }[];
    hint?: string | null;
    english?: string;
    glosses?: [string, string][];
    chips?: string[];
    introduce?: string | null;
    gap?: boolean;
    multiline?: boolean;
    disabled?: boolean;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
    submit: [];
}>();

const { t } = useI18n();

const letterBoxes = ref<{ insert: (character: string) => void } | null>(null);

const field = ref<{
    $el: HTMLInputElement | HTMLTextAreaElement;
} | null>(null);

const gapParts = computed(() => {
    if (!props.gap || props.multiline || !props.prompt.includes(GAP)) {
        return null;
    }

    const [before, ...rest] = props.prompt.split(GAP);

    return { before, after: rest.join(GAP) };
});

function input(): HTMLInputElement | HTMLTextAreaElement | null {
    return field.value?.$el ?? null;
}

onMounted(() => input()?.focus());

async function insert(character: string) {
    if (props.mask) {
        letterBoxes.value?.insert(character);

        return;
    }

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
        <p v-if="props.instruction" class="text-sm text-muted-foreground">
            {{ props.instruction }}
        </p>
        <p
            v-if="props.introduce"
            class="rounded-md bg-amber-50 px-3 py-2 text-sm text-amber-900 dark:bg-amber-950 dark:text-amber-100"
            data-testid="introduce"
        >
            {{ t('lesson.introduce') }}
            <span class="font-semibold" :lang="props.locale ?? undefined">{{
                props.introduce
            }}</span>
        </p>
        <h2
            v-if="gapParts"
            class="text-2xl leading-[3rem] font-semibold"
            data-testid="prompt"
        >
            {{ gapParts.before
            }}<Input
                ref="field"
                :model-value="props.modelValue"
                :lang="props.locale ?? undefined"
                :disabled="props.disabled"
                class="mx-1 inline-block h-10 w-36 align-middle text-lg"
                :aria-label="t('lesson.answerLabel')"
                autocomplete="off"
                autocapitalize="off"
                autocorrect="off"
                spellcheck="false"
                enterkeyhint="done"
                data-testid="gap-input"
                @update:model-value="emit('update:modelValue', String($event))"
                @keydown.enter.prevent="emit('submit')"
            />{{ gapParts.after }}
        </h2>
        <h2
            v-else-if="props.prompt"
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
            v-if="props.chips?.length"
            class="flex flex-col gap-2"
            data-testid="chips"
        >
            <p class="text-sm text-muted-foreground">
                {{ t('lesson.guided.useThese') }}
            </p>
            <ul class="flex flex-wrap gap-2">
                <li
                    v-for="chip in props.chips"
                    :key="chip"
                    class="rounded-full border bg-muted px-3 py-1 text-sm"
                >
                    {{ chip }}
                </li>
            </ul>
        </div>
        <p
            v-if="props.pattern && !props.mask"
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
        <Textarea
            v-if="props.multiline"
            ref="field"
            :model-value="props.modelValue"
            :lang="props.locale ?? undefined"
            :disabled="props.disabled"
            class="min-h-28 text-lg"
            :aria-label="t('lesson.answerLabel')"
            autocomplete="off"
            autocapitalize="off"
            autocorrect="off"
            spellcheck="false"
            @update:model-value="emit('update:modelValue', String($event))"
        />
        <LetterBoxes
            v-else-if="props.mask && !gapParts"
            ref="letterBoxes"
            :model-value="props.modelValue"
            :mask="props.mask"
            :given="props.given"
            :locale="props.locale"
            :disabled="props.disabled"
            @update:model-value="emit('update:modelValue', $event)"
            @submit="emit('submit')"
        />
        <Input
            v-else-if="!gapParts"
            ref="field"
            :model-value="props.modelValue"
            :lang="props.locale ?? undefined"
            :disabled="props.disabled"
            class="h-12 text-lg"
            :aria-label="t('lesson.answerLabel')"
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
