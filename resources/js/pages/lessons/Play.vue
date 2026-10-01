<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Lightbulb, X } from '@lucide/vue';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import AnswerFeedback from '@/components/lesson/AnswerFeedback.vue';
import ChoiceExercise from '@/components/lesson/ChoiceExercise.vue';
import LessonSummary from '@/components/lesson/LessonSummary.vue';
import MatchExercise from '@/components/lesson/MatchExercise.vue';
import TeachCard from '@/components/lesson/TeachCard.vue';
import TypedExercise from '@/components/lesson/TypedExercise.vue';
import { Button } from '@/components/ui/button';
import { Progress } from '@/components/ui/progress';
import { Spinner } from '@/components/ui/spinner';
import { useLessonRun } from '@/composables/useLessonRun';
import {
    expectedAnswer,
    hintFor,
    instructionFor,
    isChoiceFormat,
    isTeachFormat,
    isTypedFormat,
    languageName,
    strings,
    text,
} from '@/lib/lessonPayload';
import { cachePage } from '@/lib/pageCache';
import { show as showRun } from '@/routes/lesson-runs';
import { store as startRun } from '@/routes/lessons/runs';
import { show as showUnit } from '@/routes/units';
import type { PlayProps } from '@/types/lesson';

const props = defineProps<PlayProps>();

const lesson = useLessonRun(props);

const typed = ref('');
const choice = ref<string | null>(null);
const match = ref<{ complete: boolean; wrong: string[] }>({
    complete: false,
    wrong: [],
});
const starting = ref(false);

const exercise = computed(() => lesson.currentExercise.value);
const format = computed(() => exercise.value?.format ?? '');
const language = computed(() => languageName(props.settings.speechLocale));
const locale = computed(() => props.settings.speechLocale);
const phase = lesson.phase;
const inFeedback = computed(() => phase.value !== 'answering');

const canCheck = computed(() => {
    if (isTeachFormat(format.value)) {
        return true;
    }

    if (isChoiceFormat(format.value)) {
        return choice.value !== null;
    }

    if (format.value === 'match_pairs') {
        return match.value.complete;
    }

    return typed.value.trim() !== '';
});

const canHint = computed(
    () =>
        !lesson.check &&
        !lesson.hintShown.value &&
        exercise.value !== null &&
        !isTeachFormat(format.value) &&
        format.value !== 'match_pairs' &&
        expectedAnswer(exercise.value) !== '',
);

const hintText = computed(() =>
    lesson.hintShown.value && exercise.value !== null && !lesson.check
        ? hintFor(exercise.value)
        : null,
);

const studyAnswer = computed(() =>
    lesson.current.value?.mode === 'study' && exercise.value !== null
        ? expectedAnswer(exercise.value)
        : null,
);

const showSummary = computed(
    () => lesson.isCompleted.value && props.run.summary !== null,
);

const waiting = computed(
    () => !showSummary.value && lesson.current.value === null,
);

watch(
    () =>
        `${lesson.current.value?.exerciseId}:${lesson.current.value?.attempt}`,
    () => {
        typed.value = '';
        choice.value = null;
        match.value = { complete: false, wrong: [] };
    },
);

function check() {
    if (!canCheck.value || phase.value !== 'answering') {
        return;
    }

    if (isTeachFormat(format.value)) {
        void lesson.submit({});
    } else if (isChoiceFormat(format.value)) {
        void lesson.submit({ choice: choice.value });
    } else if (format.value === 'match_pairs') {
        void lesson.submit({ wrong: match.value.wrong });
    } else {
        void lesson.submit({ text: typed.value });
    }
}

function onKey(event: KeyboardEvent) {
    const tag = (event.target as HTMLElement | null)?.tagName ?? '';

    if (
        event.key !== 'Enter' ||
        event.repeat ||
        ['INPUT', 'TEXTAREA', 'BUTTON', 'A'].includes(tag)
    ) {
        return;
    }

    if (phase.value === 'feedback' && !lesson.feedback.value?.saving) {
        lesson.advance();
    } else {
        check();
    }
}

function startNext() {
    if (props.run.next === null) {
        return;
    }

    starting.value = true;
    router.post(
        startRun({ unit: props.unit.id, lesson: props.run.next.lessonId }).url,
        {},
        { onFinish: () => (starting.value = false) },
    );
}

const matchPairs = computed(() => {
    const pairs = exercise.value?.payload.pairs;

    return Array.isArray(pairs)
        ? pairs.map((pair) => ({
              target: text((pair as Record<string, unknown>).target),
              left: text((pair as Record<string, unknown>).left),
              right: text((pair as Record<string, unknown>).right),
          }))
        : [];
});

onMounted(() => {
    window.addEventListener('keydown', onKey);
    void cachePage(showRun(props.run.id).url);
});

onUnmounted(() => window.removeEventListener('keydown', onKey));
</script>

<template>
    <Head :title="props.lesson.title" />

    <div class="mx-auto flex w-full max-w-md flex-1 flex-col">
        <header
            class="flex items-center gap-3 px-4 pt-4"
            data-testid="lesson-header"
        >
            <Button
                as-child
                variant="ghost"
                size="icon"
                aria-label="Leave the lesson"
            >
                <Link :href="showUnit(props.unit.id)">
                    <X />
                </Link>
            </Button>
            <Progress
                :model-value="showSummary ? 100 : lesson.percent.value"
                aria-label="Lesson progress"
                class="h-3"
            />
        </header>

        <p class="px-4 pt-2 text-xs text-muted-foreground">
            {{ props.unit.title }}: {{ props.lesson.title }}
        </p>

        <main class="flex flex-1 flex-col gap-4 px-4 py-6">
            <LessonSummary
                v-if="showSummary && props.run.summary"
                :summary="props.run.summary"
                :is-check="!props.settings.feedback"
                :next="props.run.next"
                :starting="starting"
                @next="startNext"
                @unit="router.visit(showUnit(props.unit.id).url)"
            />

            <section
                v-else-if="waiting"
                class="flex flex-col items-center gap-3 py-12 text-center"
                data-testid="finishing"
            >
                <template v-if="lesson.isOnline.value">
                    <Spinner />
                    <p>Finishing up</p>
                </template>
                <template v-else>
                    <p class="font-medium">All answers saved on this device.</p>
                    <p class="text-sm text-muted-foreground">
                        Your results will appear when you are back online.
                    </p>
                </template>
            </section>

            <template v-else-if="exercise">
                <p
                    v-if="studyAnswer"
                    class="rounded-md bg-amber-50 px-3 py-2 text-sm text-amber-900 dark:bg-amber-950 dark:text-amber-100"
                    data-testid="study"
                >
                    Study it, then answer from memory:
                    <span class="font-semibold">{{ studyAnswer }}</span>
                </p>

                <TeachCard
                    v-if="isTeachFormat(format)"
                    :exercise="exercise"
                    :locale="locale"
                />

                <ChoiceExercise
                    v-else-if="isChoiceFormat(format)"
                    v-model="choice"
                    :prompt="text(exercise.payload.prompt)"
                    :instruction="instructionFor(format, language)"
                    :options="strings(exercise.payload.options)"
                    :answer="lesson.feedback.value?.expected ?? null"
                    :disabled="inFeedback"
                />

                <MatchExercise
                    v-else-if="format === 'match_pairs'"
                    :key="
                        exercise.id + '-' + (lesson.current.value?.attempt ?? 1)
                    "
                    :pairs="matchPairs"
                    :instruction="instructionFor(format, language)"
                    :seed="props.run.seed + exercise.id"
                    :disabled="inFeedback"
                    @change="match = $event"
                />

                <TypedExercise
                    v-else-if="isTypedFormat(format)"
                    v-model="typed"
                    :prompt="text(exercise.payload.prompt)"
                    :instruction="instructionFor(format, language)"
                    :locale="locale"
                    :pattern="text(exercise.payload.hint) || null"
                    :hint="hintText"
                    :disabled="inFeedback"
                    @submit="check"
                />

                <p v-else class="text-muted-foreground">
                    This exercise is not available yet.
                </p>

                <Button
                    v-if="canHint && !inFeedback"
                    type="button"
                    variant="ghost"
                    size="sm"
                    class="w-fit"
                    @click="lesson.showHint"
                >
                    <Lightbulb />
                    Show a hint
                </Button>
            </template>
        </main>

        <footer
            v-if="exercise && !showSummary && !waiting"
            class="sticky bottom-0 border-t bg-background px-4 pt-3 pb-[max(1rem,env(safe-area-inset-bottom))]"
            :class="{
                'bg-green-50 dark:bg-green-950':
                    phase === 'feedback' && lesson.feedback.value?.correct,
                'bg-red-50 dark:bg-red-950':
                    phase === 'feedback' &&
                    lesson.feedback.value &&
                    !lesson.feedback.value.correct,
            }"
            data-testid="lesson-footer"
        >
            <div aria-live="polite" class="flex flex-col gap-3">
                <p
                    v-if="lesson.savedFlash.value"
                    class="text-sm text-muted-foreground"
                    data-testid="saved"
                >
                    Answer saved
                </p>

                <template v-if="phase === 'answering'">
                    <Button
                        class="h-12 w-full text-base"
                        :disabled="!canCheck"
                        @click="check"
                    >
                        {{ isTeachFormat(format) ? 'Got it' : 'Check' }}
                    </Button>
                </template>

                <template v-else-if="phase === 'checking'">
                    <Button class="h-12 w-full" disabled>
                        <Spinner />
                        Checking
                    </Button>
                </template>

                <template
                    v-else-if="phase === 'self_check' && lesson.feedback.value"
                >
                    <p class="text-sm">
                        You are offline, so this answer is graded when it syncs.
                        The answer is
                        <span class="font-semibold">{{
                            lesson.feedback.value.expected
                        }}</span
                        >. Did you have it?
                    </p>
                    <div class="grid grid-cols-2 gap-2">
                        <Button
                            class="h-12"
                            variant="outline"
                            @click="lesson.selfCheck(false)"
                        >
                            No
                        </Button>
                        <Button class="h-12" @click="lesson.selfCheck(true)">
                            Yes
                        </Button>
                    </div>
                </template>

                <template v-else-if="phase === 'error'">
                    <p
                        class="text-sm text-red-700 dark:text-red-300"
                        role="alert"
                    >
                        {{
                            lesson.errorMessage.value ||
                            'That could not be saved. Try again.'
                        }}
                    </p>
                    <Button class="h-12 w-full" @click="lesson.retry"
                        >Try again</Button
                    >
                </template>

                <template v-else-if="lesson.feedback.value">
                    <AnswerFeedback
                        :feedback="lesson.feedback.value"
                        @flag="lesson.flag"
                    />
                    <Button
                        class="h-12 w-full text-base"
                        :disabled="lesson.feedback.value.saving"
                        @click="lesson.advance"
                    >
                        Continue
                    </Button>
                </template>
            </div>
        </footer>
    </div>
</template>
