<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Lightbulb, X } from '@lucide/vue';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import AppSpinner from '@/components/AppSpinner.vue';
import AnswerFeedback from '@/components/lesson/AnswerFeedback.vue';
import ChoiceExercise from '@/components/lesson/ChoiceExercise.vue';
import LessonSummary from '@/components/lesson/LessonSummary.vue';
import ListenPlayer from '@/components/lesson/ListenPlayer.vue';
import MatchExercise from '@/components/lesson/MatchExercise.vue';
import PassageExercise from '@/components/lesson/PassageExercise.vue';
import PauseMenu from '@/components/lesson/PauseMenu.vue';
import SpeakExercise from '@/components/lesson/SpeakExercise.vue';
import TeachCard from '@/components/lesson/TeachCard.vue';
import TilesExercise from '@/components/lesson/TilesExercise.vue';
import TypedExercise from '@/components/lesson/TypedExercise.vue';
import SpeakButton from '@/components/SpeakButton.vue';
import { Button } from '@/components/ui/button';
import { Progress } from '@/components/ui/progress';
import { useLessonRun } from '@/composables/useLessonRun';
import {
    expectedAnswer,
    hintFor,
    instructionFor,
    clipUrl,
    englishLine,
    glossesOf,
    isChoiceFormat,
    isListenFormat,
    isPassageFormat,
    isSpeakFormat,
    isTeachFormat,
    isTypedFormat,
    maskOf,
    hintLettersOf,
    answersInLearnedLanguage,
    languageName,
    passageAnswers,
    passageLines,
    passageQuestions,
    sameLetters,
    strings,
    text,
} from '@/lib/lessonPayload';
import { familyOf } from '@/lib/lessonQueue';
import { cachePage } from '@/lib/pageCache';
import { show as showRun } from '@/routes/lesson-runs';
import { store as scoreTry } from '@/routes/lesson-runs/speaking-tries';
import { store as startRun } from '@/routes/lessons/runs';
import { show as showUnit } from '@/routes/units';
import type { ExerciseFamily, PlayProps } from '@/types/lesson';

const props = defineProps<PlayProps>();

const { t } = useI18n();
const lesson = useLessonRun(props);

const typed = ref('');
const choice = ref<string | null>(null);
const match = ref<{ complete: boolean; wrong: string[] }>({
    complete: false,
    wrong: [],
});
const choices = ref<(string | null)[]>([]);
const spoken = ref<string[]>([]);
const starting = ref(false);

const exercise = computed(() => lesson.currentExercise.value);
const format = computed(() => exercise.value?.format ?? '');
const language = computed(() => languageName(props.settings.speechLocale));
const locale = computed(() => props.settings.speechLocale);
const phase = lesson.phase;
const inFeedback = computed(() => phase.value !== 'answering');

const glosses = computed(() =>
    exercise.value === null ? [] : glossesOf(exercise.value.payload),
);
const english = computed(() =>
    exercise.value === null ? '' : englishLine(exercise.value.payload),
);
const questions = computed(() =>
    exercise.value === null ? [] : passageQuestions(exercise.value.payload),
);
const attemptKey = computed(
    () => `${exercise.value?.id}-${lesson.current.value?.attempt ?? 1}`,
);

const copiesWord = computed(() => format.value === 'teach_word');

const copyMatches = computed(
    () =>
        exercise.value !== null &&
        sameLetters(typed.value, text(exercise.value.payload.term)),
);

const copyMissed = computed(
    () =>
        copiesWord.value &&
        !copyMatches.value &&
        exercise.value !== null &&
        typed.value.trim().length >=
            text(exercise.value.payload.term).trim().length,
);

const canCheck = computed(() => {
    if (copiesWord.value) {
        return copyMatches.value;
    }

    if (isTeachFormat(format.value)) {
        return true;
    }

    if (isChoiceFormat(format.value)) {
        return choice.value !== null;
    }

    if (isPassageFormat(format.value)) {
        return (
            questions.value.length > 0 &&
            questions.value.every((_, index) => choices.value[index] != null)
        );
    }

    if (format.value === 'match_pairs') {
        return match.value.complete;
    }

    if (isSpeakFormat(format.value)) {
        return spoken.value.length > 0;
    }

    return typed.value.trim() !== '';
});

const lettersGiven = ref(0);

const hintLetters = computed(() =>
    exercise.value !== null && maskOf(exercise.value.payload.mask) !== null
        ? hintLettersOf(exercise.value.payload.hintLetters)
        : [],
);

const givenLetters = computed(() =>
    hintLetters.value.slice(0, lettersGiven.value),
);

const givesLetters = computed(() => hintLetters.value.length > 0);

watch(
    () => [exercise.value?.id, lesson.current.value?.attempt],
    () => {
        lettersGiven.value = 0;
    },
);

function giveLetter() {
    lesson.showHint();
    lettersGiven.value += 1;
}

const canHint = computed(
    () =>
        !lesson.check &&
        (givesLetters.value
            ? lettersGiven.value < hintLetters.value.length
            : !lesson.hintShown.value) &&
        exercise.value !== null &&
        !isTeachFormat(format.value) &&
        !isSpeakFormat(format.value) &&
        !isPassageFormat(format.value) &&
        !['match_pairs', 'build_sentence'].includes(format.value) &&
        (expectedAnswer(exercise.value) !== '' ||
            (isListenFormat(format.value) &&
                (clipUrl(exercise.value.payload.audioSlowUrl) !== null ||
                    text(exercise.value.payload.text) !== ''))),
);

const hintText = computed(() =>
    lesson.hintShown.value &&
    !givesLetters.value &&
    exercise.value !== null &&
    !lesson.check
        ? hintFor(exercise.value)
        : null,
);

const studyAnswer = computed(() =>
    lesson.current.value?.mode === 'study' && exercise.value !== null
        ? expectedAnswer(exercise.value) ||
          (lesson.knownAnswers.value[exercise.value.id] ?? '')
        : null,
);

const instruction = computed(() => {
    switch (format.value) {
        case 'listen_choose':
            return t('lesson.instruction.listenChoose');
        case 'listen_pair':
            return t('lesson.instruction.listenPair');
        case 'listen_type':
            return t('lesson.instruction.listenType');
        case 'listen_passage':
            return t('lesson.instruction.listenPassage');
        case 'read_passage':
            return t('lesson.instruction.readPassage');
        case 'build_sentence':
            return t('lesson.instruction.buildSentence');
        case 'write_guided':
            return t('lesson.instruction.writeGuided');
        case 'transform_sentence':
            return text(exercise.value?.payload.prompt);
        default:
            return instructionFor(format.value, language.value);
    }
});

const heardText = computed(() =>
    exercise.value === null ||
    !inFeedback.value ||
    !isListenFormat(format.value)
        ? ''
        : text(exercise.value.payload.text) ||
          (lesson.feedback.value?.expected ?? ''),
);

const scoreUrl = computed(() =>
    exercise.value === null || !props.settings.feedback
        ? ''
        : scoreTry({
              lessonRun: props.run.id,
              lessonExercise: exercise.value.id,
          }).url,
);

const skipFamily = computed(() => familyOf(format.value) as ExerciseFamily);

const modelAnswer = computed(() =>
    inFeedback.value &&
    exercise.value !== null &&
    format.value === 'speak_answer' &&
    exercise.value.payload.audioRole === 'model'
        ? (lesson.feedback.value?.expected ?? '')
        : '',
);

const paused = computed(() => ({
    listening: lesson.pauses.isPaused('listening'),
    speaking: lesson.pauses.isPaused('speaking'),
}));

const swapNotice = computed(() => {
    switch (lesson.swapReason.value) {
        case 'paused':
            return t('lesson.swap.paused');
        case 'offline':
            return t('lesson.swap.offline');
        case 'unsupported':
            return t('lesson.swap.unsupported');
        default:
            return null;
    }
});

const showSummary = computed(
    () => lesson.isCompleted.value && props.run.summary !== null,
);

const waiting = computed(
    () => !showSummary.value && lesson.current.value === null,
);

watch(
    () =>
        `${lesson.current.value?.exerciseId}:${lesson.current.value?.shownId}:${lesson.current.value?.attempt}`,
    () => {
        typed.value = '';
        choice.value = null;
        choices.value = [];
        spoken.value = [];
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
    } else if (isPassageFormat(format.value)) {
        void lesson.submit({ choices: choices.value });
    } else if (format.value === 'match_pairs') {
        void lesson.submit({ wrong: match.value.wrong });
    } else if (isSpeakFormat(format.value)) {
        void lesson.submit({ transcripts: spoken.value });
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

function startFor(kind: 'practice' | 'retake') {
    const remediation = props.run.remediation;

    if (remediation === null) {
        return;
    }

    starting.value = true;
    router.post(
        startRun({ unit: props.unit.id, lesson: remediation.lessonId }).url,
        { kind },
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
                :aria-label="t('lesson.leave')"
            >
                <Link :href="showUnit(props.unit.id)">
                    <X />
                </Link>
            </Button>
            <Progress
                :model-value="showSummary ? 100 : lesson.percent.value"
                :aria-label="t('lesson.progress')"
                class="h-3"
            />
            <PauseMenu
                v-if="!showSummary"
                :paused="paused"
                @pause="lesson.pauses.pause"
                @resume="lesson.pauses.resume"
            />
        </header>

        <div
            v-for="family in ['listening', 'speaking'] as ExerciseFamily[]"
            :key="family"
        >
            <p
                v-if="paused[family] && !showSummary"
                class="mx-4 mt-2 flex items-center justify-between gap-2 rounded-md bg-muted px-3 py-2 text-sm"
                :data-testid="`paused-${family}`"
            >
                <span>{{
                    family === 'listening'
                        ? t('lesson.pause.bannerListening', {
                              time: lesson.pauses.endsAt(family),
                          })
                        : t('lesson.pause.bannerSpeaking', {
                              time: lesson.pauses.endsAt(family),
                          })
                }}</span>
                <Button
                    type="button"
                    variant="link"
                    size="sm"
                    class="h-auto p-0"
                    @click="lesson.pauses.resume(family)"
                >
                    {{ t('lesson.pause.turnOn') }}
                </Button>
            </p>
        </div>

        <p class="px-4 pt-2 text-xs text-muted-foreground">
            {{ `${props.unit.title}: ${props.lesson.title}` }}
        </p>

        <main class="flex flex-1 flex-col gap-4 px-4 py-6">
            <LessonSummary
                v-if="showSummary && props.run.summary"
                :summary="props.run.summary"
                :is-check="!props.settings.feedback"
                :locale="locale"
                :next="props.run.next"
                :starting="starting"
                :remediation="props.run.remediation"
                @practice="startFor('practice')"
                @retake="startFor('retake')"
                @next="startNext"
                @unit="router.visit(showUnit(props.unit.id).url)"
            />

            <section
                v-else-if="waiting"
                class="flex flex-col items-center gap-3 py-12 text-center"
                data-testid="finishing"
            >
                <template v-if="lesson.isOnline.value">
                    <AppSpinner />
                    <p>{{ t('lesson.finishing') }}</p>
                </template>
                <template v-else>
                    <p class="font-medium">
                        {{ t('lesson.offlineSaved.title') }}
                    </p>
                    <p class="text-sm text-muted-foreground">
                        {{ t('lesson.offlineSaved.note') }}
                    </p>
                </template>
            </section>

            <template v-else-if="exercise">
                <i18n-t
                    v-if="studyAnswer"
                    keypath="lesson.study"
                    tag="p"
                    scope="global"
                    class="rounded-md bg-amber-50 px-3 py-2 text-sm text-amber-900 dark:bg-amber-950 dark:text-amber-100"
                    data-testid="study"
                >
                    <template #answer>
                        <span class="font-semibold">{{ studyAnswer }}</span>
                    </template>
                </i18n-t>

                <p
                    v-if="swapNotice"
                    class="rounded-md bg-muted px-3 py-2 text-sm text-muted-foreground"
                    data-testid="swap-notice"
                >
                    {{ swapNotice }}
                </p>

                <template v-if="isTeachFormat(format)">
                    <TeachCard
                        :exercise="exercise"
                        :locale="locale"
                        :matched="copiesWord && copyMatches"
                    />

                    <template v-if="copiesWord">
                        <TypedExercise
                            :key="exercise.id"
                            v-model="typed"
                            prompt=""
                            :instruction="t('lesson.teach.typeIt')"
                            :locale="locale"
                            :disabled="inFeedback"
                            @submit="check"
                        />
                        <p
                            v-if="copyMissed"
                            class="text-sm text-muted-foreground"
                            data-testid="copy-miss"
                        >
                            {{ t('lesson.teach.copyMiss') }}
                        </p>
                    </template>
                </template>

                <section
                    v-else-if="isListenFormat(format)"
                    class="flex flex-col gap-6"
                    data-testid="listen-exercise"
                >
                    <ListenPlayer
                        :key="
                            exercise.id +
                            '-' +
                            (lesson.current.value?.attempt ?? 1)
                        "
                        :text="text(exercise.payload.text)"
                        :locale="locale"
                        :audio-url="clipUrl(exercise.payload.audioUrl)"
                        :audio-slow-url="clipUrl(exercise.payload.audioSlowUrl)"
                        :speed="
                            props.settings.audioSpeed < 1 ? 'slow' : 'normal'
                        "
                        :replay-limit="props.settings.replayLimit"
                        :offers-slower="
                            props.settings.offersSlowerAudio ||
                            lesson.hintShown.value
                        "
                    />

                    <TypedExercise
                        v-if="format === 'listen_type'"
                        :key="
                            exercise.id +
                            '-' +
                            (lesson.current.value?.attempt ?? 1)
                        "
                        v-model="typed"
                        prompt=""
                        :instruction="instruction"
                        :locale="locale"
                        :hint="null"
                        :disabled="inFeedback"
                        @submit="check"
                    />

                    <ChoiceExercise
                        v-else
                        v-model="choice"
                        prompt=""
                        :instruction="instruction"
                        :options="strings(exercise.payload.options)"
                        :answer="lesson.feedback.value?.expected ?? null"
                        :disabled="inFeedback"
                    />

                    <p
                        v-if="heardText"
                        :lang="locale ?? undefined"
                        class="text-center text-lg font-semibold"
                        data-testid="heard"
                    >
                        {{ heardText }}
                    </p>
                </section>

                <SpeakExercise
                    v-else-if="isSpeakFormat(format)"
                    :key="
                        exercise.id + '-' + (lesson.current.value?.attempt ?? 1)
                    "
                    :format="format"
                    :payload="exercise.payload"
                    :locale="locale"
                    :score-url="scoreUrl"
                    :replay-limit="props.settings.replayLimit"
                    :disabled="inFeedback"
                    @change="spoken = $event"
                    @denied="lesson.denyMicrophone"
                />

                <ChoiceExercise
                    v-else-if="isChoiceFormat(format)"
                    v-model="choice"
                    :prompt="text(exercise.payload.prompt)"
                    :instruction="instructionFor(format, language)"
                    :options="strings(exercise.payload.options)"
                    :answer="lesson.feedback.value?.expected ?? null"
                    :english="english"
                    :glosses="glosses"
                    :disabled="inFeedback"
                />

                <PassageExercise
                    v-else-if="isPassageFormat(format)"
                    :key="attemptKey"
                    v-model="choices"
                    :lines="passageLines(exercise.payload)"
                    :questions="questions"
                    :instruction="instruction"
                    :locale="locale"
                    :listen="format === 'listen_passage'"
                    :answers="
                        inFeedback && props.settings.feedback
                            ? passageAnswers(exercise.payload)
                            : null
                    "
                    :show-transcript="
                        phase === 'feedback' && props.settings.feedback
                    "
                    :glosses="glosses"
                    :speed="props.settings.audioSpeed < 1 ? 'slow' : 'normal'"
                    :replay-limit="props.settings.replayLimit"
                    :offers-slower="
                        props.settings.offersSlowerAudio ||
                        lesson.hintShown.value
                    "
                    :disabled="inFeedback"
                />

                <TilesExercise
                    v-else-if="format === 'build_sentence'"
                    :key="attemptKey"
                    :prompt="text(exercise.payload.prompt)"
                    :instruction="instruction"
                    :tiles="strings(exercise.payload.tiles)"
                    :english="english"
                    :glosses="glosses"
                    :disabled="inFeedback"
                    @change="typed = $event"
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
                    :key="
                        exercise.id + '-' + (lesson.current.value?.attempt ?? 1)
                    "
                    v-model="typed"
                    :prompt="
                        format === 'transform_sentence'
                            ? text(exercise.payload.source)
                            : text(exercise.payload.prompt)
                    "
                    :instruction="instruction"
                    :english="english"
                    :glosses="glosses"
                    :chips="strings(exercise.payload.chips)"
                    :gap="format === 'type_gap'"
                    :multiline="format === 'write_guided'"
                    :locale="locale"
                    :pattern="text(exercise.payload.hint) || null"
                    :introduce="text(exercise.payload.introduce) || null"
                    :mask="maskOf(exercise.payload.mask)"
                    :given="givenLetters"
                    :hint="hintText"
                    :disabled="inFeedback"
                    @submit="check"
                />

                <p v-else class="text-muted-foreground">
                    {{ t('lesson.unavailable') }}
                </p>

                <SpeakButton
                    v-if="modelAnswer"
                    :text="modelAnswer"
                    :locale="locale"
                    :audio-url="clipUrl(exercise.payload.audioUrl)"
                    :audio-slow-url="clipUrl(exercise.payload.audioSlowUrl)"
                    :label="t('lesson.speak.hearModel')"
                />

                <Button
                    v-if="canHint && !inFeedback"
                    type="button"
                    variant="ghost"
                    size="sm"
                    class="w-fit"
                    @click="givesLetters ? giveLetter() : lesson.showHint()"
                >
                    <Lightbulb />
                    {{
                        givesLetters
                            ? t('lesson.hint.letter')
                            : t('lesson.hint.show')
                    }}
                </Button>

                <div
                    v-if="lesson.canSkip.value && !inFeedback"
                    class="flex flex-wrap gap-x-4"
                    data-testid="skip-links"
                >
                    <Button
                        type="button"
                        variant="link"
                        size="sm"
                        class="h-auto p-0"
                        data-testid="skip"
                        @click="lesson.skip"
                    >
                        {{ t('lesson.skip.link') }}
                    </Button>
                    <Button
                        type="button"
                        variant="link"
                        size="sm"
                        class="h-auto p-0"
                        data-testid="skip-family"
                        @click="lesson.pauses.pause(skipFamily)"
                    >
                        {{
                            skipFamily === 'listening'
                                ? t('lesson.skip.cantListen')
                                : t('lesson.skip.cantSpeak')
                        }}
                    </Button>
                </div>
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
                    {{ t('lesson.saved') }}
                </p>

                <template v-if="phase === 'answering'">
                    <Button
                        class="h-12 w-full text-base"
                        :disabled="!canCheck"
                        @click="check"
                    >
                        {{
                            isTeachFormat(format)
                                ? t('lesson.gotIt')
                                : t('lesson.check')
                        }}
                    </Button>
                </template>

                <template v-else-if="phase === 'checking'">
                    <Button class="h-12 w-full" disabled>
                        <AppSpinner />
                        {{ t('lesson.checking') }}
                    </Button>
                </template>

                <template
                    v-else-if="phase === 'self_check' && lesson.feedback.value"
                >
                    <i18n-t
                        keypath="lesson.selfCheck.prompt"
                        tag="p"
                        scope="global"
                        class="text-sm"
                    >
                        <template #expected>
                            <span class="font-semibold">{{
                                lesson.feedback.value.expected
                            }}</span>
                        </template>
                    </i18n-t>
                    <div class="grid grid-cols-2 gap-2">
                        <Button
                            class="h-12"
                            variant="outline"
                            @click="lesson.selfCheck(false)"
                        >
                            {{ t('lesson.selfCheck.no') }}
                        </Button>
                        <Button class="h-12" @click="lesson.selfCheck(true)">
                            {{ t('lesson.selfCheck.yes') }}
                        </Button>
                    </div>
                </template>

                <template v-else-if="phase === 'error'">
                    <p
                        class="text-sm text-red-700 dark:text-red-300"
                        role="alert"
                    >
                        {{
                            lesson.errorMessage.value || t('lesson.saveFailed')
                        }}
                    </p>
                    <Button class="h-12 w-full" @click="lesson.retry">{{
                        t('lesson.tryAgain')
                    }}</Button>
                </template>

                <template v-else-if="lesson.feedback.value">
                    <AnswerFeedback
                        :feedback="lesson.feedback.value"
                        :guided="format === 'write_guided'"
                        :locale="
                            answersInLearnedLanguage(format) ? locale : null
                        "
                        @flag="lesson.flag"
                    />
                    <Button
                        class="h-12 w-full text-base"
                        :disabled="lesson.feedback.value.saving"
                        @click="lesson.advance"
                    >
                        {{ t('common.continue') }}
                    </Button>
                </template>
            </div>
        </footer>
    </div>
</template>
