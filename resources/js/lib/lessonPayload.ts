import type { ExerciseBase } from '@/types/lesson';

export function text(value: unknown): string {
    return typeof value === 'string' ? value : '';
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
    return ['choose_meaning', 'choose_word', 'choose_gap'].includes(format);
}

export function isTypedFormat(format: string): boolean {
    return ['type_word', 'type_gap', 'translate_sentence'].includes(format);
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

    return `Starts with "${expected.charAt(0)}", ${letters} letters`;
}

export function languageName(locale: string | null): string {
    if (locale?.startsWith('pt')) {
        return 'Portuguese';
    }

    return 'Spanish';
}

export function instructionFor(format: string, language: string): string {
    switch (format) {
        case 'choose_meaning':
            return 'Choose the meaning';
        case 'choose_word':
            return `Choose the ${language} word`;
        case 'choose_gap':
            return 'Choose the word that fits';
        case 'match_pairs':
            return 'Match each word with its meaning';
        case 'type_word':
            return `Type it in ${language}`;
        case 'type_gap':
            return 'Type the missing word';
        case 'translate_sentence':
            return `Translate it into ${language}`;
        default:
            return '';
    }
}
