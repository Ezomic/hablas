<script setup lang="ts">
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import GlossLine from '@/components/lesson/GlossLine.vue';

const props = defineProps<{
    prompt: string;
    instruction: string;
    tiles: string[];
    english?: string;
    glosses?: [string, string][];
    disabled?: boolean;
}>();

const emit = defineEmits<{ change: [text: string] }>();

const { t } = useI18n();

const placed = ref<number[]>([]);

const free = computed(() =>
    props.tiles
        .map((tile, index) => ({ tile, index }))
        .filter(({ index }) => !placed.value.includes(index)),
);

function report() {
    emit('change', placed.value.map((index) => props.tiles[index]).join(' '));
}

function place(index: number) {
    if (!props.disabled) {
        placed.value = [...placed.value, index];
        report();
    }
}

function remove(index: number) {
    if (!props.disabled) {
        placed.value = placed.value.filter((item) => item !== index);
        report();
    }
}

const tileClass =
    'min-h-11 rounded-lg border bg-background px-4 py-2 text-base outline-none transition-colors hover:bg-accent focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:opacity-60';
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
        <ol
            class="flex min-h-16 flex-wrap items-center gap-2 border-b-2 pb-3"
            :aria-label="t('lesson.build.answerLine')"
            data-testid="answer-line"
        >
            <li v-if="placed.length === 0" class="text-muted-foreground">
                {{ t('lesson.build.empty') }}
            </li>
            <li v-for="index in placed" :key="index">
                <button
                    type="button"
                    :class="tileClass"
                    :disabled="props.disabled"
                    data-testid="placed"
                    @click="remove(index)"
                >
                    {{ props.tiles[index] }}
                </button>
            </li>
        </ol>
        <ul
            class="flex flex-wrap gap-2"
            :aria-label="t('lesson.build.tiles')"
            data-testid="tiles"
        >
            <li v-for="item in free" :key="item.index">
                <button
                    type="button"
                    :class="tileClass"
                    :disabled="props.disabled"
                    data-testid="tile"
                    @click="place(item.index)"
                >
                    {{ item.tile }}
                </button>
            </li>
        </ul>
    </section>
</template>
