<script setup lang="ts">
import { CircleCheck, CircleX } from '@lucide/vue';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { Button } from '@/components/ui/button';
import type { Feedback } from '@/composables/useLessonRun';
import { explainMistake } from '@/lib/mistakeExplainer';

const props = defineProps<{
    feedback: Feedback;
    guided?: boolean;
    locale?: string | null;
}>();

const { t } = useI18n();

const emit = defineEmits<{ flag: [] }>();

const notes: Record<string, string> = {
    accent: 'lesson.note.accent',
    article: 'lesson.note.article',
    other_word: 'lesson.note.otherWord',
    portunol: 'lesson.note.portunol',
};

const note = computed(() => {
    const key =
        props.feedback.correct && props.feedback.note === 'other_word'
            ? 'lesson.note.otherWordKept'
            : notes[props.feedback.note ?? ''];

    return key === undefined
        ? null
        : t(key, { expected: props.feedback.expected });
});

const explanation = computed(() =>
    props.feedback.correct || props.locale == null
        ? null
        : explainMistake(
              props.feedback.given,
              props.feedback.expected,
              props.locale,
          ),
);

function words(value: string): string[] {
    return value
        .toLowerCase()
        .replace(/[^\p{L}\p{N}\s]/gu, '')
        .split(/\s+/)
        .filter((word) => word !== '');
}

const marked = computed(() => {
    const expected = new Set(words(props.feedback.expected));

    return props.feedback.given
        .split(/\s+/)
        .filter((word) => word !== '')
        .map((word) => ({
            word,
            wrong: !words(word).every((part) => expected.has(part)),
        }));
});
</script>

<template>
    <div
        class="flex flex-col gap-2"
        :class="
            props.feedback.correct
                ? 'text-green-900 dark:text-green-100'
                : 'text-red-900 dark:text-red-100'
        "
        data-testid="feedback"
    >
        <p class="flex items-center gap-2 text-lg font-semibold">
            <CircleCheck v-if="props.feedback.correct" class="size-5" />
            <CircleX v-else class="size-5" />
            {{
                props.feedback.correct
                    ? t('lesson.feedback.right')
                    : t('lesson.feedback.notQuite')
            }}
        </p>

        <p v-if="note" class="text-sm">{{ note }}</p>

        <p
            v-if="props.feedback.why && !props.feedback.correct"
            class="text-sm"
            data-testid="why"
        >
            {{ props.feedback.why }}
        </p>

        <template v-if="props.guided">
            <p v-if="props.feedback.details?.found.length" class="text-sm">
                {{
                    t('lesson.feedback.used', {
                        words: props.feedback.details.found.join(', '),
                    })
                }}
            </p>
            <p v-if="props.feedback.details?.missing.length" class="text-sm">
                {{
                    t('lesson.feedback.missing', {
                        words: props.feedback.details.missing.join(', '),
                    })
                }}
            </p>
            <p
                v-if="props.feedback.expected"
                class="text-sm"
                data-testid="model"
            >
                {{ t('lesson.feedback.model') }}
                <span class="font-semibold" :lang="props.locale ?? undefined">{{
                    props.feedback.expected
                }}</span>
            </p>
        </template>

        <template v-if="!props.feedback.correct">
            <p v-if="props.feedback.expected && !props.guided" class="text-sm">
                {{ t('lesson.feedback.correctAnswer') }}
                <span class="font-semibold" :lang="props.locale ?? undefined">{{
                    props.feedback.expected
                }}</span>
            </p>
            <p
                v-if="marked.length && !props.guided"
                class="text-sm"
                data-testid="given"
            >
                {{ t('lesson.feedback.yourAnswer') }}
                <template v-for="(item, index) in marked" :key="index">
                    <span
                        :lang="props.locale ?? undefined"
                        :class="
                            item.wrong
                                ? 'font-semibold underline decoration-red-600 decoration-wavy'
                                : ''
                        "
                        >{{ item.word }}</span
                    >{{ ' ' }}
                </template>
            </p>
            <p
                v-if="explanation && !props.guided"
                class="text-sm"
                data-testid="explain"
            >
                {{
                    t(`lesson.explain.${explanation.id}`, {
                        given: explanation.given,
                        expected: explanation.expected,
                        givenVerb: explanation.givenVerb,
                        expectedVerb: explanation.expectedVerb,
                    })
                }}
            </p>
            <Button
                v-if="props.feedback.flaggable"
                type="button"
                variant="link"
                size="sm"
                class="h-auto w-fit self-start p-0 text-inherit"
                :disabled="props.feedback.flagged"
                @click="emit('flag')"
            >
                {{
                    props.feedback.flagged
                        ? t('lesson.feedback.flagged')
                        : t('lesson.feedback.flag')
                }}
            </Button>
        </template>
    </div>
</template>
