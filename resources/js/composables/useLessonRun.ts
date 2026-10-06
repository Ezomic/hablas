import { router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';
import { useExactVoice } from '@/composables/useExactVoice';
import { useExercisePauses } from '@/composables/useExercisePauses';
import {
    deviceBelongsToSomeoneElse,
    useOfflineSync,
} from '@/composables/useOfflineSync';
import { i18n } from '@/i18n';
import { isRetryable } from '@/lib/http';
import {
    forgetJournal,
    markDelivered,
    mergeAnswers,
    readJournal,
    recordJournal,
} from '@/lib/lessonJournal';
import type { JournalEntry } from '@/lib/lessonJournal';
import {
    clipUrl,
    expectedAnswer,
    isChoiceFormat,
    isPassageFormat,
    isTeachFormat,
    isTypedFormat,
    passageAnswers,
    passageLines,
    text,
} from '@/lib/lessonPayload';
import {
    buildQueue,
    familyOf,
    isSettled,
    isSkippable,
    progress,
} from '@/lib/lessonQueue';
import type { QueueExercise, QueueStep } from '@/lib/lessonQueue';
import { isBrowserRecognitionSupported } from '@/lib/speechRecognizer';
import { newStep } from '@/lib/uuid';
import { store as storeAnswer } from '@/routes/lesson-runs/answers';
import { store as flagAnswer } from '@/routes/lesson-runs/answers/flag';
import type {
    AnswerRecord,
    AnswerResponse,
    ExerciseBase,
    ExerciseFamily,
    GuidedDetails,
    JournalRequest,
    PlayProps,
    SkipReason,
} from '@/types/lesson';

export type Phase =
    'answering' | 'checking' | 'feedback' | 'self_check' | 'error';

export interface Feedback {
    step: string;
    correct: boolean;
    expected: string;
    note: string | null;
    given: string;
    details: GuidedDetails | null;
    why: string | null;
    flaggable: boolean;
    flagged: boolean;
    saving: boolean;
}

interface Unsent {
    step: string;
    url: string;
    body: Record<string, unknown>;
}

const FINISH_STALL_MS = 6000;

export function useLessonRun(props: PlayProps) {
    const page = usePage();
    const { submitOrQueue, pendingCount, isOnline } = useOfflineSync();

    const userId = page.props.auth.user?.id ?? 0;
    const check = !props.settings.feedback;

    const exercises = computed(() => {
        const byId = new Map<number, ExerciseBase>();

        for (const entry of props.plan) {
            byId.set(entry.id, entry);

            if (entry.substitute !== null) {
                byId.set(entry.substitute.id, entry.substitute);
            }
        }

        return byId;
    });

    const recognitionSupported = isBrowserRecognitionSupported();
    const microphoneDenied = ref(false);
    const { exactVoice } = useExactVoice(() => props.settings.speechLocale);
    const pauses = useExercisePauses(props.settings.pauses, submitOrQueue);

    const queueExercises = computed<QueueExercise[]>(() =>
        props.plan.map((entry) => ({
            id: entry.id,
            format: entry.format,
            substituteId: entry.substitute?.id ?? null,
            substituteFormat: entry.substitute?.format ?? null,
        })),
    );

    const answers = ref<AnswerRecord[]>(mergeAnswers(props.answers, []));
    const phase = ref<Phase>('answering');
    const feedback = ref<Feedback | null>(null);
    const frozen = ref<QueueStep | null>(null);
    const hintShown = ref(false);
    const savedFlash = ref(false);
    const errorMessage = ref('');
    const step = ref(newStep());
    const unsent = ref<Unsent | null>(null);
    const finishing = ref(false);
    const finishStalled = ref(false);
    const serverDone = ref(false);
    const mastery = ref<AnswerResponse['run']['mastery'] | null>(null);

    const knownAnswers = ref<Record<number, string>>({});

    // A dictation is heard from its clip, because its text is never sent, and
    // a word heard and chosen is read by the browser when it has no clip, but
    // only in the right voice, so a Brazilian or Latin American voice never
    // teaches the pronunciation. Offline, a dictation cannot be self-checked
    // either, since there is no text to show.
    function cannotBePlayed(entry: ExerciseBase): boolean {
        const hasClip = clipUrl(entry.payload.audioUrl) !== null;

        if (entry.format === 'listen_type') {
            return !hasClip || !isOnline.value;
        }

        if (entry.format === 'listen_passage') {
            return passageLines(entry.payload).some(
                (line) =>
                    line.audioUrl === null &&
                    (line.text === '' || exactVoice.value === false),
            );
        }

        return (
            !hasClip &&
            (text(entry.payload.text) === '' || exactVoice.value === false)
        );
    }

    const skipFamilies = computed(() => {
        const families: ExerciseFamily[] = [];

        if (pauses.isPaused('listening')) {
            families.push('listening');
        }

        if (
            pauses.isPaused('speaking') ||
            !recognitionSupported ||
            microphoneDenied.value ||
            !isOnline.value
        ) {
            families.push('speaking');
        }

        return families;
    });

    const skipExerciseIds = computed(() =>
        props.plan
            .filter(
                (entry) =>
                    familyOf(entry.format) === 'listening' &&
                    entry.substitute !== null &&
                    cannotBePlayed(entry),
            )
            .map((entry) => entry.id),
    );

    function skipReasonFor(format: string): SkipReason {
        const family = familyOf(format);

        if (family === 'listening' || family === 'speaking') {
            if (pauses.isPaused(family)) {
                return 'paused';
            }
        }

        if (
            family === 'speaking' &&
            (!recognitionSupported || microphoneDenied.value)
        ) {
            return 'unsupported';
        }

        return !isOnline.value &&
            (family === 'speaking' || format === 'listen_type')
            ? 'offline'
            : 'unsupported';
    }

    const queue = computed(() =>
        buildQueue(queueExercises.value, answers.value, {
            check,
            skipFamilies: skipFamilies.value,
            skipExerciseIds: skipExerciseIds.value,
        }),
    );
    const current = computed(() => frozen.value ?? queue.value[0] ?? null);
    const currentExercise = computed(() =>
        current.value === null
            ? null
            : (exercises.value.get(current.value.shownId) ?? null),
    );
    const standing = computed(() =>
        progress(queueExercises.value, answers.value, check),
    );
    const percent = computed(() =>
        standing.value.total === 0
            ? 0
            : Math.round((standing.value.settled / standing.value.total) * 100),
    );
    const settledLocally = computed(() =>
        isSettled(queueExercises.value, answers.value, check),
    );
    const isCompleted = computed(() => props.run.status === 'completed');
    const hintShownNow = computed(
        () => hintShown.value || (current.value?.mode ?? 'normal') !== 'normal',
    );

    function resetStep() {
        step.value = newStep();
        hintShown.value = false;
        phase.value = 'answering';
        feedback.value = null;
        errorMessage.value = '';
        unsent.value = null;
    }

    function remember(entry: JournalEntry) {
        void recordJournal(userId, props.run.id, entry).catch(() => undefined);
    }

    // The answer joins the queue at once and the journal with the request
    // that delivers it, which stays until the server or the offline queue has
    // taken it. The journal write is issued before the request is sent, and
    // the write that clears the request is issued after it.
    const answeredHere = new Set<string>();

    function record(
        entry: AnswerRecord,
        request: JournalRequest | null = null,
    ) {
        answeredHere.add(entry.step);
        answers.value = [...answers.value, entry];
        remember({ ...entry, request });
    }

    function gradeLocally(
        exercise: ExerciseBase,
        response: Record<string, unknown>,
    ): { correct: boolean; expected: string } | null {
        if (isTeachFormat(exercise.format)) {
            return { correct: true, expected: '' };
        }

        if (isChoiceFormat(exercise.format)) {
            const expected = text(exercise.payload.answer);

            return { correct: text(response.choice) === expected, expected };
        }

        if (isPassageFormat(exercise.format)) {
            const answers = passageAnswers(exercise.payload);
            const choices = Array.isArray(response.choices)
                ? response.choices
                : [];

            return answers === null
                ? null
                : {
                      correct:
                          choices.length === answers.length &&
                          answers.every(
                              (answer, index) => choices[index] === answer,
                          ),
                      expected: answers.join(' / '),
                  };
        }

        if (exercise.format === 'match_pairs') {
            const wrong = response.wrong;

            return {
                correct: Array.isArray(wrong) && wrong.length === 0,
                expected: '',
            };
        }

        return null;
    }

    function givenOf(response: Record<string, unknown>): string {
        const spoken = Array.isArray(response.transcripts)
            ? response.transcripts.filter(
                  (item): item is string => typeof item === 'string',
              )
            : [];

        const chosen = Array.isArray(response.choices)
            ? response.choices.filter(
                  (item): item is string => typeof item === 'string',
              )
            : [];

        return (
            text(response.text) ||
            text(response.choice) ||
            chosen.join(' / ') ||
            (spoken.at(-1) ?? '')
        );
    }

    function whyOf(exercise: ExerciseBase): string | null {
        return text(exercise.payload.why) || null;
    }

    function learn(
        exercise: ExerciseBase,
        expected: string | null | undefined,
    ) {
        if (expected !== null && expected !== undefined && expected !== '') {
            knownAnswers.value = {
                ...knownAnswers.value,
                [exercise.id]: expected,
            };
        }
    }

    function body(
        exercise: ExerciseBase,
        response: Record<string, unknown>,
        extra: Record<string, unknown> = {},
    ): Record<string, unknown> {
        return {
            exercise_id: exercise.id,
            hinted: hintShownNow.value && !check,
            response,
            answered_at: new Date().toISOString(),
            ...extra,
        };
    }

    function answerUrl(): string {
        return storeAnswer({ lessonRun: props.run.id, step: step.value }).url;
    }

    function delivered(step: string | undefined) {
        if (step !== undefined) {
            void markDelivered(userId, step).catch(() => undefined);
        }
    }

    async function send(
        url: string,
        payload: Record<string, unknown>,
        step?: string,
    ): Promise<{ queued: boolean; ok: boolean; data: AnswerResponse | null }> {
        const result = await submitOrQueue(url, payload);

        if (result.queued) {
            delivered(step);

            return { queued: true, ok: true, data: null };
        }

        if (!result.response.ok) {
            const failure = (await result.response
                .json()
                .catch(() => null)) as { message?: string } | null;
            errorMessage.value =
                failure?.message ?? i18n.global.t('lesson.saveFailed');

            return { queued: false, ok: false, data: null };
        }

        delivered(step);

        return {
            queued: false,
            ok: true,
            data: (await result.response.json()) as AnswerResponse,
        };
    }

    function noteCompletion(data: AnswerResponse | null) {
        if (data === null) {
            return;
        }

        mastery.value = data.run.mastery;

        if (data.run.completed) {
            serverDone.value = true;
        }
    }

    function reloadRun() {
        router.reload();
    }

    async function submit(response: Record<string, unknown>) {
        const exercise = currentExercise.value;
        const shown = current.value;

        if (
            exercise === null ||
            shown === null ||
            phase.value !== 'answering'
        ) {
            return;
        }

        frozen.value = shown;

        if (check) {
            phase.value = 'checking';
        }

        const payload = body(exercise, response);
        const local = check ? null : gradeLocally(exercise, response);
        const given = givenOf(response);
        const base = {
            step: step.value,
            exerciseId: exercise.id,
            hinted: payload.hinted === true,
            skipped: false,
            flagged: false,
        };

        if (check) {
            record(
                { ...base, correct: null, settled: true },
                { url: answerUrl(), body: payload },
            );
            savedFlash.value = true;
            setTimeout(() => (savedFlash.value = false), 900);
            const result = await send(answerUrl(), payload, base.step);

            if (!result.ok) {
                unsent.value = {
                    step: base.step,
                    url: answerUrl(),
                    body: payload,
                };
                phase.value = 'error';

                return;
            }

            noteCompletion(result.data);
            advance();

            return;
        }

        if (local !== null) {
            await submitGraded(exercise, local, base, payload, given);

            return;
        }

        await submitTyped(exercise, base, payload, given);
    }

    async function submitGraded(
        exercise: ExerciseBase,
        local: { correct: boolean; expected: string },
        base: Omit<AnswerRecord, 'correct' | 'settled'>,
        payload: Record<string, unknown>,
        given: string,
    ) {
        record(
            { ...base, correct: local.correct, settled: local.correct },
            { url: answerUrl(), body: payload },
        );

        if (isTeachFormat(exercise.format)) {
            const result = await send(answerUrl(), payload, base.step);

            if (!result.ok) {
                unsent.value = {
                    step: base.step,
                    url: answerUrl(),
                    body: payload,
                };
                phase.value = 'error';

                return;
            }

            noteCompletion(result.data);
            advance();

            return;
        }

        learn(exercise, local.expected);
        feedback.value = {
            step: base.step,
            correct: local.correct,
            expected: local.expected,
            note: null,
            given,
            details: null,
            why: whyOf(exercise),
            flaggable: false,
            flagged: false,
            saving: true,
        };
        phase.value = 'feedback';
        const result = await send(answerUrl(), payload, base.step);

        if (feedback.value !== null) {
            feedback.value = { ...feedback.value, saving: false };
        }

        if (!result.ok) {
            unsent.value = {
                step: base.step,
                url: answerUrl(),
                body: payload,
            };
            phase.value = 'error';

            return;
        }

        noteCompletion(result.data);
    }

    async function submitTyped(
        exercise: ExerciseBase,
        base: Omit<AnswerRecord, 'correct' | 'settled'>,
        payload: Record<string, unknown>,
        given: string,
    ) {
        phase.value = 'checking';

        const result = await send(answerUrl(), payload);

        if (result.queued && expectedAnswer(exercise) === '') {
            record(
                { ...base, correct: null, settled: true },
                { url: answerUrl(), body: payload },
            );
            delivered(base.step);
            savedFlash.value = true;
            setTimeout(() => (savedFlash.value = false), 900);
            advance();

            return;
        }

        if (result.queued) {
            unsent.value = {
                step: base.step,
                url: answerUrl(),
                body: payload,
            };
            phase.value = 'self_check';
            feedback.value = {
                step: base.step,
                correct: false,
                expected: expectedAnswer(exercise),
                note: null,
                given,
                details: null,
                why: whyOf(exercise),
                flaggable: false,
                flagged: false,
                saving: false,
            };

            return;
        }

        if (!result.ok || result.data === null) {
            phase.value = 'error';
            unsent.value = {
                step: base.step,
                url: answerUrl(),
                body: payload,
            };

            return;
        }

        const correct = result.data.correct === true;

        record({ ...base, correct, settled: correct });
        learn(exercise, result.data.expected);
        feedback.value = {
            step: base.step,
            correct,
            expected: result.data.expected ?? expectedAnswer(exercise),
            note: result.data.note ?? null,
            given,
            details: result.data.details ?? null,
            why: whyOf(exercise),
            flaggable: !correct,
            flagged: false,
            saving: false,
        };
        phase.value = 'feedback';
        noteCompletion(result.data);
    }

    // Offline, a typed answer cannot be graded, so the learner says whether
    // he had it. That is for the flow only: the server grades the answer when
    // it syncs, and its grade is the one that counts.
    async function selfCheck(hadIt: boolean) {
        const exercise = currentExercise.value;
        const pending = unsent.value;

        if (exercise === null || pending === null || feedback.value === null) {
            return;
        }

        const payload = { ...pending.body, self_graded_correct: hadIt };

        record(
            {
                step: pending.step,
                exerciseId: exercise.id,
                hinted: pending.body.hinted === true,
                skipped: false,
                flagged: false,
                correct: hadIt,
                settled: hadIt,
            },
            { url: pending.url, body: payload },
        );
        feedback.value = { ...feedback.value, correct: hadIt };
        phase.value = 'feedback';
        await send(pending.url, payload, pending.step);
    }

    async function retry() {
        const pending = unsent.value;

        if (pending === null) {
            return;
        }

        errorMessage.value = '';

        const result = await send(pending.url, pending.body, pending.step);

        if (!result.ok) {
            phase.value = 'error';

            return;
        }

        unsent.value = null;
        noteCompletion(result.data);

        if (feedback.value !== null) {
            phase.value = 'feedback';
        } else {
            advance();
        }
    }

    async function flag() {
        const shown = feedback.value;

        if (shown === null || shown.flagged) {
            return;
        }

        feedback.value = { ...shown, flagged: true };
        answers.value = answers.value.map((entry) =>
            entry.step === shown.step
                ? { ...entry, flagged: true, settled: true }
                : entry,
        );

        const entry = answers.value.find(
            (candidate) => candidate.step === shown.step,
        );

        if (entry !== undefined) {
            remember(entry);
        }

        const result = await submitOrQueue(
            flagAnswer({ lessonRun: props.run.id, step: shown.step }).url,
            {},
        );

        if (!result.queued && result.response.ok) {
            noteCompletion((await result.response.json()) as AnswerResponse);
        }
    }

    function advance() {
        frozen.value = null;
        resetStep();
    }

    function showHint() {
        hintShown.value = true;
    }

    // A skip puts the substitute in the exercise's place and is recorded with
    // its reason, so the original is never graded and the unit page can say
    // what was skipped.
    async function skipExercise(
        exerciseId: number,
        format: string,
        reason: SkipReason,
    ) {
        const entry: AnswerRecord = {
            step: newStep(),
            exerciseId,
            hinted: false,
            skipped: true,
            skipReason: reason,
            correct: null,
            flagged: false,
            settled: false,
        };

        const request = {
            url: storeAnswer({ lessonRun: props.run.id, step: entry.step }).url,
            body: {
                exercise_id: exerciseId,
                skipped: true,
                skip_reason: reason,
                answered_at: new Date().toISOString(),
            },
        };

        record(entry, request);
        hintShown.value = false;
        await send(request.url, request.body, entry.step);
    }

    const entries = computed(
        () => new Map(props.plan.map((entry) => [entry.id, entry])),
    );

    const canSkip = computed(() => {
        const shown = current.value;

        return (
            phase.value === 'answering' &&
            shown !== null &&
            shown.shownId === shown.exerciseId &&
            isSkippable(shown.format) &&
            entries.value.get(shown.exerciseId)?.substitute != null
        );
    });

    function skip() {
        const shown = current.value;

        if (canSkip.value && shown !== null) {
            void skipExercise(shown.exerciseId, shown.format, 'chosen');
        }
    }

    // What the learner is told once, in a line, when an exercise was swapped
    // without being asked to: the reason it was.
    const swapReason = computed<SkipReason | null>(() => {
        const shown = current.value;

        if (shown === null || shown.shownId === shown.exerciseId) {
            return null;
        }

        const skipped = answers.value.findLast(
            (answer) =>
                answer.exerciseId === shown.exerciseId && answer.skipped,
        );

        return skipped?.skipReason && skipped.skipReason !== 'chosen'
            ? skipped.skipReason
            : null;
    });

    function denyMicrophone() {
        microphoneDenied.value = true;
    }

    // An answer the server neither holds nor has queued for the device (a
    // 5xx, an expired session, a failed network) is sent again from the
    // journal, to the same idempotent step url, so leaving the lesson never
    // loses it. A refusal that would repeat is dropped. On opening, the steps
    // answered since are left alone, as their own send is under way.
    const resending = new Set<string>();

    async function resendUnsent(skipAnsweredHere = false) {
        if (deviceBelongsToSomeoneElse(userId)) {
            return;
        }

        const held = new Set(props.answers.map((answer) => answer.step));
        const rows = await readJournal(userId, props.run.id).catch(() => []);

        for (const row of rows) {
            const request = row.request;

            if (
                !request ||
                held.has(row.step) ||
                resending.has(row.step) ||
                (skipAnsweredHere && answeredHere.has(row.step))
            ) {
                continue;
            }

            resending.add(row.step);

            try {
                const result = await submitOrQueue(request.url, request.body);

                if (result.queued) {
                    delivered(row.step);
                } else if (result.response.ok) {
                    delivered(row.step);
                    noteCompletion(
                        (await result.response.json()) as AnswerResponse,
                    );
                } else if (!isRetryable(result.response.status)) {
                    delivered(row.step);
                }
            } finally {
                resending.delete(row.step);
            }
        }
    }

    watch(
        [() => queue.value[0], frozen],
        ([shown]) => {
            if (shown?.skip === true && frozen.value === null) {
                void skipExercise(
                    shown.exerciseId,
                    shown.format,
                    skipReasonFor(shown.format),
                );
            }
        },
        { immediate: true },
    );

    // A request that never answers or a reload that fails must not leave the
    // finishing screen spinning: it retries once by itself, then offers a button.
    let stallTimer: ReturnType<typeof setTimeout> | undefined;

    function finish() {
        void resendUnsent().then(reloadRun, () => undefined);
    }

    function armStall() {
        if (stallTimer !== undefined) {
            return;
        }

        stallTimer = setTimeout(() => {
            stallTimer = undefined;
            finishStalled.value = true;

            if (isOnline.value) {
                finish();
            }
        }, FINISH_STALL_MS);
    }

    function retryFinish() {
        finishStalled.value = false;
        armStall();
        finish();
    }

    // Once every exercise is settled on the device, the server's own settling
    // and result are fetched as soon as the queued answers have drained.
    watch(
        [settledLocally, serverDone, pendingCount, isOnline, frozen],
        () => {
            if (
                !(settledLocally.value || serverDone.value) ||
                isCompleted.value ||
                frozen.value !== null ||
                phase.value === 'feedback' ||
                phase.value === 'checking' ||
                phase.value === 'self_check'
            ) {
                return;
            }

            finishing.value = true;
            armStall();

            if (isOnline.value && pendingCount.value === 0) {
                finish();
            }
        },
        { immediate: true },
    );

    // The journal is read asynchronously, so an answer given meanwhile is
    // kept next to what it returns.
    async function refreshAnswers() {
        const merged = mergeAnswers(
            props.answers,
            await readJournal(userId, props.run.id).catch(() => []),
        );
        const known = new Set(merged.map((answer) => answer.step));

        answers.value = [
            ...merged,
            ...answers.value.filter((answer) => !known.has(answer.step)),
        ];
    }

    watch(() => props.answers, refreshAnswers);

    watch(
        isCompleted,
        (done) => {
            if (done) {
                clearTimeout(stallTimer);
                stallTimer = undefined;
                finishStalled.value = false;
                void forgetJournal(userId, props.run.id).catch(() => undefined);
            }
        },
        { immediate: true },
    );

    watch(isOnline, (online) => {
        if (online) {
            void resendUnsent();
        }
    });

    onMounted(async () => {
        await refreshAnswers();
        await resendUnsent(true);
    });

    return {
        answers,
        phase,
        feedback,
        current,
        currentExercise,
        percent,
        standing,
        settledLocally,
        isCompleted,
        isOnline,
        pendingCount,
        finishing,
        finishStalled,
        retryFinish,
        hintShown: hintShownNow,
        savedFlash,
        errorMessage,
        mastery,
        check,
        isTyped: computed(() =>
            isTypedFormat(currentExercise.value?.format ?? ''),
        ),
        submit,
        selfCheck,
        retry,
        flag,
        advance,
        showHint,
        skip,
        canSkip,
        swapReason,
        pauses,
        denyMicrophone,
        knownAnswers,
        exactVoice,
    };
}
