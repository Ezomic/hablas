import { router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';
import { useOfflineSync } from '@/composables/useOfflineSync';
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
    expectedAnswer,
    isChoiceFormat,
    isTeachFormat,
    isTypedFormat,
    text,
} from '@/lib/lessonPayload';
import { buildQueue, isSettled, progress } from '@/lib/lessonQueue';
import type { QueueExercise, QueueStep } from '@/lib/lessonQueue';
import { newStep } from '@/lib/uuid';
import { store as storeAnswer } from '@/routes/lesson-runs/answers';
import { store as flagAnswer } from '@/routes/lesson-runs/answers/flag';
import type {
    AnswerRecord,
    AnswerResponse,
    ExerciseBase,
    JournalRequest,
    PlayProps,
} from '@/types/lesson';

// Listening and speaking are played from a later release, so for now the
// device skips them for their substitutes.
const UNSUPPORTED_FAMILIES = ['listening', 'speaking'];

export type Phase =
    'answering' | 'checking' | 'feedback' | 'self_check' | 'error';

export interface Feedback {
    step: string;
    correct: boolean;
    expected: string;
    note: string | null;
    given: string;
    flaggable: boolean;
    flagged: boolean;
    saving: boolean;
}

interface Unsent {
    step: string;
    url: string;
    body: Record<string, unknown>;
}

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
    const serverDone = ref(false);
    const mastery = ref<AnswerResponse['run']['mastery'] | null>(null);

    const queue = computed(() =>
        buildQueue(queueExercises.value, answers.value, {
            check,
            skipFamilies: UNSUPPORTED_FAMILIES,
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

        if (exercise.format === 'match_pairs') {
            const wrong = response.wrong;

            return {
                correct: Array.isArray(wrong) && wrong.length === 0,
                expected: '',
            };
        }

        return null;
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
                failure?.message ?? 'That could not be saved. Try again.';

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
        const given = text(response.text) || text(response.choice);
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

        feedback.value = {
            step: base.step,
            correct: local.correct,
            expected: local.expected,
            note: null,
            given,
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
        feedback.value = {
            step: base.step,
            correct,
            expected: result.data.expected ?? expectedAnswer(exercise),
            note: result.data.note ?? null,
            given,
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

    // A step the device cannot play is skipped for its substitute, which is
    // recorded as a skip so the original is never graded.
    async function skipUnplayable(shown: QueueStep) {
        const entry: AnswerRecord = {
            step: newStep(),
            exerciseId: shown.exerciseId,
            hinted: false,
            skipped: true,
            correct: null,
            flagged: false,
            settled: false,
        };

        const request = {
            url: storeAnswer({ lessonRun: props.run.id, step: entry.step }).url,
            body: {
                exercise_id: shown.exerciseId,
                skipped: true,
                skip_reason: 'unsupported',
                answered_at: new Date().toISOString(),
            },
        };

        record(entry, request);
        await send(request.url, request.body, entry.step);
    }

    // An answer the server neither holds nor has queued for the device (a
    // 5xx, an expired session, a failed network) is sent again from the
    // journal, to the same idempotent step url, so leaving the lesson never
    // loses it. A refusal that would repeat is dropped. On opening, the steps
    // answered since are left alone, as their own send is under way.
    const resending = new Set<string>();

    async function resendUnsent(skipAnsweredHere = false) {
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
        () => queue.value[0],
        (shown) => {
            if (shown?.skip === true && frozen.value === null) {
                void skipUnplayable(shown);
            }
        },
        { immediate: true },
    );

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

            if (isOnline.value && pendingCount.value === 0) {
                void resendUnsent().then(reloadRun);
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
    };
}
