<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import GlossLine from '@/components/lesson/GlossLine.vue';
import ListenPassagePlayer from '@/components/lesson/ListenPassagePlayer.vue';
import { choiceButtonClass, choiceState } from '@/lib/choiceStyle';
import type { PassageLine, PassageQuestion } from '@/lib/lessonPayload';
import { cn } from '@/lib/utils';
import type { SpeechSpeed } from '@/types/speech';

const props = withDefaults(
    defineProps<{
        lines: PassageLine[];
        questions: Pick<PassageQuestion, 'prompt' | 'options'>[];
        modelValue: (string | null)[];
        instruction: string;
        locale: string | null;
        listen?: boolean;
        answers?: string[] | null;
        showTranscript?: boolean;
        glosses?: [string, string][];
        speed?: SpeechSpeed;
        replayLimit?: number | null;
        offersSlower?: boolean;
        disabled?: boolean;
    }>(),
    {
        listen: false,
        answers: null,
        showTranscript: false,
        speed: 'normal',
        replayLimit: null,
        offersSlower: false,
    },
);

const emit = defineEmits<{ 'update:modelValue': [value: (string | null)[]] }>();

const { t } = useI18n();

function pick(question: number, option: string) {
    if (props.disabled) {
        return;
    }

    emit(
        'update:modelValue',
        props.questions.map((_, index) =>
            index === question ? option : (props.modelValue[index] ?? null),
        ),
    );
}

const shownLines = computed(() => {
    if (!props.listen) {
        return props.lines;
    }

    return props.showTranscript && props.lines.some((line) => line.text)
        ? props.lines
        : [];
});
</script>

<template>
    <section class="flex flex-col gap-6" data-testid="passage">
        <p v-if="props.instruction" class="text-sm text-muted-foreground">
            {{ props.instruction }}
        </p>

        <ListenPassagePlayer
            v-if="props.listen"
            :lines="props.lines"
            :locale="props.locale"
            :speed="props.speed"
            :replay-limit="props.replayLimit"
            :offers-slower="props.offersSlower"
        />

        <ol
            v-if="shownLines.length"
            class="flex flex-col gap-2 rounded-lg bg-muted/50 p-4"
            :aria-label="
                props.listen
                    ? t('lesson.passage.transcript')
                    : t('lesson.passage.dialogue')
            "
            data-testid="dialogue"
        >
            <li
                v-for="(line, index) in shownLines"
                :key="index"
                class="text-base"
            >
                <span class="font-semibold">{{ line.speaker }}:</span>
                {{ line.text }}
            </li>
        </ol>

        <GlossLine :glosses="props.glosses" />

        <div
            v-for="(question, index) in props.questions"
            :key="index"
            class="flex flex-col gap-3"
            data-testid="question"
        >
            <h3 class="text-lg font-semibold">{{ question.prompt }}</h3>
            <div
                class="flex flex-col gap-2"
                role="radiogroup"
                :aria-label="question.prompt"
            >
                <button
                    v-for="option in question.options"
                    :key="option"
                    type="button"
                    role="radio"
                    :aria-checked="option === props.modelValue[index]"
                    :disabled="props.disabled"
                    :class="
                        cn(
                            choiceButtonClass,
                            choiceState(
                                option,
                                props.modelValue[index] ?? null,
                                props.answers?.[index],
                            ),
                        )
                    "
                    @click="pick(index, option)"
                >
                    {{ option }}
                </button>
            </div>
        </div>
    </section>
</template>
