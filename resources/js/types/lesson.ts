export type LessonOrigin = 'lesson' | 'warmup' | 'review' | 'practice';

export type LessonRunKind =
    'lesson' | 'check' | 'practice' | 'retake' | 'test_out';

export type LessonState =
    | 'locked'
    | 'available'
    | 'opens_tomorrow'
    | 'in_progress'
    | 'completed'
    | 'coming';

export interface ExerciseBase {
    id: number;
    key: string;
    block: string;
    format: string;
    payload: Record<string, unknown>;
}

export interface PlanExercise extends ExerciseBase {
    origin: LessonOrigin;
    substitute: ExerciseBase | null;
}

export interface ServerAnswer {
    step: string;
    exerciseId: number;
    attempt: number;
    hinted: boolean;
    skipped: boolean;
    correct: boolean | null;
    flagged: boolean;
    settled: boolean;
}

export interface JournalAnswer {
    step: string;
    userId: number;
    runId: number;
    exerciseId: number;
    hinted: boolean;
    skipped: boolean;
    correct: boolean | null;
    settled: boolean;
    flagged: boolean;
    answeredAt: number;
}

export interface AnswerRecord {
    step: string;
    exerciseId: number;
    hinted: boolean;
    skipped: boolean;
    correct: boolean | null;
    flagged: boolean;
    settled: boolean;
}

export interface SummaryItem {
    term: string;
    translation: string | null;
    mastered: boolean;
}

export interface SummaryAnswer {
    prompt: string;
    given: string;
    expected: string;
    correct: boolean;
}

export interface RunSummary {
    accuracy: Record<string, number>;
    retried: string[];
    items: SummaryItem[];
    answers: SummaryAnswer[];
    cardsEnrolled: number;
    unitCompleted: boolean;
}

export interface NextLesson {
    lessonId: number;
    title: string;
    position: number;
    stage: string;
    state: LessonState;
}

export interface RunProps {
    id: number;
    kind: LessonRunKind;
    status: 'in_progress' | 'completed';
    probeSet: string | null;
    seed: number;
    startedAt: string;
    result: Record<string, unknown> | null;
    summary: RunSummary | null;
    next: NextLesson | null;
    summarySeen: boolean;
}

export interface LessonSettings {
    feedback: boolean;
    hintsAreFree: boolean;
    audioSpeed: number;
    replayLimit: number | null;
    offersSlowerAudio: boolean;
    speechLocale: string | null;
}

export interface PlayProps {
    unit: { id: number; title: string };
    run: RunProps;
    lesson: {
        id: number;
        unitId: number;
        stage: string;
        title: string;
        position: number;
    };
    settings: LessonSettings;
    plan: PlanExercise[];
    answers: ServerAnswer[];
}

export interface AnswerVerdict {
    correct: boolean;
    expected: string | null;
    note: string | null;
    score: number | null;
}

export interface AnswerResponse extends Partial<AnswerVerdict> {
    saved?: boolean;
    run: {
        completed: boolean;
        unitCompleted: boolean;
        mastery: { mastered: number; total: number };
    };
    milestone: { type: string; message: string } | null;
}

export interface UnitLessonRow {
    stage: string;
    title: string;
    position: number;
    lessonId: number | null;
    state: LessonState;
    bestAccuracy: number | null;
}

export interface UnitLessonOverview {
    lessons: UnitLessonRow[];
    mastery: { mastered: number; total: number };
    skipped: { listening: number; speaking: number };
    contentPending: boolean;
    canTestOut: boolean;
}
