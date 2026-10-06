<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

const props = defineProps<{
    modelValue: string;
    mask: (string | null)[];
    given?: { index: number; char: string }[];
    locale: string | null;
    disabled?: boolean;
    labelledby?: string;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
    submit: [];
}>();

const { t } = useI18n();

const hinted = computed(
    () =>
        new Map((props.given ?? []).map((entry) => [entry.index, entry.char])),
);

const shown = computed(() =>
    props.mask.map((char, index) => hinted.value.get(index) ?? char),
);

const blanks = computed(() =>
    shown.value.flatMap((char, index) => (char === null ? [index] : [])),
);

const typed = ref<Record<number, string>>({});
const boxes = ref<HTMLInputElement[]>([]);
const focused = ref(0);
const composing = ref(false);

function typedAt(blank: number): string {
    return typed.value[blanks.value[blank]] ?? '';
}

function setTyped(blank: number, char: string) {
    typed.value[blanks.value[blank]] = char;
}

const words = computed(() => {
    const groups: {
        char: string | null;
        blank: number;
        key: number;
        hinted: boolean;
    }[][] = [[]];
    let blank = 0;

    shown.value.forEach((char, key) => {
        if (char === ' ') {
            groups.push([]);

            return;
        }

        groups[groups.length - 1].push({
            char,
            blank: char === null ? blank++ : -1,
            key,
            hinted: hinted.value.has(key),
        });
    });

    return groups.filter((group) => group.length > 0);
});

function assembled(): string {
    return shown.value
        .map((char, index) => char ?? typed.value[index] ?? '')
        .join('');
}

function publish() {
    emit(
        'update:modelValue',
        blanks.value.length > 0 &&
            blanks.value.every((index) => (typed.value[index] ?? '') === '')
            ? ''
            : assembled(),
    );
}

watch(
    () => props.modelValue,
    (value) => {
        if (value === '' && Object.values(typed.value).some((char) => char)) {
            typed.value = {};
        }
    },
);

watch(
    () => props.given?.length ?? 0,
    () => {
        publish();
        focusBox(Math.min(focused.value, blanks.value.length - 1));
    },
);

function focusBox(index: number) {
    const target = Math.min(Math.max(index, 0), blanks.value.length - 1);

    focused.value = target;
    boxes.value[target]?.focus();
    boxes.value[target]?.select();
}

function fill(from: number, text: string) {
    const chars = [...text.replace(/\s+/g, '')];
    let at = from;

    for (const char of chars) {
        if (at >= blanks.value.length) {
            break;
        }

        setTyped(at++, char);
    }

    publish();
    focusBox(Math.min(at, blanks.value.length - 1));
}

function onInput(blank: number, event: Event) {
    if (composing.value || (event as InputEvent).isComposing) {
        return;
    }

    const element = event.target as HTMLInputElement;
    const value = element.value;
    const previous = typedAt(blank);

    if (value === '') {
        setTyped(blank, '');
        publish();

        return;
    }

    const entered =
        previous !== '' && value.length > 1
            ? value.replace(previous, '')
            : value;

    setTyped(blank, '');
    fill(blank, entered === '' ? previous : entered);
    element.value = typedAt(blank);
}

function onCompositionEnd(blank: number, event: Event) {
    composing.value = false;
    onInput(blank, event);
}

function onBeforeInput(blank: number, event: InputEvent) {
    if (event.inputType === 'deleteContentBackward' && typedAt(blank) === '') {
        event.preventDefault();
        focusBox(blank - 1);
    }
}

function onKeydown(blank: number, event: KeyboardEvent) {
    if (event.key === 'Enter') {
        event.preventDefault();
        emit('submit');
    } else if (event.key === 'Backspace' && typedAt(blank) === '') {
        event.preventDefault();
        focusBox(blank - 1);
    } else if (event.key === 'ArrowLeft') {
        event.preventDefault();
        focusBox(blank - 1);
    } else if (event.key === 'ArrowRight') {
        event.preventDefault();
        focusBox(blank + 1);
    }
}

function onPaste(blank: number, event: ClipboardEvent) {
    event.preventDefault();
    fill(blank, event.clipboardData?.getData('text') ?? '');
}

function insert(character: string) {
    fill(focused.value, character);
}

function setBox(element: unknown, blank: number) {
    if (element instanceof HTMLInputElement) {
        boxes.value[blank] = element;
    }
}

onMounted(() => focusBox(0));

defineExpose({ insert });
</script>

<template>
    <div
        class="flex flex-wrap gap-x-5 gap-y-3"
        role="group"
        :aria-label="props.labelledby ? undefined : t('lesson.answerLabel')"
        :aria-labelledby="props.labelledby"
        data-testid="letter-boxes"
    >
        <div
            v-for="(word, wordIndex) in words"
            :key="wordIndex"
            class="flex gap-1"
        >
            <template v-for="slot in word" :key="slot.key">
                <span
                    v-if="slot.char !== null"
                    class="flex h-11 min-w-6 items-center justify-center font-mono text-xl"
                    :class="
                        slot.hinted
                            ? 'text-amber-600 dark:text-amber-300'
                            : 'text-muted-foreground'
                    "
                    data-testid="given-letter"
                >
                    {{ slot.char }}
                </span>
                <input
                    v-else
                    :ref="(element) => setBox(element, slot.blank)"
                    :value="typedAt(slot.blank)"
                    :lang="props.locale ?? undefined"
                    :disabled="props.disabled"
                    :aria-label="t('lesson.letterBox', { n: slot.key + 1 })"
                    class="h-11 w-9 rounded-md border border-input bg-background text-center font-mono text-xl focus:border-primary focus:ring-2 focus:ring-primary/30 focus:outline-none disabled:opacity-50"
                    maxlength="2"
                    autocomplete="off"
                    autocapitalize="off"
                    autocorrect="off"
                    spellcheck="false"
                    inputmode="text"
                    data-testid="letter-box"
                    @focus="focused = slot.blank"
                    @input="onInput(slot.blank, $event)"
                    @beforeinput="onBeforeInput(slot.blank, $event)"
                    @compositionstart="composing = true"
                    @compositionend="onCompositionEnd(slot.blank, $event)"
                    @keydown="onKeydown(slot.blank, $event)"
                    @paste="onPaste(slot.blank, $event)"
                />
            </template>
        </div>
    </div>
</template>
