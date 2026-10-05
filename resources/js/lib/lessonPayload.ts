import { i18n } from '@/i18n';
import type { ExerciseBase } from '@/types/lesson';

export function text(value: unknown): string {
    return typeof value === 'string' ? value : '';
}

export function clipUrl(value: unknown): string | null {
    return typeof value === 'string' && value !== '' ? value : null;
}

export function strings(value: unknown): string[] {
    return Array.isArray(value)
        ? value.filter((item): item is string => typeof item === 'string')
        : [];
}

/**
 * What a learner is expected to give, as the device knows it: the answer of a
 * choice, or the first accepted text of a typed answer. A check carries no
 * answer keys, so this is empty there.
 */
export function expectedAnswer(exercise: ExerciseBase): string {
    const answer = text(exercise.payload.answer);

    if (answer !== '') {
        return answer;
    }

    return strings(exercise.payload.accepted)[0] ?? '';
}

export function isChoiceFormat(format: string): boolean {
    return [
        'choose_meaning',
        'choose_word',
        'choose_gap',
        'listen_choose',
        'listen_pair',
    ].includes(format);
}

export function isTypedFormat(format: string): boolean {
    return [
        'type_word',
        'type_gap',
        'translate_sentence',
        'transform_sentence',
        'write_guided',
        'listen_type',
    ].includes(format);
}

export function answersInLearnedLanguage(format: string): boolean {
    return [
        'type_word',
        'type_gap',
        'build_sentence',
        'translate_sentence',
        'transform_sentence',
        'write_guided',
        'listen_type',
        'listen_pair',
        'choose_word',
        'choose_gap',
        'speak_repeat',
        'speak_answer',
    ].includes(format);
}

export function isListenFormat(format: string): boolean {
    return ['listen_choose', 'listen_pair', 'listen_type'].includes(format);
}

export function isPassageFormat(format: string): boolean {
    return ['read_passage', 'listen_passage'].includes(format);
}

export function isSpeakFormat(format: string): boolean {
    return format.startsWith('speak_');
}

export function isTeachFormat(format: string): boolean {
    return format.startsWith('teach_');
}

/** The first letter and the length of the expected answer, as a hint. */
export function hintFor(exercise: ExerciseBase): string {
    const expected = expectedAnswer(exercise);

    if (expected === '') {
        return '';
    }

    const letters = expected.replace(/[^\p{L}]/gu, '').length;

    return i18n.global.t(
        'lesson.hint.startsWith',
        { letter: expected.charAt(0), count: letters },
        letters,
    );
}

export function languageName(locale: string | null): string {
    const code = locale?.slice(0, 2) ?? 'es';

    return i18n.global.t(
        `lesson.language.${['es', 'pt', 'fr', 'it'].includes(code) ? code : 'es'}`,
    );
}

export function instructionFor(format: string, language: string): string {
    const { t } = i18n.global;

    switch (format) {
        case 'choose_meaning':
            return t('lesson.instruction.chooseMeaning');
        case 'choose_word':
            return t('lesson.instruction.chooseWord', { language });
        case 'choose_gap':
            return t('lesson.instruction.chooseGap');
        case 'match_pairs':
            return t('lesson.instruction.matchPairs');
        case 'type_word':
            return t('lesson.instruction.typeWord', { language });
        case 'type_gap':
            return t('lesson.instruction.typeGap');
        case 'translate_sentence':
            return t('lesson.instruction.translate', { language });
        default:
            return '';
    }
}

export interface PassageQuestion {
    prompt: string;
    options: string[];
    answer: string | null;
}

export interface PassageLine {
    speaker: string;
    text: string;
    audioUrl: string | null;
    audioSlowUrl: string | null;
}

function records(value: unknown): Record<string, unknown>[] {
    return Array.isArray(value)
        ? value.filter(
              (item): item is Record<string, unknown> =>
                  typeof item === 'object' && item !== null,
          )
        : [];
}

export function passageQuestions(
    payload: Record<string, unknown>,
): PassageQuestion[] {
    return records(payload.questions).map((question) => ({
        prompt: text(question.prompt),
        options: strings(question.options),
        answer: text(question.answer) || null,
    }));
}

export function passageLines(payload: Record<string, unknown>): PassageLine[] {
    return records(payload.lines ?? payload.dialogue).map((line) => ({
        speaker: text(line.speaker),
        text: text(line.text),
        audioUrl: clipUrl(line.audioUrl),
        audioSlowUrl: clipUrl(line.audioSlowUrl),
    }));
}

/** The answers of every question, or null when any is missing, as in a check. */
export function passageAnswers(
    payload: Record<string, unknown>,
): string[] | null {
    const questions = passageQuestions(payload);

    return questions.length > 0 &&
        questions.every((question) => question.answer !== null)
        ? questions.map((question) => question.answer as string)
        : null;
}

export function glossesOf(
    payload: Record<string, unknown>,
): [string, string][] {
    const glosses = payload.glosses;

    if (typeof glosses !== 'object' || glosses === null) {
        return [];
    }

    return Object.entries(glosses).filter(
        (entry): entry is [string, string] =>
            typeof entry[1] === 'string' && entry[1] !== '',
    );
}

/** The English line shown under a prompt, unless it only repeats the prompt. */
export function englishLine(payload: Record<string, unknown>): string {
    const english = text(payload.english);

    return english !== '' && english !== text(payload.prompt) ? english : '';
}

/** The letters of a typed word, null where the learner types one. */
export function maskOf(value: unknown): (string | null)[] | null {
    if (!Array.isArray(value) || value.length === 0) {
        return null;
    }

    return value.every((entry) => entry === null || typeof entry === 'string')
        ? (value as (string | null)[])
        : null;
}

/** The letters a hint can still give, in the order they come back. */
export function hintLettersOf(
    value: unknown,
): { index: number; char: string }[] {
    if (!Array.isArray(value)) {
        return [];
    }

    return value.filter(
        (entry): entry is { index: number; char: string } =>
            typeof entry === 'object' &&
            entry !== null &&
            typeof entry.index === 'number' &&
            typeof entry.char === 'string',
    );
}
